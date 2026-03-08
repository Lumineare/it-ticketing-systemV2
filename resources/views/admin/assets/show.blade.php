@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2">{{ $asset->name }}</h1>
        <code class="text-muted">{{ $asset->asset_id }}</code>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.assets.edit', $asset) }}" class="btn btn-warning"><i class="bi bi-pencil me-1"></i> Edit</a>
        <a href="{{ route('admin.assets.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
    </div>
</div>

<div class="row g-4">
    {{-- Kolom Kiri: Info Utama --}}
    <div class="col-md-7">
        <div class="card shadow-sm h-100">
            <div class="card-header fw-semibold bg-light">Informasi Perangkat</div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tbody>
                        <tr><th style="width:160px" class="text-muted">Kategori</th><td><span class="badge bg-secondary">{{ $asset->category }}</span></td></tr>
                        <tr><th class="text-muted">Kondisi</th>
                            <td>
                                @php
                                    $cls = match($asset->condition) {
                                        'Baik' => 'bg-success',
                                        'Perlu Servis' => 'bg-warning text-dark',
                                        'Rusak' => 'bg-danger',
                                        default => 'bg-secondary',
                                    };
                                @endphp
                                <span class="badge {{ $cls }}">{{ $asset->condition }}</span>
                            </td>
                        </tr>
                        <tr><th class="text-muted">Merek</th><td>{{ $asset->brand ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Model / Seri</th><td>{{ $asset->model ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Serial Number</th><td>{{ $asset->serial_number ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Lokasi</th><td>{{ $asset->location ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Pemegang</th><td>{{ $asset->assigned_to ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Dicatat</th><td>{{ $asset->created_at->format('d M Y') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Kolom Kanan: Pembelian & Garansi --}}
    <div class="col-md-5">
        <div class="card shadow-sm mb-3">
            <div class="card-header fw-semibold bg-light">Pembelian & Garansi</div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0">
                    <tbody>
                        <tr><th style="width:140px" class="text-muted">Tgl. Pembelian</th><td>{{ $asset->purchase_date?->format('d M Y') ?? '-' }}</td></tr>
                        <tr><th class="text-muted">Akhir Garansi</th>
                            <td>
                                @if($asset->warranty_expiry)
                                    @if($asset->warranty_expiry->isPast())
                                        <span class="text-danger">{{ $asset->warranty_expiry->format('d M Y') }} (Kadaluwarsa)</span>
                                    @else
                                        <span class="text-success">{{ $asset->warranty_expiry->format('d M Y') }}</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        <tr><th class="text-muted">Harga Beli</th><td>{{ $asset->purchase_price ? 'Rp ' . number_format($asset->purchase_price, 0, ',', '.') : '-' }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Hapus Aset --}}
        <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aset ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger w-100"><i class="bi bi-trash me-1"></i> Hapus Aset Ini</button>
        </form>
    </div>

    {{-- Spesifikasi --}}
    @if($asset->specs)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-light">Spesifikasi Teknis</div>
            <div class="card-body"><pre class="mb-0" style="white-space: pre-wrap;">{{ $asset->specs }}</pre></div>
        </div>
    </div>
    @endif

    {{-- Catatan --}}
    @if($asset->notes)
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-light">Catatan Tambahan</div>
            <div class="card-body"><p class="mb-0">{{ $asset->notes }}</p></div>
        </div>
    </div>
    @endif
</div>
@endsection
