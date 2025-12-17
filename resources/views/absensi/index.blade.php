<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Absensi</title>
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        .badge { font-size: 0.85em; padding: 0.4em 0.8em; }
        .table th, .table td { vertical-align: middle; }
        .action-buttons .btn { margin-right: 5px; }
        .card { margin-top: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .btn-action { width: 35px; height: 35px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }
        .filter-container { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .rupiah { font-family: 'Courier New', monospace; font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-calendar-check"></i> Sistem Absensi Karyawan
            </a>
            <div class="navbar-nav ml-auto">
                <a class="nav-link text-white" href="<?php echo e(route('dashboard')); ?>">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a class="nav-link text-white" href="<?php echo e(route('karyawan.index')); ?>">
                    <i class="fas fa-users"></i> Karyawan
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-clipboard-list text-primary"></i> Daftar Absensi
                        </h4>
                        <a href="<?php echo e(route('absensi.create')); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Absensi
                        </a>
                    </div>
                    
                    <div class="card-body">
                        <!-- Filter Section -->
                        <div class="filter-container">
                            <form method="GET" action="" class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Filter Tanggal</label>
                                        <input type="date" class="form-control" name="filter_tanggal" value="<?php echo e(request('filter_tanggal')); ?>">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Filter Karyawan</label>
                                        <select class="form-control" name="filter_karyawan">
                                            <option value="">Semua Karyawan</option>
                                            <?php $__currentLoopData = $karyawanList ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($karyawan->id_karyawan); ?>"
                                                    <?php echo e(request('filter_karyawan') == $karyawan->id_karyawan ? 'selected' : ''); ?>>
                                                    <?php echo e($karyawan->nama_karyawan); ?>
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Filter Status</label>
                                        <select class="form-control" name="filter_status">
                                            <option value="">Semua Status</option>
                                            <option value="hadir" <?php echo e(request('filter_status') == 'hadir' ? 'selected' : ''); ?>>Hadir</option>
                                            <option value="tidak hadir" <?php echo e(request('filter_status') == 'tidak hadir' ? 'selected' : ''); ?>>Tidak Hadir</option>
                                            <option value="cuti" <?php echo e(request('filter_status') == 'cuti' ? 'selected' : ''); ?>>Cuti</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-info btn-block">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>
                            </form>
                        </div>

                        <?php if($absensi->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="absensiTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID Absensi</th>
                                            <th>Tanggal</th>
                                            <th>Karyawan</th>
                                            <th>Jam Masuk</th>
                                            <th>Jam Keluar</th>
                                            <th>Total Gaji</th>
                                            <th>Bonus</th>
                                            <th>Potongan</th>
                                            <th>Total Aktual</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><strong><?php echo e($absen->id_absensi); ?></strong></td>
                                                <td><?php echo e(\Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y')); ?></td>
                                                <td>
                                                    <strong><?php echo e($absen->karyawan->nama_karyawan ?? 'Tidak Diketahui'); ?></strong>
                                                    <br>
                                                    <small class="text-muted"><?php echo e($absen->karyawan->jabatan ?? '-'); ?></small>
                                                </td>
                                                <td>
                                                    <?php if($absen->jam_masuk): ?>
                                                        <span class="badge badge-success"><?php echo e($absen->jam_masuk); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if($absen->jam_keluar): ?>
                                                        <span class="badge badge-info"><?php echo e($absen->jam_keluar); ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="rupiah">Rp <?php echo e(number_format($absen->total_gaji, 0, ',', '.')); ?></td>
                                                <td class="rupiah text-success">
                                                    <?php if($absen->bonus_lembur > 0): ?>
                                                        + Rp <?php echo e(number_format($absen->bonus_lembur, 0, ',', '.')); ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="rupiah text-danger">
                                                    <?php if($absen->potongan > 0): ?>
                                                        - Rp <?php echo e(number_format($absen->potongan, 0, ',', '.')); ?>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="rupiah text-primary font-weight-bold">
                                                    Rp <?php echo e(number_format($absen->total_aktual, 0, ',', '.')); ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $statusColors = [
                                                            'hadir' => 'success',
                                                            'tidak hadir' => 'danger',
                                                            'cuti' => 'warning'
                                                        ];
                                                        $color = $statusColors[$absen->status_absensi] ?? 'secondary';
                                                    ?>
                                                    <span class="badge badge-<?php echo e($color); ?>">
                                                        <?php echo e(strtoupper($absen->status_absensi)); ?>
                                                    </span>
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="<?php echo e(route('absensi.edit', $absen->id_absensi)); ?>" 
                                                       class="btn btn-warning btn-action" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="<?php echo e(route('absensi.destroy', $absen->id_absensi)); ?>" 
                                                          method="POST" class="d-inline delete-form"
                                                          data-id="<?php echo e($absen->id_absensi); ?>"
                                                          data-tanggal="<?php echo e(\Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y')); ?>">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-danger btn-action" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-5">
                                <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum ada data absensi</h4>
                                <p class="text-muted">Mulai dengan menambahkan data absensi baru</p>
                                <a href="<?php echo e(route('absensi.create')); ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Absensi Pertama
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            if ($('#absensiTable').length) {
                $('#absensiTable').DataTable({
                    "pageLength": 10,
                    "order": [[1, 'desc']],
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            }

            // SweetAlert untuk hapus
            $('.delete-form').submit(function(e) {
                e.preventDefault();
                const form = $(this);
                const id = form.data('id');
                const tanggal = form.data('tanggal');

                Swal.fire({
                    title: 'Hapus Absensi?',
                    html: `Hapus absensi ID: <strong>${id}</strong><br>Tanggal: ${tanggal}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.unbind('submit').submit();
                    }
                });
            });
        });
    </script>
</body>
</html>