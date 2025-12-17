<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Data umum
        $karyawanCount = Karyawan::count();
        $absensiToday = Absensi::whereDate('tanggal', today())->count();
        $karyawanAktif = Karyawan::where('status_karyawan', 'aktif')->count();

        // Data khusus berdasarkan role
        if ($user->role === 'supervisor') {
            return $this->supervisorDashboard($karyawanCount, $absensiToday, $karyawanAktif);
        } else if ($user->role === 'pemilik') {
            return $this->pemilikDashboard($karyawanCount, $absensiToday, $karyawanAktif);
        }

        // Default dashboard (bisa untuk operator atau role lainnya)
        return view('dashboard', compact('karyawanCount', 'absensiToday', 'karyawanAktif'));
    }

    private function supervisorDashboard($karyawanCount, $absensiToday, $karyawanAktif)
    {
        // Data khusus supervisor
        $hadirToday = Absensi::whereDate('tanggal', today())
            ->where('status_absensi', 'hadir')->count();

        $terlambatToday = Absensi::whereDate('tanggal', today())
            ->where('jam_masuk', '>', '08:00:00')
            ->whereNotNull('jam_masuk')
            ->count();

        // Data operator (TIDAK ORDER BY created_at)
        $operatorPerformance = Karyawan::where('jabatan', 'operator')
            ->where('status_karyawan', 'aktif')
            ->get()
            ->map(function ($karyawan) {
                // Data dummy untuk produksi
                $karyawan->total_produksi = rand(300, 600);
                return $karyawan;
            });

        // Cek absensi pagi
        $absensiPagiDone = Absensi::whereDate('tanggal', today())
            ->whereTime('jam_masuk', '<', '09:00:00')
            ->exists();

        // Data dummy produksi
        $produksiToday = rand(2000, 5000);

        return view('dashboard.supervisor', compact(
            'karyawanCount',
            'absensiToday',
            'karyawanAktif',
            'hadirToday',
            'terlambatToday',
            'operatorPerformance',
            'absensiPagiDone',
            'produksiToday'
        ));
    }

    private function pemilikDashboard($karyawanCount, $absensiToday, $karyawanAktif)
    {
        // Data keuangan pemilik
        $totalPengeluaranGaji = Absensi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->sum('total_gaji') ?? 0;

        // Biaya operasional
        $biayaOperasional = 10000000;
        $totalPengeluaran = $totalPengeluaranGaji + $biayaOperasional;

        // Estimasi pendapatan (hindari division by zero)
        $rataHadirPerHari = $absensiToday > 0 ? $absensiToday / now()->day : 0;
        $estimasiPendapatan = $rataHadirPerHari * 750000 * now()->day;

        // Hitung profit margin dengan cek pembagi nol
        if ($estimasiPendapatan > 0) {
            $profitMargin = round(($estimasiPendapatan - $totalPengeluaran) / $estimasiPendapatan * 100, 1);
        } else {
            $profitMargin = 0;
        }

        // Productivity rate dengan cek pembagi nol
        if ($karyawanAktif > 0 && now()->day > 0) {
            $productivityRate = round(($absensiToday / ($karyawanAktif * now()->day)) * 100, 1);
        } else {
            $productivityRate = 0;
        }

        // Growth rate (dibandingkan bulan lalu)
        $absensiBulanLalu = Absensi::whereMonth('tanggal', now()->subMonth()->month)
            ->whereYear('tanggal', now()->subMonth()->year)
            ->count();

        if ($absensiBulanLalu > 0) {
            $growthRate = round((($absensiToday * now()->day) - $absensiBulanLalu) / $absensiBulanLalu * 100, 1);
        } else {
            $growthRate = 100; // Jika bulan lalu 0, growth 100%
        }

        // Retention rate dengan cek pembagi nol
        if ($karyawanCount > 0) {
            $retentionRate = round(($karyawanAktif / $karyawanCount) * 100, 1);
        } else {
            $retentionRate = 0;
        }

        return view('dashboard.pemilik', compact(
            'karyawanCount',
            'absensiToday',
            'karyawanAktif',
            'totalPengeluaran',
            'totalPengeluaranGaji',
            'biayaOperasional',
            'estimasiPendapatan',
            'profitMargin',
            'productivityRate',
            'growthRate',
            'retentionRate'
        ));
    }
}
