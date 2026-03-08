@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Aset: {{ $asset->name }}</h1>
    <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body p-4">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('admin.assets.update', $asset) }}" method="POST">
            @csrf @method('PUT')

            {{-- ================ INFORMASI UTAMA ================ --}}
            <h6 class="text-muted fw-bold text-uppercase mb-3 border-bottom pb-2">Informasi Utama</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Perangkat <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $asset->name) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                    <select name="category" class="form-select" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $asset->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kondisi <span class="text-danger">*</span></label>
                    <select name="condition" class="form-select" required>
                        @foreach($conditions as $cond)
                            <option value="{{ $cond }}" {{ old('condition', $asset->condition) == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Merek</label>
                    <input type="text" name="brand" class="form-control" value="{{ old('brand', $asset->brand) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Model / Seri</label>
                    <input type="text" name="model" class="form-control" value="{{ old('model', $asset->model) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Serial Number</label>
                    <input type="text" name="serial_number" class="form-control" value="{{ old('serial_number', $asset->serial_number) }}">
                </div>
            </div>

            {{-- ================ PENUGASAN ================ --}}
            <h6 class="text-muted fw-bold text-uppercase mb-3 border-bottom pb-2">Penugasan & Lokasi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Lokasi / Ruangan</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $asset->location) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Pemegang / Ditugaskan kepada</label>
                    <input type="text" name="assigned_to" class="form-control" value="{{ old('assigned_to', $asset->assigned_to) }}">
                </div>
            </div>

            {{-- ================ PEMBELIAN & GARANSI ================ --}}
            <h6 class="text-muted fw-bold text-uppercase mb-3 border-bottom pb-2">Pembelian & Garansi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Tanggal Pembelian</label>
                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $asset->purchase_date?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Akhir Garansi</label>
                    <input type="date" name="warranty_expiry" class="form-control" value="{{ old('warranty_expiry', $asset->warranty_expiry?->format('Y-m-d')) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Harga Beli (Rp)</label>
                    <input type="number" name="purchase_price" class="form-control" value="{{ old('purchase_price', $asset->purchase_price) }}" min="0">
                </div>
            </div>

            {{-- ================ DETAIL TEKNIS ================ --}}
            <h6 class="text-muted fw-bold text-uppercase mb-3 border-bottom pb-2">Detail Teknis & Catatan</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Spesifikasi Teknis</label>
                    <textarea name="specs" class="form-control" rows="4">{{ old('specs', $asset->specs) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Catatan Tambahan</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $asset->notes) }}</textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-light">Batal</a>
                <button type="submit" class="btn btn-warning px-5 text-dark">Perbarui Aset</button>
            </div>
        </form>
    </div>
</div>
@endsection
