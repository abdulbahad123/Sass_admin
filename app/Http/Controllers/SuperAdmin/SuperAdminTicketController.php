<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Product;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;

class SuperAdminTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['agency', 'product', 'user', 'assignedStaff', 'replies']);

        // Filters
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('assigned_to')) {
            if ($request->assigned_to === 'unassigned') {
                $query->whereNull('assigned_to');
            } else {
                $query->where('assigned_to', $request->assigned_to);
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%")
                  ->orWhereHas('agency', function ($a) use ($search) {
                      $a->where('name', 'LIKE', "%{$search}%");
                  });
            });
        }

        $tickets = $query->orderBy('updated_at', 'desc')->paginate(15);

        // Stats Counter Cards
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'open')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'pending_reply' => Ticket::where('status', 'pending_reply')->count(),
            'resolved' => Ticket::whereIn('status', ['resolved', 'closed'])->count(),
        ];

        $agencies = Agency::orderBy('name')->get();
        $products = Product::orderBy('name')->get();
        $staffMembers = User::where('role', 'super_admin')->orderBy('name')->get();

        return view('admin.tickets.index', compact('tickets', 'stats', 'agencies', 'products', 'staffMembers'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['agency', 'product', 'user', 'assignedStaff', 'replies.user'])->findOrFail($id);
        $staffMembers = User::where('role', 'super_admin')->orderBy('name')->get();

        return view('admin.tickets.show', compact('ticket', 'staffMembers'));
    }

    public function reply(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $user = auth()->user();

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,zip|max:10240',
            'status' => 'nullable|in:open,in_progress,pending_reply,resolved,closed',
            'is_internal_note' => 'nullable|boolean',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = time() . '_' . bin2hex(random_bytes(5)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/tickets/replies'), $filename);
            $attachmentPath = 'uploads/tickets/replies/' . $filename;
        }

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->message,
            'attachment' => $attachmentPath,
            'is_internal_note' => $request->boolean('is_internal_note', false),
        ]);

        // Automatically assign staff to current user if unassigned
        if (!$ticket->assigned_to) {
            $ticket->assigned_to = $user->id;
        }

        // Update status if provided or default to in_progress
        $newStatus = $request->filled('status') ? $request->status : ($ticket->status === 'open' ? 'in_progress' : $ticket->status);

        $ticket->update([
            'status' => $newStatus,
            'last_replied_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Reply posted successfully.');
    }

    public function assignStaff(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        $ticket->assigned_to = $request->assigned_to;
        if ($ticket->status === 'open' && $request->assigned_to) {
            $ticket->status = 'in_progress';
        }
        $ticket->save();

        $staffName = $ticket->assignedStaff ? $ticket->assignedStaff->name : 'Unassigned';

        return redirect()->back()->with('success', "Ticket staff assignment updated to {$staffName}.");
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,pending_reply,resolved,closed',
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update([
            'status' => $request->status,
            'priority' => $request->priority,
        ]);

        return redirect()->back()->with('success', 'Ticket status & priority updated.');
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $number = $ticket->ticket_number;
        $ticket->delete();

        return redirect()->route('admin.tickets.index')->with('success', "Ticket #{$number} deleted successfully.");
    }
}
