@extends('layouts.admin')

@section('title', 'Master Assets')

@section('breadcrumb')
    <li class="breadcrumb-item active">Master Assets</li>
@endsection

@section('page-title', 'Master Assets')
@section('page-subtitle', 'Kelola semua master aset terdaftar.')

@section('page-actions')
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddMasterAsset"
        style="border-radius: 8px; font-size: 13px;">
        <i class="bi bi-plus-lg me-1"></i> Add Master Asset
    </button>
@endsection

@push('styles')
    {{-- Quill.js CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">

    <style>
        /* ===== Pagination ===== */
        .pagination-wrap nav {
            display: flex;
            justify-content: flex-end;
        }

        .pagination-wrap .pagination {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .pagination-wrap .page-item .page-link {
            border: 1px solid var(--color-border);
            border-radius: 8px !important;
            min-width: 34px;
            height: 34px;
            padding: 0 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text-muted);
            background-color: #fff;
            box-shadow: none;
            line-height: 1;
            transition: background-color 0.15s, color 0.15s, border-color 0.15s;
        }

        .pagination-wrap .page-item .page-link:hover {
            background-color: var(--color-accent-light);
            color: var(--color-accent);
            border-color: #c7d2fb;
        }

        .pagination-wrap .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #fff;
            font-weight: 600;
        }

        .pagination-wrap .page-item.disabled .page-link {
            background-color: #f8fafc;
            color: #cbd5e1;
            border-color: var(--color-border);
            cursor: not-allowed;
            pointer-events: none;
        }

        /* ===== Action Buttons ===== */
        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            border: 1px solid var(--color-border);
            background: #fff;
            cursor: pointer;
            color: var(--color-text-muted);
            transition: background-color 0.15s, color 0.15s, border-color 0.15s;
            text-decoration: none;
        }

        .action-btn:hover {
            background: var(--color-bg);
            color: var(--color-text);
        }

        .action-btn.action-danger {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff5f5;
        }

        .action-btn.action-danger:hover {
            background: #fee2e2;
            border-color: #fca5a5;
            color: #b91c1c;
        }

        .action-btn.action-primary {
            color: var(--color-accent);
            border-color: #c7d2fb;
            background: var(--color-accent-light);
        }

        .action-btn.action-primary:hover {
            background: #dde4fd;
            border-color: var(--color-accent);
        }

        .action-btn.action-success {
            color: #15803d;
            border-color: #86efac;
            background: #f0fdf4;
        }

        .action-btn.action-success:hover {
            background: #dcfce7;
            border-color: #4ade80;
            color: #166534;
        }

        .action-btn.action-warning {
            color: #a16207;
            border-color: #fde68a;
            background: #fffbeb;
        }

        .action-btn.action-warning:hover {
            background: #fef3c7;
            border-color: #facc15;
            color: #854d0e;
        }

        /* ===== Detail Modal ===== */
        .detail-row {
            display: flex;
            flex-direction: column;
            padding: 12px 0;
            border-bottom: 1px solid var(--color-border);
        }

        .detail-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .detail-row:first-child {
            padding-top: 0;
        }

        .detail-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--color-text-muted);
            margin-bottom: 3px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text);
        }

        .detail-value.mono {
            font-family: 'DM Mono', monospace;
        }

        /* Specification rendered HTML in detail */
        .detail-spec {
            font-size: 13.5px;
            font-weight: 400;
            line-height: 1.7;
            color: var(--color-text);
        }

        .detail-spec p {
            margin-bottom: 6px;
        }

        .detail-spec ul,
        .detail-spec ol {
            padding-left: 20px;
            margin-bottom: 6px;
        }

        .detail-spec strong {
            font-weight: 700;
        }

        .detail-spec em {
            font-style: italic;
        }

        /* ===== Modal Style ===== */
        .modal-content {
            border-radius: 14px;
            border: none;
        }

        .modal-header {
            padding: 20px 24px 12px;
            flex-shrink: 0;
        }

        .modal-body {
            padding: 0 24px 8px;
            overflow-y: auto;
        }

        .modal-footer {
            padding: 12px 24px 20px;
            flex-shrink: 0;
        }

        .modal-title {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
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

        .modal .form-control:focus,
        .modal .form-select:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(79, 110, 247, 0.1);
        }

        .modal .form-control[readonly] {
            background-color: #f8fafc;
            color: var(--color-text-muted);
        }

        /* Keep scroll behavior stable for large forms in Bootstrap scrollable modal */
        .master-asset-modal .modal-content {
            max-height: calc(100vh - 2rem);
        }

        .master-asset-modal .modal-body {
            max-height: calc(100vh - 210px);
            overscroll-behavior: contain;
            -webkit-overflow-scrolling: touch;
        }

        /* ===== Quill Editor Custom Style ===== */
        .quill-wrapper {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            overflow: hidden;
        }

        .quill-wrapper:focus-within {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(79, 110, 247, 0.1);
        }

        .quill-wrapper .ql-toolbar {
            border: none;
            border-bottom: 1px solid var(--color-border);
            background: #f8fafc;
            padding: 8px 12px;
            font-family: 'DM Sans', sans-serif;
        }

        .quill-wrapper .ql-toolbar .ql-formats {
            margin-right: 10px;
        }

        .quill-wrapper .ql-toolbar button {
            border-radius: 4px;
        }

        .quill-wrapper .ql-toolbar button:hover,
        .quill-wrapper .ql-toolbar button.ql-active {
            background: var(--color-accent-light) !important;
            color: var(--color-accent);
        }

        .quill-wrapper .ql-toolbar .ql-stroke {
            stroke: var(--color-text-muted);
        }

        .quill-wrapper .ql-toolbar button:hover .ql-stroke,
        .quill-wrapper .ql-toolbar button.ql-active .ql-stroke {
            stroke: var(--color-accent);
        }

        .quill-wrapper .ql-toolbar .ql-fill {
            fill: var(--color-text-muted);
        }

        .quill-wrapper .ql-toolbar button:hover .ql-fill,
        .quill-wrapper .ql-toolbar button.ql-active .ql-fill {
            fill: var(--color-accent);
        }

        .quill-wrapper .ql-container {
            border: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 13.5px;
        }

        .quill-wrapper .ql-editor {
            min-height: 130px;
            max-height: 220px;
            overflow-y: auto;
            padding: 10px 14px;
            color: var(--color-text);
            line-height: 1.6;
        }

        .quill-wrapper .ql-editor.ql-blank::before {
            color: #9ca3af;
            font-style: normal;
            font-size: 13.5px;
        }

        /* Invalid state */
        .quill-wrapper.is-invalid {
            border-color: #dc2626;
        }

        .quill-wrapper.is-invalid:focus-within {
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }
    </style>
@endpush

@section('content')

    <div class="card">

        {{-- Filter Bar --}}
        <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <form method="GET" id="masterAssetFilterForm" class="d-flex align-items-center gap-2 flex-wrap m-0"
                style="flex: 1;">
                <div class="position-relative">
                    <i class="bi bi-search position-absolute"
                        style="top: 50%; left: 10px; transform: translateY(-50%); color: var(--color-text-muted); font-size: 13px; pointer-events: none;"></i>
                    <input type="search" id="searchInput" name="search" class="form-control form-control ps-5"
                        value="{{ request('search') }}" placeholder="Search kode, nama, brand..."
                        style="max-width: 260px; border-radius: 8px; font-size: 13px;">
                </div>
                <label class="small text-muted mb-0">Show</label>
                <select name="per_page" id="masterAssetPerPage" class="form-select form-select-sm"
                    style="width: 90px; border-radius: 8px;">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="small text-muted">entries</span>
            </form>
            <div style="font-size: 12px; color: var(--color-text-muted);">
                {{ $masterAssets->total() }} records
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Kode Aset</th>
                        <th>Nama Aset</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Harga Beli Standar</th>
                        <th>Umur Manfaat</th>
                        <th>Status</th>
                        <th style="width: 120px;" class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                @include('master-assets.partials.table')
            </table>
        </div>

        {{-- Pagination --}}
        @if (isset($masterAssets) && $masterAssets->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div style="font-size: 13px; color: var(--color-text-muted);">
                    Showing {{ $masterAssets->firstItem() }}-{{ $masterAssets->lastItem() }} of {{ $masterAssets->total() }}
                    master assets
                </div>
                <div class="pagination-wrap">
                    {{ $masterAssets->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif

    </div>


    {{-- ===== MODAL ADD ===== --}}
    <div class="modal fade master-asset-modal" id="modalAddMasterAsset" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">
                        <i class="bi bi-plus-circle me-2" style="color: var(--color-accent);"></i>Add New Master Asset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form method="POST" action="{{ route('master-assets.store') }}" id="formAddMasterAsset">
                    @csrf
                    <div class="modal-body" style="padding-top: 20px;">

                        <div class="row g-3">

                            {{-- Kode Aset --}}
                            <div class="col-12">
                                <label class="form-label">Kode Aset</label>
                                <input type="text" name="asset_code" value="{{ old('asset_code', $nextCode) }}"
                                    class="form-control @error('asset_code') is-invalid @enderror" readonly>
                                @error('asset_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Nama Aset --}}
                            <div class="col-12">
                                <label class="form-label">Nama Aset <span class="text-danger">*</span></label>
                                <input type="text" name="asset_name" value="{{ old('asset_name') }}"
                                    class="form-control @error('asset_name') is-invalid @enderror"
                                    placeholder="Masukkan nama aset">
                                @error('asset_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori & Brand --}}
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <input type="text" name="category" value="{{ old('category') }}"
                                    class="form-control @error('category') is-invalid @enderror"
                                    placeholder="Contoh: Laptop">
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Brand</label>
                                <input type="text" name="brand" value="{{ old('brand') }}"
                                    class="form-control @error('brand') is-invalid @enderror" placeholder="Contoh: Dell">
                                @error('brand')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Model --}}
                            <div class="col-12">
                                <label class="form-label">Model</label>
                                <input type="text" name="model" value="{{ old('model') }}"
                                    class="form-control @error('model') is-invalid @enderror"
                                    placeholder="Contoh: Latitude 5520">
                                @error('model')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Specification - Quill Editor --}}
                            <div class="col-12">
                                <label class="form-label">Spesifikasi</label>
                                {{-- Hidden input untuk menyimpan nilai HTML dari Quill --}}
                                <input type="hidden" name="specification" id="addSpecificationInput"
                                    value="{{ old('specification') }}">
                                {{-- Quill editor container --}}
                                <div class="quill-wrapper @error('specification') is-invalid @enderror">
                                    <div id="addSpecificationEditor"></div>
                                </div>
                                @error('specification')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div style="font-size: 11px; color: var(--color-text-muted); margin-top: 5px;">
                                    Gunakan toolbar untuk memformat teks, membuat daftar, dll.
                                </div>
                            </div>

                            {{-- Harga & Umur Manfaat --}}
                            <div class="col-md-6">
                                <label class="form-label">Harga Beli Standar</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                        style="border-radius: 8px 0 0 8px; font-size: 13px; background: #f8fafc; border-color: var(--color-border);">Rp</span>
                                    <input type="number" name="purchase_price" value="{{ old('purchase_price') }}"
                                        class="form-control @error('purchase_price') is-invalid @enderror" placeholder="0"
                                        style="border-radius: 0 8px 8px 0;">
                                    @error('purchase_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Umur Manfaat</label>
                                <div class="input-group">
                                    <input type="number" name="useful_life" value="{{ old('useful_life') }}"
                                        class="form-control @error('useful_life') is-invalid @enderror" placeholder="0"
                                        style="border-radius: 8px 0 0 8px;">
                                    <span class="input-group-text"
                                        style="border-radius: 0 8px 8px 0; font-size: 13px; background: #f8fafc; border-color: var(--color-border);">Tahun</span>
                                    @error('useful_life')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer border-top">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                            style="border-radius: 8px; font-size: 13px;">Cancel</button>
                        <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-size: 13px;">
                            <i class="bi bi-check-lg me-1"></i> Save Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ===== MODAL DETAIL ===== --}}
    <div class="modal fade master-asset-modal" id="modalDetailMasterAsset" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title">
                        <i class="bi bi-info-circle me-2" style="color: var(--color-accent);"></i>Detail Master Asset
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" style="padding-top: 16px; padding-bottom: 8px;">

                    {{-- Header Badge --}}
                    <div class="d-flex align-items-center gap-3 mb-4 p-3"
                        style="background: var(--color-accent-light); border-radius: 10px;">
                        <div
                            style="width: 44px; height: 44px; border-radius: 10px; background: var(--color-accent);
                                color: #fff; display: flex; align-items: center; justify-content: center;
                                font-size: 20px; flex-shrink: 0;">
                            <i class="bi bi-box-seam"></i>
                        </div>
                        <div>
                            <div style="font-size: 16px; font-weight: 700; color: var(--color-text);" id="detailName">-
                            </div>
                            <div style="font-size: 12px; font-family: 'DM Mono', monospace; color: var(--color-accent); font-weight: 600;"
                                id="detailCode">-</div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Kategori</span>
                                <span class="detail-value" id="detailCategory">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Status</span>
                                <span class="detail-value badge badge-active" id="detailStatus">-</span>
                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Brand</span>
                                <span class="detail-value" id="detailBrand">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Model</span>
                                <span class="detail-value" id="detailModel">-</span>
                            </div>
                        </div>
                    </div>

                    {{-- Specification --}}
                    <div class="detail-row mb-3">
                        <span class="detail-label">Spesifikasi</span>
                        <div class="detail-spec" id="detailSpecification">-</div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Harga Beli Standar</span>
                                <span class="detail-value mono" id="detailPrice">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Umur Manfaat</span>
                                <span class="detail-value" id="detailLife">-</span>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Dibuat Pada</span>
                                <span class="detail-value" id="detailCreated">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="detail-row">
                                <span class="detail-label">Diperbarui Pada</span>
                                <span class="detail-value" id="detailUpdated">-</span>
                            </div>
                        </div>
                    </div>





                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-light border-dark" data-bs-dismiss="modal"
                        style="border-radius: 8px; font-size: 13px;">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Quill.js --}}
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ============================================================
            // QUILL CONFIG
            // ============================================================
            const quillToolbar = [
                [{
                    header: [1, 2, 3, false]
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    list: 'ordered'
                }, {
                    list: 'bullet'
                }],
                [{
                    indent: '-1'
                }, {
                    indent: '+1'
                }],
                ['blockquote', 'code-block'],
                ['clean']
            ];

            // Quill ADD
            const quillAdd = new Quill('#addSpecificationEditor', {
                theme: 'snow',
                placeholder: 'Masukkan spesifikasi aset (processor, RAM, storage, dll.)...',
                modules: {
                    toolbar: quillToolbar
                }
            });

            // Populate dari old() jika ada (validasi error)
            const oldAddValue = document.getElementById('addSpecificationInput').value;
            if (oldAddValue) {
                quillAdd.clipboard.dangerouslyPasteHTML(oldAddValue);
            }

            // Sync ke hidden input sebelum form submit
            document.getElementById('formAddMasterAsset').addEventListener('submit', function() {
                const html = quillAdd.getSemanticHTML();
                document.getElementById('addSpecificationInput').value = (html === '<p></p>' ? '' : html);
            });

            // ============================================================
            // DETAIL MODAL
            // ============================================================
            if (false) {
                document.querySelectorAll('.btn-detail').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        const d = this.dataset;

                        document.getElementById('detailCode').innerText = d.code || '-';
                        document.getElementById('detailName').innerText = d.name || '-';
                        document.getElementById('detailCategory').innerText = d.category || '-';
                        document.getElementById('detailStatus').innerText = d.status || '-';
                        document.getElementById('detailBrand').innerText = d.brand || '-';
                        document.getElementById('detailModel').innerText = d.model || '-';
                        document.getElementById('detailPrice').innerText = d.price ?
                            'Rp ' + Number(d.price).toLocaleString('id-ID') :
                            '-';
                        document.getElementById('detailLife').innerText = d.life ? d.life +
                            ' Tahun' :
                            '-';
                        document.getElementById('detailCreated').innerText = d.created || '-';
                        document.getElementById('detailUpdated').innerText = d.updated || '-';

                        // Render HTML specification (aman karena konten dari DB sendiri)
                        const specEl = document.getElementById('detailSpecification');
                        const spec = d.specification || '';
                        specEl.innerHTML = spec.trim() ? spec :
                            '<span style="color: var(--color-text-muted);">-</span>';

                        new bootstrap.Modal(document.getElementById('modalDetailMasterAsset'))
                            .show();
                    });
                });
            }

            // ============================================================
            // LIVE SEARCH
            // ============================================================
            const filterForm = document.getElementById('masterAssetFilterForm');
            const searchInput = document.getElementById('searchInput');
            const perPageSelect = document.getElementById('masterAssetPerPage');
            let timeout = null;

            searchInput?.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => filterForm?.submit(), 350);
            });

            perPageSelect?.addEventListener('change', function() {
                filterForm?.submit();
            });

            // Re-bind setelah live search
            function bindTableEvents() {
                if (window._masterAssetTableEventsBound) return;

                document.addEventListener('click', function(e) {
                    const changeStatusBtn = e.target.closest('.btn-change-status');
                    if (changeStatusBtn) {
                        const form = changeStatusBtn.closest('.form-change-status');
                        const nextStatus = form?.querySelector('input[name="status"]')?.value;
                        const willActivate = nextStatus === '1';

                        Swal.fire({
                            title: willActivate ? 'Aktifkan master asset ini?' :
                                'Nonaktifkan master asset ini?',
                            text: willActivate ?
                                'Master asset akan berstatus aktif.' :
                                'Master asset akan berstatus nonaktif.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: willActivate ? '#16a34a' : '#d97706',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: willActivate ? 'Ya, aktifkan' : 'Ya, nonaktifkan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                        }).then((result) => {
                            if (result.isConfirmed) form?.submit();
                        });
                        return;
                    }

                    const detailBtn = e.target.closest('.btn-detail');
                    if (detailBtn) {
                        const d = detailBtn.dataset;

                        document.getElementById('detailCode').innerText = d.code || '-';
                        document.getElementById('detailName').innerText = d.name || '-';
                        document.getElementById('detailCategory').innerText = d.category || '-';
                        document.getElementById('detailStatus').innerText = d.status || '-';
                        document.getElementById('detailBrand').innerText = d.brand || '-';
                        document.getElementById('detailModel').innerText = d.model || '-';
                        document.getElementById('detailPrice').innerText = d.price ?
                            'Rp ' + Number(d.price).toLocaleString('id-ID') :
                            '-';
                        document.getElementById('detailLife').innerText = d.life ? d.life + ' Tahun' : '-';
                        document.getElementById('detailCreated').innerText = d.created || '-';
                        document.getElementById('detailUpdated').innerText = d.updated || '-';

                        const specEl = document.getElementById('detailSpecification');
                        const spec = d.specification || '';
                        specEl.innerHTML = spec.trim() ? spec :
                            '<span style="color: var(--color-text-muted);">-</span>';

                        new bootstrap.Modal(document.getElementById('modalDetailMasterAsset')).show();
                        return;
                    }
                });

                window._masterAssetTableEventsBound = true;
            }

            bindTableEvents();

            // ============================================================
            // AUTO OPEN ADD MODAL ON VALIDATION ERROR
            // ============================================================
            @if ($errors->any())
                new bootstrap.Modal(document.getElementById('modalAddMasterAsset')).show();
            @endif

        });
    </script>
@endpush
