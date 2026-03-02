@extends('layouts.admin')

@section('title', 'Asset Tracking')

@section('breadcrumb')
    <li class="breadcrumb-item active">Asset Tracking</li>
@endsection

@section('page-title', 'Asset Tracking')
@section('page-subtitle', 'Pantau posisi asset dan zoom ke pin berdasarkan IMEI.')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #trackingMap {
            width: 100%;
            height: 460px;
            border-radius: 12px;
            border: 1px solid var(--color-border);
        }

        .tracking-search {
            max-width: 360px;
        }
    </style>
@endpush

@section('content')
    <div class="card mb-4">
        <div class="card-body p-3 p-md-4">
            <div id="trackingMap"></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
            <div class="position-relative tracking-search">
                <i class="bi bi-search position-absolute"
                    style="top:50%;left:10px;transform:translateY(-50%);color:var(--color-text-muted);font-size:13px;pointer-events:none;"></i>
                <input type="search" id="trackingSearch" class="form-control form-control ps-5"
                    placeholder="Search IMEI, kode aset, nama aset...">
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <form method="GET" id="trackingPerPageForm" class="d-flex align-items-center gap-2 m-0">
                    <label class="small text-muted mb-0">Show</label>
                    <select name="per_page" id="trackingPerPage" class="form-select form-select-sm"
                        style="width: 90px; border-radius: 8px;">
                        @foreach ([10, 25, 50, 100] as $size)
                            <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                                {{ $size }}
                            </option>
                        @endforeach
                    </select>
                    <span class="small text-muted">entries</span>
                </form>
                <span class="text-muted" style="font-size:12px;">
                    {{ $trackings->count() }} lokasi terbaru
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap" id="trackingTable">
                <thead>
                    <tr>
                        <th>IMEI</th>
                        <th>Status</th>
                        <th>Kode Aset</th>
                        <th>Nama Aset</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($trackings as $row)
                        <tr data-search="{{ strtolower(trim(($row->imei ?? '') . ' ' . ($row->asset_code ?? '') . ' ' . ($row->asset_name ?? ''))) }}"
                            data-marker-key="{{ $row->imei }}">
                            <td>{{ $row->imei ?? '-' }}</td>
                            <td>
                                <span
                                    class="badge badge-md 
        {{ $row->status === 1 ? 'bg-success' : ($row->status === 0 ? 'bg-danger' : 'bg-secondary') }}">

                                    {{ $row->status === 1 ? 'Aktif' : ($row->status === 0 ? 'Nonaktif' : '-') }}

                                </span>
                            </td>
                            <td>{{ $row->asset_code ?? '-' }}</td>
                            <td>{{ $row->asset_name ?? '-' }}</td>
                            <td class="text-end pe-4">
                                @if (!is_null($row->lat) && !is_null($row->lng))
                                    <button type="button" class="btn btn-sm btn-primary btn-zoom"
                                        data-marker-key="{{ $row->imei }}">
                                        <i class="bi bi-geo-alt me-1"></i>Zoom
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled>
                                        <i class="bi bi-slash-circle me-1"></i>No Pin
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Data tracking belum tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($trackings->hasPages())
                <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div style="font-size: 13px; color: var(--color-text-muted);">
                        Showing {{ $trackings->firstItem() }}-{{ $trackings->lastItem() }} of {{ $trackings->total() }}
                        data
                    </div>
                    <div>
                        {{ $trackings->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackingData = @json($trackings->items());
            const tableRows = Array.from(document.querySelectorAll('#trackingTable tbody tr[data-search]'));
            const searchInput = document.getElementById('trackingSearch');
            const trackingPerPage = document.getElementById('trackingPerPage');
            const trackingPerPageForm = document.getElementById('trackingPerPageForm');
            const map = L.map('trackingMap');
            const markersByKey = {};

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const markerCoords = [];
            trackingData.forEach((item) => {
                const lat = Number(item.lat);
                const lng = Number(item.lng);
                if (Number.isFinite(lat) && Number.isFinite(lng)) {
                    const popupHtml = `
                        <div style="font-size:12px;">
                            <div><strong>IMEI:</strong> ${item.imei ?? '-'}</div>
                            <div><strong>Kode Aset:</strong> ${item.asset_code ?? '-'}</div>
                            <div><strong>Nama Aset:</strong> ${item.asset_name ?? '-'}</div>
                            <div><strong>Electricity:</strong> ${item.electQuantity ?? '-'} %</div>
                            <div><strong>Status:</strong> <span class="badge ${Number(item.status) === 1 ? 'bg-success' : Number(item.status) === 0 ? 'bg-danger' : 'bg-secondary'}">${Number(item.status) === 1 ? 'Aktif' : Number(item.status) === 0 ? 'Nonaktif' : '-'}</span></div>
                    `;
                    const marker = L.marker([lat, lng]).addTo(map).bindPopup(popupHtml);
                    markersByKey[item.imei] = marker;
                    markerCoords.push([lat, lng]);
                }
            });

            if (markerCoords.length > 0) {
                map.fitBounds(markerCoords, {
                    padding: [30, 30]
                });
            } else {
                map.setView([-2.5, 118], 5);
            }

            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-zoom');
                if (!btn) return;
                const key = btn.dataset.markerKey;
                const marker = markersByKey[key];
                if (!marker) return;
                const latLng = marker.getLatLng();
                map.setView(latLng, 17, {
                    animate: true
                });
                marker.openPopup();
            });

            function applyFilter(keyword) {
                const normalized = keyword.trim().toLowerCase();
                tableRows.forEach((row) => {
                    const matched = !normalized || row.dataset.search.includes(normalized);
                    row.style.display = matched ? '' : 'none';

                    const markerKey = row.dataset.markerKey;
                    const marker = markersByKey[markerKey];
                    if (marker) {
                        if (matched && !map.hasLayer(marker)) {
                            marker.addTo(map);
                        }
                        if (!matched && map.hasLayer(marker)) {
                            map.removeLayer(marker);
                        }
                    }
                });
            }

            searchInput?.addEventListener('input', function() {
                applyFilter(this.value);
            });

            trackingPerPage?.addEventListener('change', function() {
                trackingPerPageForm?.submit();
            });
        });
    </script>
@endpush
