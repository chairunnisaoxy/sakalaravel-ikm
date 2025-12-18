<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk</title>

    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        .badge {
            font-size: 0.85em;
            padding: 0.4em 0.8em;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        .action-buttons .btn {
            margin-right: 5px;
        }

        .card {
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-action {
            width: 35px;
            height: 35px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-box"></i> Sistem Manajemen Produk
            </a>
            <div class="navbar-nav ml-auto">
                <a class="nav-link text-white" href="{{ route('dashboard') }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">
                    <span>&times;</span>
                </button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-box text-primary"></i> Daftar Produk
                        </h4>
                        <a href="{{ route('produk.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                    </div>
                    <div class="card-body">
                        @if ($produk->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="produkTable">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>ID PRODUK</th>
                                            <th>NAMA PRODUK</th>
                                            <th>SATUAN</th>
                                            <th>STATUS</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($produk as $item)
                                            <tr>
                                                <td><strong>{{ $item->id_produk }}</strong></td>
                                                <td>{{ $item->nama_produk }}</td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ strtoupper($item->satuan) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge badge-{{ $item->status_produk == 'aktif' ? 'success' : 'danger' }}">
                                                        {{ strtoupper($item->status_produk) }}
                                                    </span>
                                                </td>
                                                <td class="action-buttons">
                                                    <a href="{{ route('produk.edit', $item->id_produk) }}"
                                                        class="btn btn-warning btn-action" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('produk.destroy', $item->id_produk) }}"
                                                        method="POST" class="d-inline delete-form"
                                                        data-nama="{{ $item->nama_produk }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-danger btn-action delete-btn"
                                                            title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                                <h4 class="text-muted">Belum ada data produk</h4>
                                <p class="text-muted">Mulai dengan menambahkan produk baru</p>
                                <a href="{{ route('produk.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Produk Pertama
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable jika ada data
            if ($('#produkTable').length) {
                $('#produkTable').DataTable({
                    "pageLength": 10,
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
                    }
                });
            }

            // SweetAlert untuk konfirmasi hapus
            $('.delete-btn').click(function(e) {
                e.preventDefault();
                const form = $(this).closest('form');
                const nama = form.data('nama');

                Swal.fire({
                    title: 'Hapus Produk?',
                    html: `Apakah Anda yakin ingin menghapus produk <strong>${nama}</strong>?<br><small>Data yang dihapus tidak dapat dikembalikan.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form
                        form.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>