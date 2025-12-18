<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Absensi Baru</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .card {
            margin-top: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .required:after {
            content: " *";
            color: red;
        }

        .input-group-text {
            background-color: #f8f9fa;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="<?php echo e(route('absensi.index')); ?>">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text text-white">
                    Form Tambah Absensi
                </span>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card form-container">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-calendar-plus"></i> Tambah Absensi Baru
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:</h5>
                                <ul class="mb-0">
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('absensi.store')); ?>" method="POST" id="absensiForm">
                            <?php echo csrf_field(); ?>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_absensi" class="required">ID Absensi</label>
                                        <input type="text"
                                            class="form-control <?php echo e($errors->has('id_absensi') ? 'is-invalid' : ''); ?>"
                                            id="id_absensi" name="id_absensi"
                                            value="<?php echo e(old('id_absensi', 'ABS-' . date('Ymd-His'))); ?>"
                                            required>
                                        <?php if($errors->has('id_absensi')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('id_absensi')); ?></div>
                                        <?php endif; ?>
                                        <small class="form-text text-muted">Format: ABS-YYYYMMDD-HHMMSS</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="required">Tanggal</label>
                                        <input type="date"
                                            class="form-control <?php echo e($errors->has('tanggal') ? 'is-invalid' : ''); ?>"
                                            id="tanggal" name="tanggal"
                                            value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>"
                                            max="<?php echo e(date('Y-m-d')); ?>" required>
                                        <?php if($errors->has('tanggal')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('tanggal')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_karyawan" class="required">Karyawan</label>
                                        <select class="form-control <?php echo e($errors->has('id_karyawan') ? 'is-invalid' : ''); ?>"
                                            id="id_karyawan" name="id_karyawan" required>
                                            <option value="">Pilih Karyawan</option>
                                            <?php $__currentLoopData = $karyawanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($karyawan->id_karyawan); ?>"
                                                    <?php echo e(old('id_karyawan') == $karyawan->id_karyawan ? 'selected' : ''); ?>>
                                                    <?php echo e($karyawan->nama_karyawan); ?> (<?php echo e($karyawan->jabatan); ?>)
                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php if($errors->has('id_karyawan')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('id_karyawan')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_absensi" class="required">Status Absensi</label>
                                        <select class="form-control <?php echo e($errors->has('status_absensi') ? 'is-invalid' : ''); ?>"
                                            id="status_absensi" name="status_absensi" required>
                                            <option value="">Pilih Status</option>
                                            <option value="hadir" <?php echo e(old('status_absensi') == 'hadir' ? 'selected' : ''); ?>>Hadir</option>
                                            <option value="tidak hadir" <?php echo e(old('status_absensi') == 'tidak hadir' ? 'selected' : ''); ?>>Tidak Hadir</option>
                                            <option value="cuti" <?php echo e(old('status_absensi') == 'cuti' ? 'selected' : ''); ?>>Cuti</option>
                                        </select>
                                        <?php if($errors->has('status_absensi')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('status_absensi')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_masuk">Jam Masuk</label>
                                        <input type="time"
                                            class="form-control <?php echo e($errors->has('jam_masuk') ? 'is-invalid' : ''); ?>"
                                            id="jam_masuk" name="jam_masuk"
                                            value="<?php echo e(old('jam_masuk', '08:00')); ?>">
                                        <?php if($errors->has('jam_masuk')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('jam_masuk')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_keluar">Jam Keluar</label>
                                        <input type="time"
                                            class="form-control <?php echo e($errors->has('jam_keluar') ? 'is-invalid' : ''); ?>"
                                            id="jam_keluar" name="jam_keluar"
                                            value="<?php echo e(old('jam_keluar', '17:00')); ?>">
                                        <?php if($errors->has('jam_keluar')): ?>
                                            <div class="invalid-feedback"><?php echo e($errors->first('jam_keluar')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="total_gaji" class="required">Total Gaji</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control <?php echo e($errors->has('total_gaji') ? 'is-invalid' : ''); ?>"
                                                id="total_gaji" name="total_gaji"
                                                value="<?php echo e(old('total_gaji', 100000)); ?>" min="0" step="1000" required>
                                            <?php if($errors->has('total_gaji')): ?>
                                                <div class="invalid-feedback"><?php echo e($errors->first('total_gaji')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="bonus_lembur">Bonus Lembur</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control <?php echo e($errors->has('bonus_lembur') ? 'is-invalid' : ''); ?>"
                                                id="bonus_lembur" name="bonus_lembur"
                                                value="<?php echo e(old('bonus_lembur', 0)); ?>" min="0" step="1000">
                                            <?php if($errors->has('bonus_lembur')): ?>
                                                <div class="invalid-feedback"><?php echo e($errors->first('bonus_lembur')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="potongan">Potongan</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control <?php echo e($errors->has('potongan') ? 'is-invalid' : ''); ?>"
                                                id="potongan" name="potongan"
                                                value="<?php echo e(old('potongan', 0)); ?>" min="0" step="1000">
                                            <?php if($errors->has('potongan')): ?>
                                                <div class="invalid-feedback"><?php echo e($errors->first('potongan')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="total_aktual">Total Aktual</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control <?php echo e($errors->has('total_aktual') ? 'is-invalid' : ''); ?>"
                                                id="total_aktual" name="total_aktual"
                                                value="<?php echo e(old('total_aktual', 100000)); ?>" readonly
                                                style="background-color: #f8f9fa;">
                                            <?php if($errors->has('total_aktual')): ?>
                                                <div class="invalid-feedback"><?php echo e($errors->first('total_aktual')); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <small class="form-text text-muted">Akan dihitung otomatis: (Total Gaji + Bonus - Potongan)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                    <i class="fas fa-save"></i> Simpan Absensi
                                </button>
                                <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-secondary btn-lg">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Auto calculate total aktual
            function calculateTotal() {
                let totalGaji = parseFloat($('#total_gaji').val()) || 0;
                let bonus = parseFloat($('#bonus_lembur').val()) || 0;
                let potongan = parseFloat($('#potongan').val()) || 0;
                
                let totalAktual = totalGaji + bonus - potongan;
                $('#total_aktual').val(totalAktual);
            }
            
            // Calculate when input changes
            $('#total_gaji, #bonus_lembur, #potongan').on('input', calculateTotal);
            
            // Initial calculation
            calculateTotal();
            
            // Form submission handler
            $('#absensiForm').submit(function(e) {
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });

            // Confirm before leaving page if form has changes
            let formChanged = false;
            $('#absensiForm input, #absensiForm select').on('change input', function() {
                formChanged = true;
            });

            $('a.btn-secondary').click(function(e) {
                if (formChanged) {
                    if (!confirm('Anda memiliki perubahan yang belum disimpan. Yakin ingin keluar?')) {
                        e.preventDefault();
                    }
                }
            });
        });
    </script>
</body>

</html>