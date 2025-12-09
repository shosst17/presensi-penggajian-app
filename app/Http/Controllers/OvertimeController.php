<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Notifications\SystemNotification;

class OvertimeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('staff')) {
            $data = OvertimeRequest::where('user_id', $user->id)->with('user')->orderBy('date', 'desc')->get();
        } else {
            $query = OvertimeRequest::with('user')->orderBy('date', 'desc');
            if ($user->hasRole('manager')) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            }
            $data = $query->get();
        }

        return view('overtime.index', compact('data'));
    }

    public function create()
    {
        return view('overtime.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'reason' => 'required',
        ]);

        // 1. Hitung Durasi Pengajuan
        $start = Carbon::parse($request->date . ' ' . $request->start_time);
        $end   = Carbon::parse($request->date . ' ' . $request->end_time);

        if ($end->lessThan($start)) $end->addDay(); // Lintas hari

        $duration = $start->diffInMinutes($end); // Durasi dalam menit

        // 2. Ambil Aturan Kantor dari User yang Login
        $user = Auth::user();
        $office = $user->office;

        $minOvertime = $office ? $office->min_overtime_minutes : 60;
        $maxOvertime = $office ? $office->max_overtime_minutes : 120; // Default 2 jam
        $jamMasukKantor = $office ? $office->start_time : '08:00:00';
        $jamPulangKantor = $office ? $office->end_time : '17:00:00';

        // 3. Cek Hari (Weekday vs Weekend)
        $dayOfWeek = Carbon::parse($request->date)->dayOfWeekIso; // 1=Senin, 6=Sabtu, 7=Minggu
        $isWeekend = ($dayOfWeek >= 6);

        // === LOGIKA WEEKDAY (SENIN-JUMAT) ===
        if (!$isWeekend) {
            // Rule A: Minimal Durasi
            if ($duration < $minOvertime) {
                return back()->with('error', "Lembur Weekday minimal $minOvertime menit!");
            }

            // Rule B: Maksimal Durasi (CAPPING)
            if ($duration > $maxOvertime) {
                return back()->with('error', "Lembur Weekday tidak boleh lebih dari $maxOvertime menit (2 Jam)!");
            }
        }

        // === LOGIKA WEEKEND (SABTU-MINGGU) ===
        else {
            // Rule C: Harus dalam Jam Operasional Kantor
            // Cek apakah jam mulai < jam masuk kantor?
            if ($request->start_time < $jamMasukKantor) {
                return back()->with('error', "Lembur Weekend tidak boleh sebelum jam masuk kantor ($jamMasukKantor)!");
            }

            // Cek apakah jam selesai > jam pulang kantor?
            if ($request->end_time > $jamPulangKantor) {
                return back()->with('error', "Lembur Weekend tidak boleh melebihi jam operasional ($jamPulangKantor)!");
            }
        }

        // Simpan Data
        OvertimeRequest::create([
            'user_id' => $user->id,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $duration,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        // Notif ke Manager (Failsafe Logic)
        $managers = User::where('role', 'manager')
            ->where('department_id', Auth::user()->department_id)
            ->get();

        if ($managers->isEmpty()) {
            $managers = User::where('role', 'manager')->get();
        }

        $pesan = "⏳ Pengajuan Lembur Baru dari: " . Auth::user()->name;
        $url = route('overtime.index');

        foreach ($managers as $manager) {
            $manager->notify(new SystemNotification($pesan, $url));
        }

        return redirect()->route('overtime.index')->with('success', 'Pengajuan lembur berhasil dikirim.');
    }

    public function approve($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $overtime->update(['status' => 'approved', 'approved_by' => Auth::id()]);

        if ($overtime->user) {
            $overtime->user->notify(new SystemNotification("✅ Lembur Disetujui.", route('overtime.index')));
        }

        return back()->with('success', 'Lembur disetujui.');
    }

    public function reject(Request $request, $id)
    {
        $overtime = OvertimeRequest::findOrFail($id);
        $overtime->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'rejection_note' => $request->rejection_note
        ]);

        if ($overtime->user) {
            $overtime->user->notify(new SystemNotification("❌ Lembur Ditolak.", route('overtime.index')));
        }

        return back()->with('error', 'Lembur ditolak.');
    }
}
