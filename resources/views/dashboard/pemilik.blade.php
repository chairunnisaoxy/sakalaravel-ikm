@extends('layouts.app')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h4 class="mb-0">
                <i class="bi bi-person-fill-gear"></i> Dashboard Pemilik
            </h4>
            <small class="text-muted">Overview bisnis dan keuangan</small>
        </div>

        <div class="col-md-4 text-end">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    {{-- STAT CARD ATAS --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</h5>
                    <small class="text-muted">Total Pengeluaran</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>Rp {{ number_format($estimasiPendapatan ?? 0, 0, ',', '.') }}</h5>
                    <small class="text-muted">Estimasi Pendapatan</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="{{ ($profitMargin ?? 0) >= 0 ? 'text-success' : 'text-danger' }}">
                        {{ $profitMargin ?? 0 }}%
                    </h5>
                    <small class="text-muted">Profit Margin</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5>{{ $karyawanCount ?? 0 }}</h5>
                    <small class="text-muted">Total Karyawan</small>
                </div>
            </div>
        </div>
    </div>

    {{-- KEUANGAN & PERFORMA --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-warning">
                    <strong><i class="bi bi-cash-stack"></i> Keuangan</strong>
                </div>
                <div class="card-body">
                    <p>Pengeluaran Bulan Ini: Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}</p>
                    <p>Biaya Gaji: Rp {{ number_format($totalPengeluaranGaji ?? 0, 0, ',', '.') }}</p>
                    <p>Biaya Operasional: Rp {{ number_format($biayaOperasional ?? 0, 0, ',', '.') }}</p>
                    <p>Estimasi Pendapatan: Rp {{ number_format($estimasiPendapatan ?? 0, 0, ',', '.') }}</p>

                    <p class="mt-3 mb-1">Profit Margin: {{ $profitMargin ?? 0 }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ min(abs($profitMargin ?? 0),100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong><i class="bi bi-graph-up"></i> Performa</strong>
                </div>
                <div class="card-body">
                    <p>Produktivitas: {{ $productivityRate ?? 0 }}%</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-info" style="width: {{ $productivityRate ?? 0 }}%"></div>
                    </div>

                    <p>Pertumbuhan: {{ $growthRate ?? 0 }}%</p>
                    <div class="progress mb-2">
                        <div class="progress-bar bg-success" style="width: {{ $growthRate ?? 0 }}%"></div>
                    </div>

                    <p>Retensi Karyawan: {{ $retentionRate ?? 0 }}%</p>
                    <div class="progress">
                        <div class="progress-bar bg-success" style="width: {{ $retentionRate ?? 0 }}%"></div>
                    </div>

                    <p class="mt-2">Karyawan Aktif: {{ $karyawanAktif ?? 0 }} dari {{ $karyawanCount ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- QUICK INFO (SDM | ABSENSI | PRODUK | RINGKASAN) --}}
    <div class="row">

        {{-- SDM --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <strong><i class="bi bi-people"></i> SDM</strong>
                </div>
                <div class="card-body">
                    <p>Total: {{ $karyawanCount ?? 0 }}</p>
                    <p>Aktif: {{ $karyawanAktif ?? 0 }}</p>
                    <p>Nonaktif: {{ ($karyawanCount ?? 0) - ($karyawanAktif ?? 0) }}</p>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-outline-info">
                        Lihat Semua
                    </a>
                </div>
            </div>
        </div>

        {{-- ABSENSI --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong><i class="bi bi-calendar-check"></i> Absensi</strong>
                </div>
                <div class="card-body">
                    <p>Hari Ini: {{ $absensiToday ?? 0 }}</p>
                    <p>Bulan Ini: {{ ($absensiToday ?? 0) * now()->day }}</p>
                    <p>Rate: {{ $attendanceRate ?? 0 }}%</p>
                    <a href="#" class="btn btn-sm btn-outline-primary">
                        Lihat Absensi
                    </a>
                </div>
            </div>
        </div>

        {{-- PRODUK (BARU) --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong><i class="bi bi-box"></i> Produk</strong>
                </div>
                <div class="card-body">
                    <p>Total Produk: {{ $produkCount ?? 0 }}</p>
                    <p class="text-success">
                        Aktif: {{ $produkAktif ?? 0 }}
                    </p>
                    <a href="{{ route('produk.index') }}" class="btn btn-sm btn-outline-success">
                        Lihat Produk
                    </a>
                </div>
            </div>
        </div>

        {{-- RINGKASAN --}}
        <div class="col-md-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <strong><i class="bi bi-clipboard-data"></i> Ringkasan</strong>
                </div>
                <div class="card-body">
                    <p>✔ Karyawan: {{ $karyawanAktif ?? 0 }}/{{ $karyawanCount ?? 0 }}</p>
                    <p>💰 Profit: {{ $profitMargin ?? 0 }}%</p>
                    <p>📈 Pertumbuhan: {{ $growthRate ?? 0 }}%</p>
                    <p>⚙ Produktivitas: {{ $productivityRate ?? 0 }}%</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
