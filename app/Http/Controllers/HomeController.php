<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Leave;
use App\Models\OvertimeRequest;
use App\Models\Payroll;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        $today = date('Y-m-d');
        $month = date('m');

        $data = [];

        // === DASHBOARD ADMIN (Global Stats) ===
        if ($user->hasRole('admin')) {
            $data['total_pegawai'] = User::where('role', '!=', 'admin')->where('is_active', 1)->count();
            $data['pegawai_hadir'] = Attendance::where('date', $today)->count();
            $data['total_dept']    = Department::count();
            // Total pengeluaran gaji bulan ini (yang sudah paid)
            $data['total_gaji']    = Payroll::where('month', date('m-Y'))->sum('net_salary');

            // List 5 absen terakhir (Realtime Monitoring)
            $data['recent_absences'] = Attendance::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        }

        // === DASHBOARD MANAGER (Team Stats) ===
        elseif ($user->hasRole('manager')) {
            // Pegawai di departemen yang sama
            $myStaffIds = User::where('department_id', $user->department_id)->pluck('id');

            $data['team_total'] = $myStaffIds->count();
            $data['team_hadir'] = Attendance::whereIn('user_id', $myStaffIds)->where('date', $today)->count();

            // Approval Pending
            $data['pending_cuti']   = Leave::whereIn('user_id', $myStaffIds)->where('status', 'pending')->count();
            $data['pending_lembur'] = OvertimeRequest::whereIn('user_id', $myStaffIds)->where('status', 'pending')->count();
        }

        // === DASHBOARD STAFF (Personal Stats) ===
        else {
            $data['hadir_bulan_ini'] = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->whereIn('status', ['present', 'late', 'overtime_weekend'])
                ->count();

            $data['telat_bulan_ini'] = Attendance::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->sum('late_minutes');

            $data['lembur_acc']      = OvertimeRequest::where('user_id', $user->id)
                ->whereMonth('date', $month)
                ->where('status', 'approved')
                ->count();

            // Gaji Terakhir
            $lastPayroll = Payroll::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            $data['gaji_terakhir'] = $lastPayroll ? $lastPayroll->net_salary : 0;
        }

        return view('home', compact('data'));
    }
}
