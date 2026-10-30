<?php

namespace App\Http\Controllers\WhiteLabel;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhiteLabelTicketController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $agency = \App\Models\Agency::resolveCurrent($request, $user);

        if (!$agency) {
            return redirect()->route('whitelabel.dashboard')->with('error', 'Agency profile not found.');
        }

        $query = Ticket::with(['product', 'assignedStaff', 'replies'])
            ->where('agency_id', $agency->id);

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(10);

        // Stats
        $stats = [
            'total' => Ticket::where('agency_id', $agency->id)->count(),
            'open' => Ticket::where('agency_id', $agency->id)->where('status', 'open')->count(),
            'in_progress' => Ticket::where('agency_id', $agency->id)->where('status', 'in_progress')->count(),
            'resolved' => Ticket::where('agency_id', $agency->id)->whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $products = $agency->products;

        return view('whitelabel.tickets.index', compact('tickets', 'stats', 'products', 'agency'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $agency = \App\Models\Agency::resolveCurrent($request, $user);

        if (!$agency) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $request->validate(array_merge([
            'subject' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string',
        ], $this->getAttachmentValidationRules()));

        $uploadedPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets/attachments'), $filename);
                    $uploadedPaths[] = 'uploads/tickets/attachments/' . $filename;
                }
            }
        } elseif ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets/attachments'), $filename);
            $uploadedPaths[] = 'uploads/tickets/attachments/' . $filename;
        }

        $attachmentData = count($uploadedPaths) > 1 ? json_encode($uploadedPaths) : ($uploadedPaths[0] ?? null);

        $ticket = Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'agency_id' => $agency->id,
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
            'message' => $request->message,
            'attachment' => $attachmentData,
            'last_replied_at' => now(),
        ]);

        return redirect()->route('whitelabel.tickets.show', $ticket->id)
            ->with('success', 'Support ticket #' . $ticket->ticket_number . ' raised successfully! Our support team will review it shortly.');
    }

    public function show($id)
    {
        $user = auth()->user();
        $agency = \App\Models\Agency::resolveCurrent(request(), $user);

        $ticket = Ticket::with(['agency', 'product', 'user', 'assignedStaff', 'replies.user'])
            ->where('agency_id', $agency->id)
            ->findOrFail($id);

        return view('whitelabel.tickets.show', compact('ticket', 'agency'));
    }

    public function reply(Request $request, $id)
    {
        $user = auth()->user();
        $agency = \App\Models\Agency::resolveCurrent($request, $user);

        $ticket = Ticket::where('agency_id', $agency->id)->findOrFail($id);

        $request->validate(array_merge([
            'message' => 'required|string',
        ], $this->getAttachmentValidationRules()));

        $uploadedPaths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('uploads/tickets/replies'), $filename);
                    $uploadedPaths[] = 'uploads/tickets/replies/' . $filename;
                }
            }
        } elseif ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets/replies'), $filename);
            $uploadedPaths[] = 'uploads/tickets/replies/' . $filename;
        }

        $attachmentData = count($uploadedPaths) > 1 ? json_encode($uploadedPaths) : ($uploadedPaths[0] ?? null);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'attachment' => $attachmentData,
            'is_internal_note' => false,
        ]);

        // Reopen ticket if resolved or closed, or set to pending admin response
        $newStatus = in_array($ticket->status, ['resolved', 'closed']) ? 'open' : 'pending_reply';

        $ticket->update([
            'status' => $newStatus,
            'last_replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reply submitted successfully.');
    }

    protected function getAttachmentValidationRules(): array
    {
        $allowed = ['jpeg', 'jpg', 'png', 'pdf', 'webp', 'doc', 'docx', 'zip'];
        $rule = extension_loaded('fileinfo')
            ? 'file|mimes:' . implode(',', $allowed) . '|max:10240'
            : ['file', function($attr, $val, $fail) use ($allowed) {
                if (!$val) return;
                if ($val->getSize() > 10240 * 1024) { $fail('Attachment file must not exceed 10MB.'); return; }
                $ext = strtolower($val->getClientOriginalExtension() ?: pathinfo($val->getClientOriginalName(), PATHINFO_EXTENSION));
                if (!in_array($ext, $allowed, true)) { $fail('Invalid file type. Allowed: ' . implode(', ', $allowed)); }
            }];

        return [
            'attachments'   => 'nullable|array|max:5',
            'attachments.*' => $rule,
            'attachment'    => is_array($rule) ? array_merge(['nullable'], $rule) : 'nullable|' . $rule,
        ];
    }
}
