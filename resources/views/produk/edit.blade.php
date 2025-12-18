@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Produk: {{ $produk->id_produk }}</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('produk.update', $produk->id_produk) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">
                                <strong>ID PRODUK</strong>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   value="{{ $produk->id_produk }}" 
                                   readonly>
                            <div class="form-text">ID Produk tidak dapat diubah</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">
                                <strong>NAMA PRODUK</strong>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_produk') is-invalid @enderror" 
                                   id="nama_produk" 
                                   name="nama_produk" 
                                   value="{{ old('nama_produk', $produk->nama_produk) }}" 
                                   required>
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Satuan dan Status sama seperti create -->
                        <div class="mb-3">
                            <label for="satuan" class="form-label">
                                <strong>SATUAN</strong>
                            </label>
                            <select class="form-control @error('satuan') is-invalid @enderror" 
                                    id="satuan" 
                                    name="satuan">
                                <option value="pcs" {{ old('satuan', $produk->satuan) == 'pcs' ? 'selected' : '' }}>PCS</option>
                                <option value="set" {{ old('satuan', $produk->satuan) == 'set' ? 'selected' : '' }}>SET</option>
                                <option value="liter" {{ old('satuan', $produk->satuan) == 'liter' ? 'selected' : '' }}>LITER</option>
                                <option value="kg" {{ old('satuan', $produk->satuan) == 'kg' ? 'selected' : '' }}>KG</option>
                                <option value="unit" {{ old('satuan', $produk->satuan) == 'unit' ? 'selected' : '' }}>UNIT</option>
                            </select>
                            @error('satuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status_produk" class="form-label">
                                <strong>STATUS PRODUK</strong>
                            </label>
                            <select class="form-control @error('status_produk') is-invalid @enderror" 
                                    id="status_produk" 
                                    name="status_produk" 
                                    required>
                                <option value="aktif" {{ old('status_produk', $produk->status_produk) == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                                <option value="nonaktif" {{ old('status_produk', $produk->status_produk) == 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
                            </select>
                            @error('status_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection