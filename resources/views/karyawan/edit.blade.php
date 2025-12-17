<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>

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
            max-width: 800px;
            margin: 0 auto;
        }

        .required:after {
            content: " *";
            color: red;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="{{ route('karyawan.index') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <div class="navbar-nav ml-auto">
                <span class="navbar-text text-white">
                    Edit Data Karyawan
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
                            <i class="fas fa-edit"></i> Edit Data Karyawan
                            <small class="float-right">ID: {{ $karyawan->id_karyawan }}</small>
                        </h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle"></i> Terjadi kesalahan:</h5>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('karyawan.update', $karyawan->id_karyawan) }}" method="POST"
                            id="editForm">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nama_karyawan" class="required">Nama Karyawan</label>
                                        <input type="text"
                                            class="form-control @error('nama_karyawan') is-invalid @enderror"
                                            id="nama_karyawan" name="nama_karyawan"
                                            value="{{ old('nama_karyawan', $karyawan->nama_karyawan) }}" required>
                                        @error('nama_karyawan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jabatan" class="required">Jabatan</label>
                                        <select class="form-control @error('jabatan') is-invalid @enderror"
                                            id="jabatan" name="jabatan" required>
                                            <option value="">Pilih Jabatan</option>
                                            <option value="operator"
                                                {{ old('jabatan', $karyawan->jabatan) == 'operator' ? 'selected' : '' }}>
                                                Operator</option>
                                            <option value="supervisor"
                                                {{ old('jabatan', $karyawan->jabatan) == 'supervisor' ? 'selected' : '' }}>
                                                Supervisor</option>
                                            <option value="pemilik"
                                                {{ old('jabatan', $karyawan->jabatan) == 'pemilik' ? 'selected' : '' }}>
                                                Pemilik</option>
                                        </select>
                                        @error('jabatan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email', $karyawan->email) }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="no_telp">No. Telepon</label>
                                        <input type="text"
                                            class="form-control @error('no_telp') is-invalid @enderror" id="no_telp"
                                            name="no_telp" value="{{ old('no_telp', $karyawan->no_telp) }}">
                                        @error('no_telp')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password"
                                            class="form-control @error('password') is-invalid @enderror" id="password"
                                            name="password">
                                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah
                                            password</small>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="gaji_harian" class="required">Gaji Harian</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rp</span>
                                            </div>
                                            <input type="number"
                                                class="form-control @error('gaji_harian') is-invalid @enderror"
                                                id="gaji_harian" name="gaji_harian"
                                                value="{{ old('gaji_harian', $karyawan->gaji_harian) }}" min="0"
                                                required>
                                            @error('gaji_harian')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="jml_target" class="required">Target Harian</label>
                                        <input type="number"
                                            class="form-control @error('jml_target') is-invalid @enderror"
                                            id="jml_target" name="jml_target"
                                            value="{{ old('jml_target', $karyawan->jml_target) }}" min="0"
                                            required>
                                        @error('jml_target')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status_karyawan" class="required">Status</label>
                                        <select class="form-control @error('status_karyawan') is-invalid @enderror"
                                            id="status_karyawan" name="status_karyawan" required>
                                            <option value="aktif"
                                                {{ old('status_karyawan', $karyawan->status_karyawan) == 'aktif' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="nonaktif"
                                                {{ old('status_karyawan', $karyawan->status_karyawan) == 'nonaktif' ? 'selected' : '' }}>
                                                Nonaktif</option>
                                        </select>
                                        @error('status_karyawan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                @error('alamat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group text-center mt-4">
                                <button type="submit" class="btn btn-warning btn-lg" id="updateBtn">
                                    <i class="fas fa-save"></i> Update Data
                                </button>
                                <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-lg">
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
            // Form submission handler
            $('#editForm').submit(function(e) {
                // Disable submit button
                $('#updateBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });

            // Confirm before leaving page if form has changes
            let formChanged = false;
            const initialValues = {};

            // Store initial form values
            $('#editForm input, #editForm select, #editForm textarea').each(function() {
                const id = $(this).attr('id');
                if (id) {
                    if ($(this).is('select')) {
                        initialValues[id] = $(this).val();
                    } else {
                        initialValues[id] = $(this).val();
                    }
                }
            });

            // Check for changes
            $('#editForm input, #editForm select, #editForm textarea').on('change input', function() {
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
