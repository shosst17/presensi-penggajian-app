<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payroll;
use App\Models\User;
use App\Models\Attendance;
use App\Models\OvertimeRequest;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class PayrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->hasRole('staff')) {
            $data = Payroll::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data = Payroll::with('user')->orderBy('created_at', 'desc')->get();
        }

        return view('payroll.index', compact('data'));
    }

    // METHOD GENERATE GAJI
    public function store(Request $request)
    {
        if (!Auth::user()->hasRole('admin')) return back()->with('error', 'Akses Ditolak');

        $bulan = date('m-Y');
        // Hanya pegawai aktif yang digaji
        $users = User::where('role', '!=', 'admin')->where('is_active', 1)->get();
        $countGenerated = 0;

        foreach ($users as $u) {
            // Cek Double (Jangan gaji 2x di bulan yang sama)
            if (Payroll::where('user_id', $u->id)->where('month', $bulan)->exists()) continue;

            $salaryData = $u->salary;
            if (!$salaryData) continue;

            // 1. KEHADIRAN (Termasuk Lembur Weekend)
            $hadirCount = Attendance::where('user_id', $u->id)
                ->whereMonth('date', date('m'))->whereYear('date', date('Y'))
                ->whereIn('status', ['present', 'late', 'overtime_weekend'])->count();

            // Logic: Kalau bolos sebulan penuh (0 Hadir) & Bukan Direktur -> SKIP
            if ($hadirCount == 0 && !$u->hasRole('director')) continue;

            // 2. DENDA TELAT
            $telatMenit = Attendance::where('user_id', $u->id)
                ->whereMonth('date', date('m'))->whereYear('date', date('Y'))
                ->sum('late_minutes');
            $dendaTelat = $telatMenit * 1000; // Contoh denda 1000/menit

            // 3. UANG LEMBUR
            $lemburMenit = OvertimeRequest::where('user_id', $u->id)
                ->whereMonth('date', date('m'))->where('status', 'approved')
                ->sum('duration_minutes');
            $uangLembur = ($lemburMenit / 60) * ($salaryData->basic_salary / 173);

            // 4. KASBON
            $loan = Loan::where('user_id', $u->id)->where('status', 'active')->first();
            $potonganKasbon = 0;
            if ($loan) {
                $potonganKasbon = $loan->installment_amount;
                $loan->remaining_amount -= $potonganKasbon;
                if ($loan->remaining_amount <= 0) $loan->status = 'paid_off';
                $loan->save();
            }

            // 5. === POTONGAN TETAP (BPJS & PAJAK) ===
            $gajiPokok = $salaryData->basic_salary;

            // BPJS (Nominal Langsung)
            $potonganBpjs = $salaryData->bpjs;

            // Pajak PPH21 (Persentase dari Gaji Pokok)
            $persenPajak = $salaryData->tax;
            $potonganPajak = $gajiPokok * ($persenPajak / 100);

            // 6. HITUNG FINAL
            $tunjangan = $salaryData->position_allowance;
            $variable  = ($hadirCount * $salaryData->daily_meal_allowance) + ($hadirCount * $salaryData->daily_transport_allowance);

            $totalPendapatan = $gajiPokok + $tunjangan + $variable + $uangLembur;
            $totalPotongan   = $dendaTelat + $potonganKasbon + $potonganBpjs + $potonganPajak;

            $thp = $totalPendapatan - $totalPotongan;
            if ($thp < 0) $thp = 0;

            Payroll::create([
                'user_id' => $u->id,
                'month' => $bulan,
                'generated_date' => now(),
                'basic_salary' => $gajiPokok,
                'allowances' => $tunjangan + $variable,
                'overtime_pay' => $uangLembur,
                'deductions' => $totalPotongan, // Total semua potongan
                'net_salary' => $thp,
                'status' => 'paid'
            ]);

            $countGenerated++;
        }

        return back()->with('success', "Payroll Selesai. $countGenerated slip gaji diterbitkan.");
    }

    public function show($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        if (Auth::user()->hasRole('staff') && $payroll->user_id != Auth::id()) abort(403);
        return view('payroll.show', compact('payroll'));
    }

    public function print($id)
    {
        $payroll = Payroll::with('user')->findOrFail($id);
        if (Auth::user()->hasRole('staff') && $payroll->user_id != Auth::id()) abort(403);

        $pdf = Pdf::loadView('payroll.pdf', compact('payroll'));
        return $pdf->download('Slip-Gaji-' . $payroll->month . '-' . $payroll->user->name . '.pdf');
    }
}
