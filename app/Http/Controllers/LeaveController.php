<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;
use Carbon\Carbon;

class LeaveController extends Controller
{
    // ... index & create (sama seperti sebelumnya) ...
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('staff')) {
            $data = Leave::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $query = Leave::with('user')->orderBy('created_at', 'desc');
            if ($user->hasRole('manager')) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            }
            $data = $query->get();
        }
        return view('leave.index', compact('data'));
    }

    public function create()
    {
        return view('leave.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required',
            'attachment' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        // === LOGIKA H-3 ===
        $start = Carbon::parse($request->start_date);
        $today = Carbon::now()->startOfDay();
        $diffDays = $today->diffInDays($start, false);

        // Jika bukan sakit DAN kurang dari 3 hari, tolak
        if ($request->type != 'sakit' && $diffDays < 3) {
            return back()->with('error', 'Pengajuan Cuti/Izin minimal H-3! (Kecuali Sakit)');
        }
        // ==================

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('leave_attachments', 'public');
        }

        Leave::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'attachment' => $attachmentPath,
            'status' => 'pending'
        ]);

        // Notif ke Manager
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $managers = User::where('role', 'manager')->where('department_id', $user->department_id)->get();
        if ($managers->isEmpty()) $managers = User::where('role', 'manager')->get();

        foreach ($managers as $manager) {
            $manager->notify(new SystemNotification("📢 Pengajuan Cuti Baru: " . $user->name, route('leave.index')));
        }

        return redirect()->route('leave.index')->with('success', 'Pengajuan cuti berhasil dikirim.');
    }

    // ... approve & reject (sama seperti sebelumnya) ...
    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'approved_manager', 'approved_by' => Auth::id()]);
        if ($leave->user) $leave->user->notify(new SystemNotification("✅ Cuti Disetujui.", route('leave.index')));
        return back()->with('success', 'Cuti disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $leave = Leave::findOrFail($id);
        $leave->update(['status' => 'rejected', 'approved_by' => Auth::id(), 'rejection_note' => $request->rejection_note]);
        if ($leave->user) $leave->user->notify(new SystemNotification("❌ Cuti Ditolak.", route('leave.index')));
        return back()->with('error', 'Cuti ditolak.');
    }
}
