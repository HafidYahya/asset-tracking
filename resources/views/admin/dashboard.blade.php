@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('page-title', 'Dashboard')
@section('page-subtitle', 'Selamat datang kembali, ' . (auth()->user()->name ?? 'Admin') . '!')

@section('content')
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Users</div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
                <div class="stat-change change-neutral">Total pengguna terdaftar</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Master Assets</div>
                <div class="stat-value">{{ number_format($totalMasterAssets) }}</div>
                <div class="stat-change change-up">{{ number_format($activeMasterAssets) }} aktif</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Assets</div>
                <div class="stat-value">{{ number_format($totalAssets) }}</div>
                <div class="stat-change change-neutral">Asset operasional</div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-xl-3">
            <div class="stat-card">
                <div class="stat-label">Tracking IMEI</div>
                <div class="stat-value">{{ number_format($trackedAssets) }}</div>
                <div class="stat-change change-neutral">IMEI terdeteksi lokasi</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Asset Terbaru</div>
        <div class="table-responsive">
            <table class="table table-hover text-nowrap mb-0">
                <thead>
                    <tr>
                        <th>Kode Asset</th>
                        <th>Nama Aset</th>
                        <th>IMEI</th>
                        <th>Kondisi</th>
                        <th>Tanggal Beli</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestAssets as $asset)
                        <tr>
                            <td>{{ $asset->asset_code ?: '-' }}</td>
                            <td>{{ $asset->masterAsset->asset_name ?? '-' }}</td>
                            <td>{{ $asset->gps_number ?: '-' }}</td>
                            <td>
                                <span
                                    class="text-uppercase badge text-light rounded-pill  {{ $asset->condition === 'good' ? 'bg-success' : ($asset->condition === 'minor' ? 'bg-info' : ($asset->condition === 'major' ? 'bg-warning' : 'bg-danger')) }}">{{ $asset->condition ?: '-' }}</span>
                            </td>
                            <td>{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted">Belum ada data asset.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
