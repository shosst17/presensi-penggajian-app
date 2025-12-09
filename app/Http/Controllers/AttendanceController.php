<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficeLocation;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // 1. HALAMAN RIWAYAT
    public function index()
    {
        $user = Auth::user();
        $history = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return view('attendance.index', compact('history'));
    }

    // 2. HALAMAN ABSEN (FORM)
    public function create()
    {
        $user = Auth::user();
        $date = date('Y-m-d');
        $now  = date('H:i:s');
        $dayOfWeek = Carbon::now()->dayOfWeekIso; // 1=Senin ... 6=Sabtu
        $isWeekend = ($dayOfWeek >= 6);

        // Ambil Kantor
        $office = $user->office;
        if (!$office) {
            return redirect()->route('home')->with('error', 'Anda belum ditempatkan di kantor manapun! Hubungi Admin.');
        }

        // Cek Absen Hari Ini
        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();

        // Ambil Jam Kerja dari Database
        $jamMasuk   = $office->start_time;
        $jamPulang  = $office->end_time;

        // Jendela Buka Absen (30 Menit sebelum jam masuk)
        $bukaAbsen = date('H:i:s', strtotime("$jamMasuk -30 minutes"));

        // VALIDASI JENDELA WAKTU
        if (!$attendance) {
            // --- MAU ABSEN MASUK ---
            // Jika Weekday & Kepagian -> Tolak
            // Jika Weekend -> Bebas (karena lembur)
            if (!$isWeekend && $now < $bukaAbsen) {
                return redirect()->route('home')->with('error', "Absen Masuk belum dibuka! Harap tunggu sampai pukul $bukaAbsen");
            }
        } else {
            // --- MAU ABSEN PULANG ---
            if ($attendance->check_out_time) {
                return redirect()->route('home')->with('warning', 'Anda sudah menyelesaikan absensi hari ini.');
            }

            // Jika Weekday & Belum Jam Pulang -> Tolak
            // Jika Weekend -> Bebas pulang kapan saja
            if (!$isWeekend && $now < $jamPulang) {
                return redirect()->route('home')->with('error', "Belum jam pulang kantor ($jamPulang)! Jika sakit/izin setengah hari, hubungi Admin.");
            }
        }

        return view('attendance.create', compact('office', 'attendance'));
    }

    // 3. PROSES SIMPAN (LOGIC UTAMA)
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required',
            'longitude' => 'required',
            'image'     => 'required',
        ]);

        $user = Auth::user();
        $office = $user->office;

        if (!$office) return redirect()->back()->with('error', 'Data kantor tidak valid.');

        $date = date('Y-m-d');
        $time = date('H:i:s');

        // A. HITUNG JARAK (Server-Side)
        $distance = $this->calculateDistance(
            $request->latitude,
            $request->longitude,
            $office->latitude,
            $office->longitude
        );

        // Validasi Radius
        if ($distance > $office->radius_meters) {
            return redirect()->back()->with('error', 'Anda berada di luar radius kantor! Jarak: ' . round($distance) . 'm (Maks: ' . $office->radius_meters . 'm)');
        }

        // B. DECODE FOTO
        $image_parts = explode(";base64,", $request->image);
        $image_base64 = base64_decode($image_parts[1]);

        $attendance = Attendance::where('user_id', $user->id)->where('date', $date)->first();

        // Cek Hari Libur
        $dayOfWeek = Carbon::now()->dayOfWeekIso;
        $isWeekend = ($dayOfWeek >= 6);

        if ($attendance) {
            // === PROSES PULANG ===

            // Cek Double
            if ($attendance->check_out_time) {
                return redirect()->route('home')->with('error', 'Sudah absen pulang sebelumnya.');
            }

            $fileName = $user->id . '_' . date('Ymd_His') . '_out.jpg';
            Storage::disk('public')->put('attendance_photos/' . $fileName, $image_base64);

            $attendance->update([
                'check_out_time' => $time,
                'check_out_lat'  => $request->latitude,
                'check_out_long' => $request->longitude,
                'check_out_photo' => $fileName,
            ]);

            return redirect()->route('home')->with('success', 'Hati-hati di jalan! Absen pulang berhasil.');
        } else {
            // === PROSES MASUK ===

            $fileName = $user->id . '_' . date('Ymd_His') . '_in.jpg';
            Storage::disk('public')->put('attendance_photos/' . $fileName, $image_base64);

            // LOGIKA STATUS & TERLAMBAT
            $jamMasuk = Carbon::parse($office->start_time);
            $jamAbsen = Carbon::parse($time);

            // Ambil toleransi dari database (Default 0 jika kolom belum ada/null)
            $toleransi = $office->entry_grace_minutes ?? 0;

            // Hitung batas waktu aman (Jam Masuk + Toleransi)
            $batasToleransi = $jamMasuk->copy()->addMinutes($toleransi);

            $status = 'present';
            $lateminutes = 0;

            if ($isWeekend) {
                // Jika Sabtu/Minggu -> Dianggap Lembur Weekend (Tidak ada telat)
                $status = 'overtime_weekend';
            } else {
                // Jika Weekday -> Cek apakah lewat batas toleransi?
                if ($jamAbsen->greaterThan($batasToleransi)) {
                    $status = 'late';
                    // Hitung telat dari jam masuk ASLI (Fairness)
                    $lateminutes = $jamAbsen->diffInMinutes($jamMasuk);
                }
            }

            Attendance::create([
                'user_id'       => $user->id,
                'date'          => $date,
                'check_in_time' => $time,
                'check_in_lat'  => $request->latitude,
                'check_in_long' => $request->longitude,
                'check_in_photo' => $fileName,
                'status'        => $status,
                'late_minutes'  => $lateminutes,
            ]);

            // Pesan Feedback
            if ($status == 'late') {
                $msg = "Absen berhasil, namun Anda TERLAMBAT $lateminutes menit (Toleransi $toleransi menit).";
                return redirect()->route('home')->with('warning', $msg); // Pakai warning biar sadar
            } else {
                return redirect()->route('home')->with('success', 'Selamat Bekerja! Absen Tepat Waktu.');
            }
        }
    }

    // RUMUS HAVERSINE (Hitung Jarak GPS)
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Meter
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }

    // REKAP ABSENSI (ADMIN)
    public function recap(Request $request)
    {
        if (Auth::user()->role == 'staff') abort(403);

        $bulan = $request->month ?? date('m');
        $tahun = $request->year ?? date('Y');

        $data = Attendance::with('user')
            ->whereMonth('date', $bulan)
            ->whereYear('date', $tahun)
            ->orderBy('date', 'desc')
            ->get();

        if ($request->has('download_pdf')) {
            $pdf = Pdf::loadView('attendance.print_recap', compact('data', 'bulan', 'tahun'));
            return $pdf->download('Laporan-Absensi-' . $bulan . '-' . $tahun . '.pdf');
        }

        return view('attendance.recap', compact('data', 'bulan', 'tahun'));
    }
}
