<!-- resources/views/dashboard/pemilik.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <h3><i class="bi bi-person-fill-gear"></i> Dashboard Pemilik</h3>
                <p class="text-muted">Overview bisnis dan keuangan</p>
            </div>
        </div>

        <!-- Stats Keuangan -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h4 mb-1">Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</div>
                        <small class="text-muted">Total Pengeluaran</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h4 mb-1">Rp {{ number_format($estimasiPendapatan ?? 0, 0, ',', '.') }}</div>
                        <small class="text-muted">Estimasi Pendapatan</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        @php
                            $profitMargin = $profitMargin ?? 0;
                        @endphp
                        <div class="h2 mb-0 {{ $profitMargin >= 0 ? 'text-success' : 'text-danger' }}">
                            {{ $profitMargin }}%
                        </div>
                        <small class="text-muted">Profit Margin</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-6 mb-3">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="h2 mb-0">{{ $karyawanCount ?? 0 }}</div>
                        <small class="text-muted">Total Karyawan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ringkasan Bisnis -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-cash-stack"></i> Keuangan</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Pengeluaran Bulan Ini:</strong> Rp
                            {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                        <p><strong>Biaya Gaji:</strong> Rp {{ number_format($totalPengeluaranGaji ?? 0, 0, ',', '.') }}
                        </p>
                        <p><strong>Biaya Operasional:</strong> Rp {{ number_format($biayaOperasional ?? 0, 0, ',', '.') }}
                        </p>
                        <p><strong>Estimasi Pendapatan:</strong> Rp
                            {{ number_format($estimasiPendapatan ?? 0, 0, ',', '.') }}</p>

                        <div class="mt-3">
                            <p class="mb-1"><strong>Profit Margin:</strong> {{ $profitMargin ?? 0 }}%</p>
                            @php
                                $progressWidth = min(abs($profitMargin ?? 0), 100);
                            @endphp
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-{{ ($profitMargin ?? 0) >= 0 ? 'success' : 'danger' }}"
                                    style="width: {{ $progressWidth }}%; max-width: 100%">
                                    {{ $profitMargin ?? 0 }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-graph-up"></i> Performa</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $productivityRate = $productivityRate ?? 0;
                            $growthRate = $growthRate ?? 0;
                            $retentionRate = $retentionRate ?? 0;
                        @endphp

                        <p><strong>Produktivitas:</strong> {{ $productivityRate }}%</p>
                        <div class="progress mb-2" style="height: 15px;">
                            <div class="progress-bar bg-{{ $productivityRate >= 80 ? 'success' : ($productivityRate >= 60 ? 'warning' : 'danger') }}"
                                style="width: {{ min($productivityRate, 100) }}%"></div>
                        </div>

                        <p><strong>Pertumbuhan:</strong> {{ $growthRate }}%</p>
                        <div class="progress mb-2" style="height: 15px;">
                            @php
                                $growthProgress = min(abs($growthRate), 100);
                            @endphp
                            <div class="progress-bar bg-{{ $growthRate >= 10 ? 'success' : ($growthRate >= 0 ? 'warning' : 'danger') }}"
                                style="width: {{ $growthProgress }}%"></div>
                        </div>

                        <p><strong>Retensi Karyawan:</strong> {{ $retentionRate }}%</p>
                        <div class="progress mb-2" style="height: 15px;">
                            <div class="progress-bar bg-{{ $retentionRate >= 90 ? 'success' : ($retentionRate >= 80 ? 'warning' : 'danger') }}"
                                style="width: {{ min($retentionRate, 100) }}%"></div>
                        </div>

                        <p><strong>Karyawan Aktif:</strong> {{ $karyawanAktif ?? 0 }} dari {{ $karyawanCount ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="row">
            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-people"></i> SDM</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Total Karyawan:</strong> {{ $karyawanCount ?? 0 }}</p>
                        <p><strong>Aktif:</strong> {{ $karyawanAktif ?? 0 }}</p>
                        <p><strong>Nonaktif:</strong> {{ ($karyawanCount ?? 0) - ($karyawanAktif ?? 0) }}</p>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-outline-info mt-2">
                            Lihat Semua Karyawan
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Absensi</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Hari Ini:</strong> {{ $absensiToday ?? 0 }}</p>
                        <p><strong>Bulan Ini:</strong> {{ ($absensiToday ?? 0) * now()->day }}</p>
                        @php
                            $attendanceRate =
                                ($karyawanAktif ?? 0) > 0
                                    ? round((($absensiToday ?? 0) / ($karyawanAktif ?? 0)) * 100, 1)
                                    : 0;
                        @endphp
                        <p><strong>Rate Kehadiran:</strong> {{ $attendanceRate }}%</p>
                        <!-- PERBAIKAN DI SINI: ganti href="#" dengan route('absensi.index') -->
                        <a href="{{ route('absensi.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                            Lihat Absensi
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card">
                    <div class="card-header bg-dark text-white">
                        <h6 class="mb-0"><i class="bi bi-clipboard-data"></i> Ringkasan</h6>
                    </div>
                    <div class="card-body">
                        <p><i class="bi bi-check-circle text-success"></i> Karyawan:
                            {{ $karyawanAktif ?? 0 }}/{{ $karyawanCount ?? 0 }}</p>
                        <p><i class="bi bi-cash-coin {{ ($profitMargin ?? 0) >= 0 ? 'text-success' : 'text-danger' }}"></i>
                            Profit: {{ $profitMargin ?? 0 }}%</p>
                        <p><i class="bi bi-graph-up text-info"></i> Pertumbuhan: {{ $growthRate ?? 0 }}%</p>
                        <p><i class="bi bi-activity text-warning"></i> Produktivitas: {{ $productivityRate ?? 0 }}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection