@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1"><i class="bi bi-speedometer2 me-2"></i>Dashboard IKM Otomotif</h2>
                        <p class="text-muted mb-0">Sistem Manajemen Terintegrasi</p>
                    </div>
                    <div class="text-end">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <small class="text-muted d-block">Tanggal</small>
                                <strong>{{ now()->translatedFormat('l, d F Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Stats Row -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-primary border-3 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-primary text-uppercase mb-1">
                                    Total Karyawan
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $karyawanCount }}</div>
                                <div class="mt-2 mb-0">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i> {{ $karyawanAktif }} aktif
                                    </span>
                                    <span class="badge bg-secondary ms-1">
                                        {{ $karyawanCount - $karyawanAktif }} nonaktif
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-success border-3 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-success text-uppercase mb-1">
                                    Absensi Hari Ini
                                </div>
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $absensiToday }}</div>
                                <div class="mt-2 mb-0">
                                    @php
                                        $hadirToday = \App\Models\Absensi::whereDate('tanggal', today())
                                            ->where('status_absensi', 'hadir')
                                            ->count();
                                        $cutiToday = \App\Models\Absensi::whereDate('tanggal', today())
                                            ->where('status_absensi', 'cuti')
                                            ->count();
                                        $tidakHadirToday = \App\Models\Absensi::whereDate('tanggal', today())
                                            ->where('status_absensi', 'tidak hadir')
                                            ->count();
                                    @endphp
                                    <span class="badge bg-success me-1">
                                        {{ $hadirToday }} hadir
                                    </span>
                                    <span class="badge bg-warning me-1">
                                        {{ $cutiToday }} cuti
                                    </span>
                                    <span class="badge bg-danger">
                                        {{ $tidakHadirToday }} tidak hadir
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-calendar-check text-success" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-info border-3 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-info text-uppercase mb-1">
                                    Kehadiran Bulan Ini
                                </div>
                                @php
                                    $hadirBulanIni = \App\Models\Absensi::whereMonth('tanggal', now()->month)
                                        ->whereYear('tanggal', now()->year)
                                        ->where('status_absensi', 'hadir')
                                        ->count();
                                    $attendanceRate =
                                        $karyawanAktif > 0 ? round(($hadirToday / $karyawanAktif) * 100, 1) : 0;
                                @endphp
                                <div class="h5 mb-0 fw-bold text-gray-800">{{ $hadirBulanIni }}</div>
                                <div class="mt-2 mb-0">
                                    <span class="badge bg-info">
                                        {{ $attendanceRate }}% kehadiran hari ini
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-graph-up text-info" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-warning border-3 shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col me-2">
                                <div class="text-xs fw-bold text-warning text-uppercase mb-1">
                                    Gaji Bulan Ini
                                </div>
                                @php
                                    $totalGajiBulanIni = \App\Models\Absensi::whereMonth('tanggal', now()->month)
                                        ->whereYear('tanggal', now()->year)
                                        ->sum('total_gaji');
                                    $avgGajiPerHari = $absensiToday > 0 ? round($totalGajiBulanIni / now()->day) : 0;
                                @endphp
                                <div class="h5 mb-0 fw-bold text-gray-800">Rp
                                    {{ number_format($totalGajiBulanIni, 0, ',', '.') }}</div>
                                <div class="mt-2 mb-0">
                                    <span class="badge bg-warning">
                                        Rp {{ number_format($avgGajiPerHari, 0, ',', '.') }} / hari
                                    </span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="bi bi-cash-stack text-warning" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Detailed Stats -->
        <div class="row mb-4">
            <!-- Left Column: Charts -->
            <div class="col-xl-8 col-lg-7">
                <!-- Attendance Chart Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3">
                        <h6 class="m-0 fw-bold"><i class="bi bi-bar-chart me-2"></i>Statistik Kehadiran 7 Hari Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area" style="height: 300px;">
                            <canvas id="attendanceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Karyawan -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold"><i class="bi bi-people me-2"></i>Data Karyawan Terbaru</h6>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-primary">
                            <i class="bi bi-arrow-right me-1"></i> Kelola
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Status</th>
                                        <th>Total Hadir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $recentKaryawan = \App\Models\Karyawan::orderBy('created_at', 'desc')
                                            ->limit(5)
                                            ->get();
                                    @endphp

                                    @if ($recentKaryawan->count() > 0)
                                        @foreach ($recentKaryawan as $karyawan)
                                            <tr>
                                                <td><code>{{ $karyawan->id_karyawan }}</code></td>
                                                <td>
                                                    <div class="fw-semibold">{{ $karyawan->nama_karyawan }}</div>
                                                    <small class="text-muted">{{ $karyawan->email ?: '-' }}</small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $karyawan->jabatan == 'pemilik' ? 'warning' : ($karyawan->jabatan == 'supervisor' ? 'info' : 'secondary') }}">
                                                        {{ ucfirst($karyawan->jabatan) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge bg-{{ $karyawan->status_karyawan == 'aktif' ? 'success' : 'danger' }}">
                                                        {{ ucfirst($karyawan->status_karyawan) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        {{ $karyawan->total_hadir }} hari
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-3">
                                                <i class="bi bi-people text-muted mb-2" style="font-size: 2rem;"></i>
                                                <p class="text-muted mb-0">Belum ada data karyawan</p>
                                                <a href="{{ route('karyawan.create') }}"
                                                    class="btn btn-sm btn-primary mt-2">
                                                    <i class="bi bi-plus-circle me-1"></i> Tambah Karyawan
                                                </a>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activity -->
            <div class="col-xl-4 col-lg-5">
                <!-- Recent Absensi Card -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Absensi Terbaru</h6>
                        <a href="#" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-plus-circle me-1"></i> Tambah
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @php
                                $recentAbsensi = \App\Models\Absensi::with('karyawan')
                                    ->orderBy('tanggal', 'desc')
                                    ->orderBy('jam_masuk', 'desc')
                                    ->limit(8)
                                    ->get();
                            @endphp

                            @if ($recentAbsensi->count() > 0)
                                @foreach ($recentAbsensi as $absensi)
                                    <div class="list-group-item border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm">
                                                    <span
                                                        class="avatar-title rounded-circle bg-{{ $absensi->status_absensi == 'hadir' ? 'success' : ($absensi->status_absensi == 'cuti' ? 'warning' : 'danger') }}-subtle text-{{ $absensi->status_absensi == 'hadir' ? 'success' : ($absensi->status_absensi == 'cuti' ? 'warning' : 'danger') }}">
                                                        <i class="bi bi-person"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $absensi->karyawan->nama_karyawan ?? 'N/A' }}</h6>
                                                <p class="text-muted mb-0 small">
                                                    {{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}
                                                    @if ($absensi->jam_masuk)
                                                        • {{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }}
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="badge bg-{{ $absensi->status_absensi == 'hadir' ? 'success' : ($absensi->status_absensi == 'cuti' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($absensi->status_absensi) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-calendar-x text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted">Belum ada data absensi</p>
                                    <a href="#" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-circle me-1"></i> Tambah Absensi
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Performers Card -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3">
                        <h6 class="m-0 fw-bold"><i class="bi bi-trophy me-2"></i>Top Performers Bulan Ini</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @php
                                // Top performers berdasarkan total hadir bulan ini
                                $topPerformers = \App\Models\Karyawan::select('m_karyawan.*')
                                    ->selectSub(function ($query) {
                                        $query
                                            ->from('m_absensi')
                                            ->whereColumn('m_absensi.id_karyawan', 'm_karyawan.id_karyawan')
                                            ->whereMonth('tanggal', now()->month)
                                            ->whereYear('tanggal', now()->year)
                                            ->where('status_absensi', 'hadir')
                                            ->selectRaw('COUNT(*)');
                                    }, 'hadir_bulan_ini')
                                    ->where('status_karyawan', 'aktif')
                                    ->orderByDesc('hadir_bulan_ini')
                                    ->limit(5)
                                    ->get();
                            @endphp

                            @if ($topPerformers->count() > 0)
                                @foreach ($topPerformers as $index => $karyawan)
                                    <div class="list-group-item border-0">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0">
                                                <div class="avatar-sm">
                                                    @if ($index == 0)
                                                        <div class="avatar-title bg-warning text-dark rounded-circle">
                                                            <i class="bi bi-trophy-fill"></i>
                                                        </div>
                                                    @elseif($index == 1)
                                                        <div class="avatar-title bg-secondary text-white rounded-circle">
                                                            <i class="bi bi-2-circle-fill"></i>
                                                        </div>
                                                    @elseif($index == 2)
                                                        <div class="avatar-title bg-danger text-white rounded-circle">
                                                            <i class="bi bi-3-circle-fill"></i>
                                                        </div>
                                                    @else
                                                        <div class="avatar-title bg-light text-muted rounded-circle">
                                                            {{ $index + 1 }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-0">{{ $karyawan->nama_karyawan }}</h6>
                                                <p class="text-muted mb-0 small">
                                                    <span
                                                        class="badge bg-{{ $karyawan->jabatan == 'supervisor' ? 'info' : 'secondary' }}">
                                                        {{ ucfirst($karyawan->jabatan) }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <span class="badge bg-success">
                                                    <i class="bi bi-check-circle me-1"></i>
                                                    {{ $karyawan->hadir_bulan_ini ?? 0 }} hari
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <i class="bi bi-people text-muted mb-3" style="font-size: 3rem;"></i>
                                    <p class="text-muted">Belum ada data performa</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-light py-3">
                        <h6 class="m-0 fw-bold"><i class="bi bi-lightning me-2"></i>Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('karyawan.create') }}"
                                    class="btn btn-primary w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-person-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>Tambah Karyawan</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-success w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-calendar-plus mb-2" style="font-size: 2rem;"></i>
                                    <span>Tambah Absensi</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('karyawan.index') }}"
                                    class="btn btn-info w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-people mb-2" style="font-size: 2rem;"></i>
                                    <span>Kelola Karyawan</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="#"
                                    class="btn btn-warning w-100 d-flex flex-column align-items-center py-3">
                                    <i class="bi bi-cash-stack mb-2" style="font-size: 2rem;"></i>
                                    <span>Laporan Gaji</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
        }

        .border-start-primary {
            border-left-color: #4e73df !important;
        }

        .border-start-success {
            border-left-color: #1cc88a !important;
        }

        .border-start-info {
            border-left-color: #36b9cc !important;
        }

        .border-start-warning {
            border-left-color: #f6c23e !important;
        }

        .avatar-sm {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .chart-area {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .list-group-item {
            border-left: 0;
            border-right: 0;
        }

        .list-group-item:first-child {
            border-top: 0;
        }

        .list-group-item:last-child {
            border-bottom: 0;
        }

        .quick-action-btn {
            height: 120px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }

        .quick-action-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Attendance Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('attendanceChart').getContext('2d');

            // Data real dari database untuk 7 hari terakhir
            const labels = [];
            const hadirData = [];
            const cutiData = [];
            const tidakHadirData = [];

            // Generate data untuk 7 hari terakhir
            for (let i = 6; i >= 0; i--) {
                const date = new Date();
                date.setDate(date.getDate() - i);
                const dateString = date.toISOString().split('T')[0];

                labels.push(date.toLocaleDateString('id-ID', {
                    weekday: 'short',
                    day: 'numeric'
                }));

                // Fetch data dari database (dummy, sesuaikan dengan kebutuhan)
                @php
                    // Data dummy untuk chart - bisa diganti dengan query database
                    $chartData = [];
                    for ($i = 6; $i >= 0; $i--) {
                        $date = now()->subDays($i);
                        $chartData[$date->format('Y-m-d')] = [
                            'hadir' => rand(5, 15),
                            'cuti' => rand(0, 3),
                            'tidak_hadir' => rand(0, 2),
                        ];
                    }
                @endphp

                // Gunakan data dari PHP
                hadirData.push({{ $chartData[$dateString]['hadir'] ?? '0' }});
                cutiData.push({{ $chartData[$dateString]['cuti'] ?? '0' }});
                tidakHadirData.push({{ $chartData[$dateString]['tidak_hadir'] ?? '0' }});
            }

            const attendanceChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Hadir',
                        data: hadirData,
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }, {
                        label: 'Cuti',
                        data: cutiData,
                        borderColor: '#f6c23e',
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }, {
                        label: 'Tidak Hadir',
                        data: tidakHadirData,
                        borderColor: '#e74a3b',
                        backgroundColor: 'rgba(231, 74, 59, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: 'rgba(0, 0, 0, 0.7)',
                            titleFont: {
                                size: 14
                            },
                            bodyFont: {
                                size: 13
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                drawBorder: false
                            },
                            ticks: {
                                stepSize: 2
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index',
                    }
                }
            });

            // Auto refresh dashboard setiap 5 menit (300000 ms)
            setTimeout(function() {
                window.location.reload();
            }, 300000);
        });
    </script>
@endpush
