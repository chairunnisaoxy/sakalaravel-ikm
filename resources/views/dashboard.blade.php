@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <h4 class="mb-0 text-dark">
                        <i class="fas fa-box"></i> Daftar Produk
                    </h4>
                    <a href="{{ route('produk.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Produk
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if($produk->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                            <h5>Belum ada data produk</h5>
                            <p class="text-muted">Silakan tambahkan produk pertama Anda</p>
                            <a href="{{ route('produk.create') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-plus"></i> Tambah Produk
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID PRODUK</th>
                                        <th>NAMA PRODUK</th>
                                        <th>SATUAN</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($produk as $item)
                                    <tr>
                                        <td><strong>{{ $item->id_produk }}</strong></td>
                                        <td>{{ $item->nama_produk }}</td>
                                        <td class="text-uppercase">{{ $item->satuan }}</td>
                                        <td>
                                            @if($item->status_produk == 'aktif')
                                                <span class="badge bg-success">AKTIF</span>
                                            @else
                                                <span class="badge bg-danger">NONAKTIF</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('produk.edit', $item->id_produk) }}" 
                                                   class="btn btn-warning btn-sm btn-action" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('produk.destroy', $item->id_produk) }}" 
                                                      method="POST" class="delete-form"
                                                      data-nama="{{ $item->nama_produk }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            class="btn btn-danger btn-sm btn-action delete-btn" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Tambahkan keterangan footer seperti di contoh -->
                        <div class="mt-4 p-3 border-top">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> 
                                Total {{ $produk->count() }} produk ditemukan
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 Script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // SweetAlert untuk konfirmasi hapus
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const nama = form.getAttribute('data-nama');
            
            Swal.fire({
                title: 'Hapus Produk?',
                html: `Apakah Anda yakin ingin menghapus produk <strong>${nama}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Mohon tunggu',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    form.submit();
                }
            });
        });
    });
});
</script>

<style>
.btn-action {
    width: 35px;
    height: 35px;
    padding: 0;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.card-header.bg-light {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
}

.table th {
    font-weight: 600;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
}

.badge {
    font-size: 0.85em;
    padding: 0.4em 0.8em;
}

.gap-2 {
    gap: 0.5rem;
}

/* Style sesuai tampilan Anda */
.table-bordered {
    border: 1px solid #dee2e6;
}

.table-bordered th,
.table-bordered td {
    border: 1px solid #dee2e6;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,0,0,.02);
}
</style>
@endsection