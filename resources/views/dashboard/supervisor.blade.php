@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3><i class="bi bi-person-gear"></i> Dashboard Supervisor</h3>
            <p class="text-muted">Monitor operasional harian</p>
        </div>
        <div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Ringkas -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 mb-0">{{ $hadirToday }}</div>
                    <small class="text-muted">Hadir Hari Ini</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 mb-0">{{ $terlambatToday }}</div>
                    <small class="text-muted">Terlambat</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 mb-0">{{ $karyawanAktif }}</div>
                    <small class="text-muted">Karyawan Aktif</small>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-6 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <div class="h2 mb-0">{{ $produksiToday }}</div>
                    <small class="text-muted">Produksi Hari Ini</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Operator & Aksi Cepat -->
    <div class="row mb-4">

        <!-- Daftar Operator -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="bi bi-people"></i> Daftar Operator</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Target</th>
                                    <th>Produksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operatorPerformance as $operator)
                                    @php
                                        $persentase = ($operator->total_produksi / $operator->jml_target) * 100;
                                    @endphp
                                    <tr>
                                        <td>{{ $operator->nama_karyawan }}</td>
                                        <td>{{ $operator->jml_target }}</td>
                                        <td>{{ $operator->total_produksi }}</td>
                                        <td>
                                            <span class="badge bg-{{ $persentase >= 100 ? 'success' : ($persentase >= 80 ? 'warning' : 'danger') }}">
                                                {{ round($persentase) }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0"><i class="bi bi-lightning"></i> Aksi Cepat</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">

                        <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Tambah Karyawan
                        </a>

                        <a href="#" class="btn btn-success">
                            <i class="bi bi-clock"></i> Input Absensi
                        </a>

                        <a href="{{ route('karyawan.index') }}" class="btn btn-info">
                            <i class="bi bi-list-ul"></i> Lihat Semua Karyawan
                        </a>

                        {{-- 🔥 FITUR BARU: KELOLA PRODUK --}}
                        <a href="{{ route('produk.index') }}" class="btn btn-warning">
                            <i class="bi bi-box-seam"></i> Kelola Produk
                        </a>

                    </div>
                </div>
            </div>

            <!-- Status Absensi -->
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-calendar-check"></i> Status Absensi</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Absensi Pagi:</strong>
                        <span class="badge bg-{{ $absensiPagiDone ? 'success' : 'warning' }}">
                            {{ $absensiPagiDone ? 'Sudah' : 'Belum' }}
                        </span>
                    </p>
                    <p class="mb-0">
                        <strong>Total Absensi Hari Ini:</strong> {{ $absensiToday }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-info-circle"></i> Ringkasan</h6>
                </div>
                <div class="card-body">
                    <p>Total karyawan: <strong>{{ $karyawanCount }}</strong> orang</p>
                    <p>Karyawan aktif: <strong>{{ $karyawanAktif }}</strong> orang</p>
                    <p>Total produksi hari ini: <strong>{{ $produksiToday }}</strong> unit</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
