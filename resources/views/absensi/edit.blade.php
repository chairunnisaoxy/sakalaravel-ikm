<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Absensi</title>

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
            <a class="navbar-brand" href="{{ route('absensi.index') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text text-white">
                    Edit Data Absensi
                </span>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card form-container">
                    <div class="card-header bg-warning text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Data Absensi
                            <small class="float-right">ID: {{ $absensi->id_absensi }}</small>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:</h5>
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('absensi.update', $absensi->id_absensi) }}" method="POST" id="editForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_absensi" class="required">ID Absensi</label>
                                        <input type="text" 
                                            class="form-control {{ $errors->has('id_absensi') ? 'is-invalid' : '' }}" 
                                            id="id_absensi" 
                                            value="{{ old('id_absensi', $absensi->id_absensi) }}" 
                                            readonly style="background-color: #f8f9fa;">
                                        @if($errors->has('id_absensi'))
                                            <div class="invalid-feedback">{{ $errors->first('id_absensi') }}</div>
                                        @endif
                                        <small class="form-text text-muted">ID tidak dapat diubah</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal" class="required">Tanggal</label>
                                        <input type="date" 
                                            class="form-control {{ $errors->has('tanggal') ? 'is-invalid' : '' }}" 
                                            id="tanggal" name="tanggal" 
                                            value="{{ old('tanggal', $absensi->tanggal) }}" 
                                            max="{{ date('Y-m-d') }}" required>
                                        @if($errors->has('tanggal'))
                                            <div class="invalid-feedback">{{ $errors->first('tanggal') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_karyawan" class="required">Karyawan</label>
                                        <select class="form-control {{ $errors->has('id_karyawan') ? 'is-invalid' : '' }}" 
                                            id="id_karyawan" name="id_karyawan" required>
                                            <option value="">Pilih Karyawan</option>
                                            @foreach($karyawanList as $karyawan)
                                                <option value="{{ $karyawan->id_karyawan }}" 
                                                    {{ old('id_karyawan', $absensi->id_karyawan) == $karyawan->id_karyawan ? 'selected' : '' }}>
                                                    {{ $karyawan->nama_karyawan }} ({{ $karyawan->jabatan }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @if($errors->has('id_karyawan'))
                                            <div class="invalid-feedback">{{ $errors->first('id_karyawan') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_absensi" class="required">Status Absensi</label>
                                        <select class="form-control {{ $errors->has('status_absensi') ? 'is-invalid' : '' }}" 
                                            id="status_absensi" name="status_absensi" required>
                                            <option value="">Pilih Status</option>
                                            <option value="hadir" {{ old('status_absensi', $absensi->status_absensi) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                            <option value="tidak hadir" {{ old('status_absensi', $absensi->status_absensi) == 'tidak hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                                            <option value="cuti" {{ old('status_absensi', $absensi->status_absensi) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                        </select>
                                        @if($errors->has('status_absensi'))
                                            <div class="invalid-feedback">{{ $errors->first('status_absensi') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_masuk">Jam Masuk</label>
                                        <input type="time" 
                                            class="form-control {{ $errors->has('jam_masuk') ? 'is-invalid' : '' }}" 
                                            id="jam_masuk" name="jam_masuk" 
                                            value="{{ old('jam_masuk', isset($absensi->jam_masuk_formatted) ? $absensi->jam_masuk_formatted : substr($absensi->jam_masuk, 0, 5)) }}">
                                        @if($errors->has('jam_masuk'))
                                            <div class="invalid-feedback">{{ $errors->first('jam_masuk') }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jam_keluar">Jam Keluar</label>
                                        <input type="time" 
                                            class="form-control {{ $errors->has('jam_keluar') ? 'is-invalid' : '' }}" 
                                            id="jam_keluar" name="jam_keluar" 
                                            value="{{ old('jam_keluar', isset($absensi->jam_keluar_formatted) ? $absensi->jam_keluar_formatted : substr($absensi->jam_keluar, 0, 5)) }}">
                                        @if($errors->has('jam_keluar'))
                                            <div class="invalid-feedback">{{ $errors->first('jam_keluar') }}</div>
                                        @endif
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
                                                class="form-control {{ $errors->has('total_gaji') ? 'is-invalid' : '' }}" 
                                                id="total_gaji" name="total_gaji" 
                                                value="{{ old('total_gaji', $absensi->total_gaji) }}" 
                                                min="0" step="1000" required>
                                            @if($errors->has('total_gaji'))
                                                <div class="invalid-feedback">{{ $errors->first('total_gaji') }}</div>
                                            @endif
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
                                                class="form-control {{ $errors->has('bonus_lembur') ? 'is-invalid' : '' }}" 
                                                id="bonus_lembur" name="bonus_lembur" 
                                                value="{{ old('bonus_lembur', $absensi->bonus_lembur) }}" 
                                                min="0" step="1000">
                                            @if($errors->has('bonus_lembur'))
                                                <div class="invalid-feedback">{{ $errors->first('bonus_lembur') }}</div>
                                            @endif
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
                                                class="form-control {{ $errors->has('potongan') ? 'is-invalid' : '' }}" 
                                                id="potongan" name="potongan" 
                                                value="{{ old('potongan', $absensi->potongan) }}" 
                                                min="0" step="1000">
                                            @if($errors->has('potongan'))
                                                <div class="invalid-feedback">{{ $errors->first('potongan') }}</div>
                                            @endif
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
                                                class="form-control {{ $errors->has('total_aktual') ? 'is-invalid' : '' }}" 
                                                id="total_aktual" name="total_aktual" 
                                                value="{{ old('total_aktual', $absensi->total_aktual) }}" 
                                                readonly style="background-color: #f8f9fa;">
                                            @if($errors->has('total_aktual'))
                                                <div class="invalid-feedback">{{ $errors->first('total_aktual') }}</div>
                                            @endif
                                        </div>
                                        <small class="form-text text-muted">Akan dihitung otomatis: (Total Gaji + Bonus - Potongan)</small>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-warning btn-lg" id="updateBtn">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                                <a href="{{ route('absensi.index') }}" class="btn btn-secondary btn-lg">
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
            $('#editForm').submit(function(e) {
                $('#updateBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });

            // Confirm before leaving page if form has changes
            let formChanged = false;
            const initialValues = {};

            // Store initial form values
            $('#editForm input, #editForm select').each(function() {
                const id = $(this).attr('id');
                if (id) {
                    initialValues[id] = $(this).is('select') ? $(this).val() : $(this).val();
                }
            });

            // Check for changes
            $('#editForm input, #editForm select').on('change input', function() {
                const id = $(this).attr('id');
                if (id) {
                    const currentValue = $(this).is('select') ? $(this).val() : $(this).val();
                    formChanged = (currentValue !== initialValues[id]);
                }
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