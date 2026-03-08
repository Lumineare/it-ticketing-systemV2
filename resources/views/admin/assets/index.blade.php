@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <div>
        <h1 class="h2"><i class="bi bi-pc-display-horizontal me-2"></i>Inventaris Peralatan IT</h1>
        <p class="text-muted small mb-0">Manajemen asset dan perangkat IT seluruh unit.</p>
    </div>
    <a href="{{ route('admin.assets.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Tambah Aset
    </a>
</div>

{{-- Filter & Search --}}
<div class="card shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.assets.index') }}" class="row g-2 align-items-center">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Cari nama, merek, SN, lokasi..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                {{-- KATEGORI: Daftar ini dihasilkan dari controller AssetController::CATEGORIES --}}
                <select name="category" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                {{-- KONDISI: Daftar ini dihasilkan dari controller AssetController::CONDITIONS --}}
                <select name="condition" class="form-select">
                    <option value="">Semua Kondisi</option>
                    @foreach($conditions as $cond)
                        <option value="{{ $cond }}" {{ request('condition') == $cond ? 'selected' : '' }}>{{ $cond }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.assets.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Summary Badges --}}
<div class="row mb-4 g-3">
    @php
        // -- Statistik Cepat: diambil dari query terfilter jika ada search, atau semua aset --
        $totalAssets = \App\Models\Asset::count();
        $byCondition = \App\Models\Asset::selectRaw('`condition`, count(*) as total')->groupBy('condition')->pluck('total', 'condition');
    @endphp
    <div class="col-sm-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="h3 fw-bold text-primary">{{ $totalAssets }}</div>
                <div class="small text-muted">Total Aset</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="h3 fw-bold text-success">{{ $byCondition['Baik'] ?? 0 }}</div>
                <div class="small text-muted">Kondisi Baik</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="h3 fw-bold text-warning">{{ $byCondition['Perlu Servis'] ?? 0 }}</div>
                <div class="small text-muted">Perlu Servis</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card text-center border-0 shadow-sm">
            <div class="card-body">
                <div class="h3 fw-bold text-danger">{{ $byCondition['Rusak'] ?? 0 }}</div>
                <div class="small text-muted">Rusak</div>
            </div>
        </div>
    </div>
</div>

{{-- Tabel Aset --}}
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>Asset ID</th>
                <th>Nama Perangkat</th>
                <th>Kategori</th>
                <th>Merek / Model</th>
                <th>Kondisi</th>
                <th>Lokasi</th>
                <th>Pemegang</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($assets as $asset)
            <tr>
                <td><code class="text-primary">{{ $asset->asset_id }}</code></td>
                <td class="fw-semibold">{{ $asset->name }}</td>
                <td><span class="badge bg-secondary">{{ $asset->category }}</span></td>
                <td>{{ $asset->brand }} {{ $asset->model }}</td>
                <td>
                    @php
                        $conditionClass = match($asset->condition) {
                            'Baik' => 'bg-success',
                            'Perlu Servis' => 'bg-warning text-dark',
                            'Rusak' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    @endphp
                    <span class="badge {{ $conditionClass }}">{{ $asset->condition }}</span>
                </td>
                <td>{{ $asset->location ?? '-' }}</td>
                <td>{{ $asset->assigned_to ?? '-' }}</td>
                <td class="text-center">
                    <a href="{{ route('admin.assets.show', $asset) }}" class="btn btn-sm btn-outline-info" title="Detail"><i class="bi bi-eye"></i></a>
                    <a href="{{ route('admin.assets.edit', $asset) }}" class="btn btn-sm btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.assets.destroy', $asset) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus aset {{ $asset->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">Belum ada data aset. <a href="{{ route('admin.assets.create') }}">Tambah sekarang.</a></td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">{{ $assets->links('pagination::bootstrap-5') }}</div>
@endsection
