<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Notifications\SystemNotification;

class LoanController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->hasRole('staff')) {
            $data = Loan::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data = Loan::with('user')->orderBy('created_at', 'desc')->get();
        }
        return view('loan.index', compact('data'));
    }

    public function create()
    {
        return view('loan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100000',
            'installments' => 'required|numeric|min:1|max:12',
            'reason' => 'required',
        ]);

        $user = Auth::user();

        // Cek Hutang Aktif
        $activeLoan = Loan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'active'])
            ->exists();

        if ($activeLoan) {
            return back()->with('error', 'Anda masih memiliki pinjaman berjalan!');
        }

        // Cek Limit Cicilan (Max 30%)
        $gajiPokok = $user->salary->basic_salary ?? 0;
        $cicilan = $request->amount / $request->installments;
        $limit = $gajiPokok * 0.3;

        if ($cicilan > $limit) {
            return back()->with('error', 'Cicilan melebihi 30% gaji pokok! Limit: Rp ' . number_format($limit));
        }

        Loan::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'installments' => $request->installments,
            'installment_amount' => $cicilan,
            'remaining_amount' => $request->amount,
            'reason' => $request->reason,
            'status' => 'pending'
        ]);

        // === NOTIFIKASI KE MANAGER (FAILSAFE) ===
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $managers = User::where('role', 'manager')
            ->where('department_id', $user->department_id)
            ->get();

        if ($managers->isEmpty()) {
            $managers = User::where('role', 'manager')->get();
        }

        $pesan = "💰 Pengajuan Kasbon Baru dari: " . $user->name;
        $url = route('loan.index');

        foreach ($managers as $manager) {
            $manager->notify(new SystemNotification($pesan, $url));
        }
        // ========================================

        return redirect()->route('loan.index')->with('success', 'Pinjaman diajukan. Menunggu approval.');
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'active', 'approved_by' => Auth::id()]);

        if ($loan->user) {
            $loan->user->notify(new SystemNotification("✅ Kasbon Disetujui & Cair.", route('loan.index')));
        }

        return back()->with('success', 'Pinjaman disetujui & dicairkan.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'rejected']);

        if ($loan->user) {
            $loan->user->notify(new SystemNotification("❌ Kasbon Ditolak.", route('loan.index')));
        }

        return back()->with('error', 'Pinjaman ditolak.');
    }
}
