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
        $agency = $user->agency;

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
        $agency = $user->agency;

        if (!$agency) {
            return redirect()->back()->with('error', 'Agency profile not found.');
        }

        $request->validate([
            'subject' => 'required|string|max:255',
            'product_id' => 'nullable|exists:products,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/attachments', 'public');
        }

        $ticket = Ticket::create([
            'ticket_number' => Ticket::generateTicketNumber(),
            'agency_id' => $agency->id,
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'subject' => $request->subject,
            'priority' => $request->priority,
            'status' => 'open',
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'last_replied_at' => now(),
        ]);

        return redirect()->route('whitelabel.tickets.show', $ticket->id)
            ->with('success', 'Support ticket #' . $ticket->ticket_number . ' raised successfully! Our support team will review it shortly.');
    }

    public function show($id)
    {
        $user = auth()->user();
        $agency = $user->agency;

        $ticket = Ticket::with(['agency', 'product', 'user', 'assignedStaff', 'replies.user'])
            ->where('agency_id', $agency->id)
            ->findOrFail($id);

        return view('whitelabel.tickets.show', compact('ticket', 'agency'));
    }

    public function reply(Request $request, $id)
    {
        $user = auth()->user();
        $agency = $user->agency;

        $ticket = Ticket::where('agency_id', $agency->id)->findOrFail($id);

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip|max:10240',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('tickets/replies', 'public');
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
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
}
