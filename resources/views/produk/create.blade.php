@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Tambah Produk Baru</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('produk.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="id_produk" class="form-label">
                                <strong>ID PRODUK</strong>
                                <small class="text-muted"> (contoh: PR01, PR02, PRD-001)</small>
                            </label>
                            <input type="text" 
                                   class="form-control @error('id_produk') is-invalid @enderror" 
                                   id="id_produk" 
                                   name="id_produk" 
                                   value="{{ old('id_produk') }}" 
                                   required 
                                   placeholder="PR01">
                            @error('id_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Gunakan format: PR + angka (PR01, PR02, dst) atau kode custom
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">
                                <strong>NAMA PRODUK</strong>
                            </label>
                            <input type="text" 
                                   class="form-control @error('nama_produk') is-invalid @enderror" 
                                   id="nama_produk" 
                                   name="nama_produk" 
                                   value="{{ old('nama_produk') }}" 
                                   required 
                                   placeholder="Footstep K2F2">
                            @error('nama_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="satuan" class="form-label">
                                <strong>SATUAN</strong>
                            </label>
                            <select class="form-control @error('satuan') is-invalid @enderror" 
                                    id="satuan" 
                                    name="satuan">
                                <option value="pcs" {{ old('satuan') == 'pcs' ? 'selected' : '' }}>PCS</option>
                                <option value="set" {{ old('satuan') == 'set' ? 'selected' : '' }}>SET</option>
                                <option value="unit" {{ old('satuan') == 'unit' ? 'selected' : '' }}>UNIT</option>
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
                                <option value="aktif" {{ old('status_produk') == 'aktif' ? 'selected' : '' }}>AKTIF</option>
                                <option value="nonaktif" {{ old('status_produk') == 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
                            </select>
                            @error('status_produk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection