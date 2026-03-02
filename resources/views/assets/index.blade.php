@extends('layouts.admin')

@section('title', 'Assets')

@section('breadcrumb')
    <li class="breadcrumb-item active">Assets</li>
@endsection

@section('page-title', 'Assets')
@section('page-subtitle', 'Kelola semua asset terdaftar.')

@section('page-actions')
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddAsset"
        style="border-radius: 8px; font-size: 13px;">
        <i class="bi bi-plus-lg me-1"></i> Add Asset
    </button>
@endsection

@push('styles')
    <style>
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--color-border);
            background: #fff;
            color: var(--color-text-muted);
        }

        .action-btn.action-primary {
            color: var(--color-accent);
            border-color: #c7d2fb;
            background: var(--color-accent-light);
        }

        .action-btn.action-danger {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff5f5;
        }

        .asset-accordion .accordion-button {
            box-shadow: none !important;
            border: 1px solid var(--color-border);
            background: #fff;
        }

        .asset-accordion .accordion-button:not(.collapsed) {
            color: var(--color-text);
            background: #f8fafc;
        }

        /* Shared modal form styles */
        .asset-form-modal .modal-content {
            max-height: calc(100vh - 2rem);
        }

        .asset-form-modal .modal-body {
            max-height: calc(100vh - 210px);
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        .modal .form-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .modal .form-control,
        .modal .form-select {
            border-radius: 8px;
            font-size: 13.5px;
            border: 1px solid var(--color-border);
            padding: 8px 12px;
        }

        .master-lov-row {
            cursor: pointer;
        }

        .master-lov-row:hover td {
            background: #f8fafc;
        }

        /* Add and Edit modal header (yellow accent) */
        #modalAddAsset .modal-header,
        #modalEditAsset .modal-header {
            background: #fefce8;
            border-bottom: 1px solid #fde047;
        }

        #modalAddAsset .modal-title,
        #modalEditAsset .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #modalAddAsset .modal-header .title-icon,
        #modalEditAsset .modal-header .title-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #fde047;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        /* LOV button matches yellow theme */
        #modalAddAsset .input-group-text,
        #modalEditAsset .input-group-text {
            background: #fde047;
            border-color: #fde047;
            cursor: pointer;
            border-radius: 0 8px 8px 0 !important;
        }


        /* Detail modal clean card style */

        #modalDetailAsset .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
        }

        #modalDetailAsset .modal-header {
            background: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 18px 22px 14px;
        }

        #modalDetailAsset .modal-title {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        #modalDetailAsset .modal-title .title-icon {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            background: #fef9c3;
            border: 1px solid #fde047;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        #modalDetailAsset .modal-body {
            padding: 0;
            background: #f8fafc;
        }

        /* Identity banner */
        .detail-identity {
            background: #fff;
            padding: 18px 22px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .detail-identity-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #fef9c3;
            border: 1px solid #fde047;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .detail-identity-code {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 2px;
        }

        .detail-identity-name {
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.3;
        }

        .detail-identity-master-code {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .detail-condition-badge {
            margin-left: auto;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            padding: 4px 10px;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .detail-condition-badge.good {
            background: #dcfce7;
            color: #15803d;
        }

        .detail-condition-badge.minor {
            background: #fef9c3;
            color: #a16207;
        }

        .detail-condition-badge.major {
            background: #fee2e2;
            color: #b91c1c;
        }

        .detail-condition-badge.broken {
            background: #f1f5f9;
            color: #475569;
        }

        .condition-badge {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            border-radius: 20px;
            padding: 4px 10px;
            display: inline-flex;
            align-items: center;
            line-height: 1;
        }

        .condition-badge.good {
            background: #dcfce7;
            color: #15803d;
        }

        .condition-badge.minor {
            background: #fef9c3;
            color: #a16207;
        }

        .condition-badge.major {
            background: #fee2e2;
            color: #b91c1c;
        }

        .condition-badge.broken {
            background: #f1f5f9;
            color: #475569;
        }

        /* Sections */
        .detail-section {
            padding: 16px 22px;
            background: #fff;
        }

        .detail-section+.detail-section {
            border-top: 6px solid #f8fafc;
        }

        .detail-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .detail-field {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .detail-field-key {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .detail-field-val {
            font-size: 13.5px;
            font-weight: 500;
            color: #1e293b;
        }

        /* Notes box */
        .detail-notes-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            font-size: 13px;
            color: #475569;
            line-height: 1.6;
            min-height: 36px;
            margin-top: 6px;
        }

        /* Timestamps footer */
        .detail-timestamps {
            padding: 11px 22px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .detail-timestamps span {
            font-size: 11px;
            color: #94a3b8;
        }

        .detail-timestamps span strong {
            color: #64748b;
            font-weight: 600;
        }
    </style>
@endpush

@section('content')
    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div style="font-size: 12px; color: var(--color-text-muted);">
                Total {{ $assets->count() }} asset
            </div>
            <div style="font-size: 12px; color: var(--color-text-muted);">
                Search, show entries, dan pagination tersedia di tiap master asset
            </div>
        </div>

        <div id="assetAccordionWrap" class="asset-accordion p-3">
            @include('assets.partials.accordion', ['assets' => $assets])
        </div>
    </div>

    {{-- ADD ASSET MODAL --}}
    <div class="modal fade asset-form-modal" id="modalAddAsset" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="title-icon"><i class="bi bi-plus-lg"></i></span>
                        Add Asset
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="{{ route('assets.store') }}" id="formAddAsset">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Aset</label>
                                <input type="text" name="asset_code" id="addGeneratedAssetCode"
                                    class="form-control bg-light" value="{{ $nextAssetCode }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Aset</label>
                                <input type="hidden" name="master_asset_id" id="addMasterAssetId"
                                    value="{{ old('master_asset_id') }}">
                                <div class="input-group">
                                    <input type="text" id="addAssetName"
                                        class="form-control lov-trigger @error('master_asset_id') is-invalid @enderror"
                                        value="{{ old('master_asset_id') ? $masterAssets->firstWhere('id', old('master_asset_id'))->asset_name ?? '' : '' }}"
                                        data-prefix="add" placeholder="Pilih master asset" readonly>
                                    <button type="button" class="input-group-text lov-trigger" data-prefix="add">
                                        <i class="bi bi-table"></i>
                                    </button>
                                </div>
                                @error('master_asset_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IMEI</label>
                                <input type="text" name="gps_number" inputmode="numeric" pattern="[0-9]*" data-imei-input
                                    class="form-control @error('gps_number') is-invalid @enderror"
                                    value="{{ old('gps_number') }}" placeholder="masukan imei (opsional)">
                                @error('gps_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kondisi</label>
                                <select name="condition" class="form-select @error('condition') is-invalid @enderror">
                                    <option value="" @selected(old('condition') === null || old('condition') === '')>Pilih kondisi</option>
                                    <option value="good" @selected(old('condition') === 'good')>GOOD</option>
                                    <option value="minor" @selected(old('condition') === 'minor')>MINOR</option>
                                    <option value="major" @selected(old('condition') === 'major')>MAJOR</option>
                                    <option value="broken" @selected(old('condition') === 'broken')>BROKEN</option>
                                </select>
                                @error('condition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Beli</label>
                                <input type="date" name="purchase_date"
                                    class="form-control @error('purchase_date') is-invalid @enderror"
                                    value="{{ old('purchase_date') }}">
                                @error('purchase_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Aktual</label>
                                <input type="number" name="purchase_price"
                                    class="form-control @error('purchase_price') is-invalid @enderror"
                                    value="{{ old('purchase_price') }}">
                                @error('purchase_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Garansi</label>
                                <input type="date" name="warranty_expired"
                                    class="form-control @error('warranty_expired') is-invalid @enderror"
                                    value="{{ old('warranty_expired') }}">
                                @error('warranty_expired')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- EDIT ASSET MODAL --}}
    <div class="modal fade asset-form-modal" id="modalEditAsset" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="title-icon"><i class="bi bi-pencil"></i></span>
                        Edit Asset
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="formEditAsset">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Kode Aset</label>
                                <input type="text" id="editGeneratedAssetCode" class="form-control bg-light" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Aset</label>
                                <input type="hidden" name="master_asset_id" id="editMasterAssetId">
                                <div class="input-group">
                                    <input type="text" id="editAssetName" class="form-control lov-trigger"
                                        data-prefix="edit" placeholder="Pilih master asset" readonly>
                                    <button type="button" class="input-group-text lov-trigger" data-prefix="edit">
                                        <i class="bi bi-table"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">IMEI</label>
                                <input type="text" name="gps_number" id="editImei" inputmode="numeric"
                                    pattern="[0-9]*" data-imei-input class="form-control"
                                    placeholder="masukan imei (opsional)">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kondisi</label>
                                <select name="condition" id="editCondition" class="form-select">
                                    <option value="">Pilih kondisi</option>
                                    <option value="good">GOOD</option>
                                    <option value="minor">MINOR</option>
                                    <option value="major">MAJOR</option>
                                    <option value="broken">BROKEN</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tanggal Beli</label>
                                <input type="date" name="purchase_date" id="editPurchaseDate" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Aktual</label>
                                <input type="number" name="purchase_price" id="editPurchasePrice" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Garansi</label>
                                <input type="date" name="warranty_expired" id="editWarrantyExpired"
                                    class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Catatan</label>
                                <textarea name="notes" id="editNotes" rows="3" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-light" data-bs-dismiss="modal" type="button">Cancel</button>
                        <button class="btn btn-primary" type="submit">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- DETAIL ASSET MODAL --}}
    <div class="modal fade" id="modalDetailAsset" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable" style="max-width: 480px;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <span class="title-icon"><i class="bi bi-box2"></i></span>
                        Detail Asset
                    </h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    {{-- Identity banner --}}
                    <div class="detail-identity">
                        <div class="detail-identity-icon"><i class="bi bi-box-seam"></i></div>
                        <div style="min-width: 0;">
                            <div class="detail-identity-code" id="detailAssetCode">-</div>
                            <div class="detail-identity-name" id="detailAssetName">-</div>
                            <div class="detail-identity-master-code">Master: <span id="detailMasterCode">-</span></div>
                        </div>
                        <span class="detail-condition-badge" id="detailConditionBadge">-</span>
                    </div>

                    {{-- Asset info --}}
                    <div class="detail-section">
                        <div class="detail-section-label"><i class="bi bi-info-circle"></i> Info Asset</div>
                        <div class="detail-grid">
                            <div class="detail-field">
                                <span class="detail-field-key">IMEI</span>
                                <span class="detail-field-val" id="detailImei">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Kondisi</span>
                                <span class="detail-field-val" id="detailCondition">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Tanggal Beli</span>
                                <span class="detail-field-val" id="detailPurchaseDate">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Harga Aktual</span>
                                <span class="detail-field-val" id="detailPurchasePrice">-</span>
                            </div>
                            <div class="detail-field" style="grid-column: 1 / -1;">
                                <span class="detail-field-key">Garansi</span>
                                <span class="detail-field-val" id="detailWarrantyExpired">-</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="detail-field-key d-block">Catatan</span>
                            <div class="detail-notes-box" id="detailNotes">-</div>
                        </div>
                    </div>

                    {{-- Master asset info --}}
                    <div class="detail-section">
                        <div class="detail-section-label"><i class="bi bi-layers"></i> Master Asset</div>
                        <div class="detail-grid">
                            <div class="detail-field">
                                <span class="detail-field-key">Kategori</span>
                                <span class="detail-field-val" id="detailMasterCategory">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Brand</span>
                                <span class="detail-field-val" id="detailMasterBrand">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Model</span>
                                <span class="detail-field-val" id="detailMasterModel">-</span>
                            </div>
                            <div class="detail-field">
                                <span class="detail-field-key">Umur Manfaat</span>
                                <span class="detail-field-val" id="detailMasterUsefulLife">-</span>
                            </div>
                            <div class="detail-field" style="grid-column: 1 / -1;">
                                <span class="detail-field-key">Harga Beli Standar</span>
                                <span class="detail-field-val" id="detailMasterPurchasePrice">-</span>
                            </div>
                        </div>
                        <div class="mt-3">
                            <span class="detail-field-key d-block">Spesifikasi</span>
                            <div class="detail-notes-box" id="detailMasterSpec">-</div>
                        </div>
                    </div>
                </div>

                {{-- Timestamps footer --}}
                <div class="detail-timestamps">
                    <span><strong>Created:</strong> <span id="detailCreated">-</span></span>
                    <span><strong>Updated:</strong> <span id="detailUpdated">-</span></span>
                </div>
            </div>
        </div>
    </div>

    {{-- LOV MASTER ASSET MODAL --}}
    <div class="modal fade" id="modalLovMasterAsset" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Pilih Master Asset (Aktif)</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th style="width: 56px;">No</th>
                                    <th>Kode Master</th>
                                    <th>Nama Aset</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($masterAssets as $master)
                                    <tr class="master-lov-row btn-pick-master" data-id="{{ $master->id }}"
                                        data-code="{{ $master->asset_code }}" data-name="{{ $master->asset_name }}">
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $master->asset_code }}</td>
                                        <td>{{ $master->asset_name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-3 text-muted">Tidak ada master asset
                                            aktif</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addAssetModalEl = document.getElementById('modalAddAsset');
            const editAssetModalEl = document.getElementById('modalEditAsset');
            const lovModalEl = document.getElementById('modalLovMasterAsset');
            const formAddAsset = document.getElementById('formAddAsset');
            const formEditAsset = document.getElementById('formEditAsset');
            const defaultAddCode = @json($nextAssetCode);
            let currentLovPrefix = 'add';
            let reopenModalIdAfterLov = null;
            let skipResetModalId = null;

            /* condition badge helper */
            function applyConditionBadge(raw) {
                const badge = document.getElementById('detailConditionBadge');
                if (!badge) return;
                const normalized = normalizeCondition(raw);
                badge.textContent = normalized ? normalized.toUpperCase() : '-';
                badge.className = 'detail-condition-badge ' + (normalized || '').toLowerCase();
            }

            function normalizeCondition(value) {
                const v = (value || '').toLowerCase();
                if (v === 'broker') return 'broken';
                return v;
            }

            function stripHtmlTags(html) {
                if (!html) return '';
                const div = document.createElement('div');
                div.innerHTML = html;
                return (div.textContent || div.innerText || '').trim();
            }

            function initializeGroupPagination() {
                const tables = document.querySelectorAll('.group-table');

                tables.forEach((table) => {
                    const group = table.dataset.group;
                    const tbody = table.querySelector('tbody');
                    const allRows = Array.from(tbody.querySelectorAll('.group-row'));
                    const searchInputEl = document.querySelector(`.group-search[data-group="${group}"]`);
                    const entriesEl = document.querySelector(`.group-entries[data-group="${group}"]`);
                    const paginationEl = document.querySelector(`.group-pagination[data-group="${group}"]`);
                    const infoEl = document.querySelector(`.group-pagination-info[data-group="${group}"]`);

                    if (!tbody || !entriesEl || !paginationEl || !infoEl) return;

                    let currentPage = 1;
                    let perPage = parseInt(entriesEl.value || '10', 10);
                    let keyword = '';

                    const emptyRow = document.createElement('tr');
                    emptyRow.className = 'group-empty-row';
                    emptyRow.innerHTML =
                        '<td colspan="8" class="text-center py-4 text-muted">Tidak ada data pada filter ini</td>';

                    const buildPaginationButton = (label, page, disabled = false, active = false) => {
                        const li = document.createElement('li');
                        li.className =
                            `page-item${disabled ? ' disabled' : ''}${active ? ' active' : ''}`;
                        const a = document.createElement('a');
                        a.href = '#';
                        a.className = 'page-link';
                        a.textContent = label;
                        a.dataset.page = String(page);
                        li.appendChild(a);
                        return li;
                    };

                    const render = () => {
                        const filtered = allRows.filter((row) => !keyword || (row.dataset.search || '')
                            .includes(keyword));
                        const totalItems = filtered.length;
                        const totalPages = Math.max(1, Math.ceil(totalItems / perPage));
                        if (currentPage > totalPages) currentPage = 1;

                        const startIndex = (currentPage - 1) * perPage;
                        const endIndex = startIndex + perPage;
                        const pageItems = filtered.slice(startIndex, endIndex);

                        allRows.forEach((row) => {
                            row.style.display = 'none';
                        });

                        pageItems.forEach((row, i) => {
                            row.style.display = '';
                            const noCell = row.querySelector('td');
                            if (noCell) noCell.textContent = String(startIndex + i + 1);
                        });

                        if (totalItems === 0) {
                            if (!tbody.contains(emptyRow)) tbody.appendChild(emptyRow);
                            infoEl.textContent = 'Showing 0-0 of 0 entries';
                        } else {
                            emptyRow.remove();
                            infoEl.textContent =
                                `Showing ${startIndex + 1}-${Math.min(endIndex, totalItems)} of ${totalItems} entries`;
                        }

                        paginationEl.innerHTML = '';
                        paginationEl.appendChild(buildPaginationButton('<<', currentPage - 1,
                            currentPage === 1));
                        for (let page = 1; page <= totalPages; page++) {
                            paginationEl.appendChild(buildPaginationButton(String(page), page, false,
                                page === currentPage));
                        }
                        paginationEl.appendChild(buildPaginationButton('>>', currentPage + 1,
                            currentPage === totalPages));
                    };

                    searchInputEl?.addEventListener('input', function() {
                        keyword = this.value.trim().toLowerCase();
                        currentPage = 1;
                        render();
                    });

                    entriesEl.addEventListener('change', function() {
                        perPage = parseInt(this.value || '10', 10);
                        currentPage = 1;
                        render();
                    });

                    paginationEl.addEventListener('click', function(e) {
                        const link = e.target.closest('a.page-link');
                        if (!link) return;
                        e.preventDefault();

                        const li = link.closest('.page-item');
                        if (li?.classList.contains('disabled') || li?.classList.contains('active'))
                            return;

                        const nextPage = parseInt(link.dataset.page || '1', 10);
                        if (Number.isNaN(nextPage) || nextPage < 1) return;

                        currentPage = nextPage;
                        render();
                    });

                    render();
                });
            }

            function bindEvents() {
                if (window._assetPageBound) return;

                document.addEventListener('click', function(e) {

                    /* DETAIL */
                    const detailBtn = e.target.closest('.btn-detail');
                    if (detailBtn) {
                        const d = detailBtn.dataset;
                        document.getElementById('detailAssetCode').innerText = d.assetCode || '-';
                        document.getElementById('detailMasterCode').innerText = d.masterCode || '-';
                        document.getElementById('detailAssetName').innerText = d.assetName || '-';
                        document.getElementById('detailImei').innerText = d.imei || '-';
                        const normalizedCondition = normalizeCondition(d.condition);
                        document.getElementById('detailCondition').innerText = normalizedCondition ?
                            normalizedCondition.toUpperCase() : '-';
                        document.getElementById('detailPurchaseDate').innerText = d.purchaseDate || '-';
                        document.getElementById('detailPurchasePrice').innerText = d.purchasePrice ?
                            'Rp ' + Number(d.purchasePrice).toLocaleString('id-ID') : '-';
                        document.getElementById('detailWarrantyExpired').innerText = d.warrantyExpired ||
                            '-';
                        document.getElementById('detailNotes').innerText = d.notes || '-';
                        document.getElementById('detailMasterCategory').innerText = d.masterCategory || '-';
                        document.getElementById('detailMasterBrand').innerText = d.masterBrand || '-';
                        document.getElementById('detailMasterModel').innerText = d.masterModel || '-';
                        const detailSpec = stripHtmlTags(d.masterSpec || '');
                        document.getElementById('detailMasterSpec').textContent = detailSpec || '-';
                        document.getElementById('detailMasterPurchasePrice').innerText = d
                            .masterPurchasePrice ?
                            'Rp ' + Number(d.masterPurchasePrice).toLocaleString('id-ID') : '-';
                        document.getElementById('detailMasterUsefulLife').innerText = d.masterUsefulLife ?
                            d.masterUsefulLife + ' Tahun' : '-';
                        document.getElementById('detailCreated').innerText = d.created || '-';
                        document.getElementById('detailUpdated').innerText = d.updated || '-';
                        applyConditionBadge(normalizedCondition);
                        new bootstrap.Modal(document.getElementById('modalDetailAsset')).show();
                        return;
                    }

                    /* EDIT */
                    const editBtn = e.target.closest('.btn-edit');
                    if (editBtn) {
                        const d = editBtn.dataset;
                        document.getElementById('editGeneratedAssetCode').value = d.assetCode || '';
                        document.getElementById('editMasterAssetId').value = d.masterId || '';
                        document.getElementById('editAssetName').value = d.assetName || '';
                        document.getElementById('editImei').value = d.imei || '';
                        document.getElementById('editCondition').value = normalizeCondition(d.condition) ||
                            '';
                        document.getElementById('editPurchaseDate').value = d.purchaseDate || '';
                        document.getElementById('editPurchasePrice').value = d.purchasePrice || '';
                        document.getElementById('editWarrantyExpired').value = d.warrantyExpired || '';
                        document.getElementById('editNotes').value = d.notes || '';
                        formEditAsset.setAttribute('action', `/assets/${d.id}`);
                        new bootstrap.Modal(editAssetModalEl).show();
                        return;
                    }

                    /* DELETE */
                    const deleteBtn = e.target.closest('.btn-delete');
                    if (deleteBtn) {
                        const form = deleteBtn.closest('.form-delete');
                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: 'Data yang dihapus tidak bisa dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) form?.submit();
                        });
                        return;
                    }

                    /* LOV TRIGGER */
                    const lovTrigger = e.target.closest('.lov-trigger');
                    if (lovTrigger) {
                        currentLovPrefix = lovTrigger.dataset.prefix || 'add';
                        reopenModalIdAfterLov = currentLovPrefix === 'edit' ? 'modalEditAsset' :
                            'modalAddAsset';
                        skipResetModalId = reopenModalIdAfterLov;
                        const reopenEl = document.getElementById(reopenModalIdAfterLov);
                        bootstrap.Modal.getOrCreateInstance(reopenEl).hide();
                        setTimeout(() => bootstrap.Modal.getOrCreateInstance(lovModalEl).show(), 150);
                        return;
                    }

                    /* PICK MASTER */
                    const pickMaster = e.target.closest('.btn-pick-master');
                    if (pickMaster) {
                        const id = pickMaster.dataset.id || '';
                        const name = pickMaster.dataset.name || '';
                        if (currentLovPrefix === 'edit') {
                            document.getElementById('editMasterAssetId').value = id;
                            document.getElementById('editAssetName').value = name;
                        } else {
                            document.getElementById('addMasterAssetId').value = id;
                            document.getElementById('addAssetName').value = name;
                        }
                        bootstrap.Modal.getOrCreateInstance(lovModalEl).hide();
                    }
                });

                window._assetPageBound = true;
            }

            initializeGroupPagination();
            bindEvents();

            lovModalEl?.addEventListener('hidden.bs.modal', function() {
                if (reopenModalIdAfterLov) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById(reopenModalIdAfterLov))
                        .show();
                    reopenModalIdAfterLov = null;
                }
            });

            addAssetModalEl?.addEventListener('hidden.bs.modal', function() {
                if (skipResetModalId === 'modalAddAsset') {
                    skipResetModalId = null;
                    return;
                }
                formAddAsset?.reset();
                document.getElementById('addMasterAssetId').value = '';
                document.getElementById('addAssetName').value = '';
                document.getElementById('addGeneratedAssetCode').value = defaultAddCode;
            });

            editAssetModalEl?.addEventListener('hidden.bs.modal', function() {
                if (skipResetModalId === 'modalEditAsset') {
                    skipResetModalId = null;
                    return;
                }
                formEditAsset?.reset();
                formEditAsset?.setAttribute('action', '');
                document.getElementById('editMasterAssetId').value = '';
                document.getElementById('editGeneratedAssetCode').value = '';
                document.getElementById('editAssetName').value = '';
            });

            document.addEventListener('input', function(e) {
                const imeiInput = e.target.closest('[data-imei-input]');
                if (!imeiInput) return;
                imeiInput.value = imeiInput.value.replace(/\D/g, '');
            });

            @if ($errors->any())
                bootstrap.Modal.getOrCreateInstance(addAssetModalEl).show();
            @endif
        });
    </script>
@endpush
