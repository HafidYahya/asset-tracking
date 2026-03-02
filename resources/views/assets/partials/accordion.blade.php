@php
    $groupedAssets = $assets->groupBy('master_asset_id');
@endphp

<div class="accordion" id="assetAccordion">
    @forelse ($groupedAssets as $masterId => $assetGroup)
        @php
            $master = $assetGroup->first()->masterAsset;
            $headingId = 'heading-' . $masterId;
            $collapseId = 'collapse-' . $masterId;
            $groupKey = 'group-' . $masterId;
        @endphp
        <div class="accordion-item border-0 mb-2">
            <h2 class="accordion-header" id="{{ $headingId }}">
                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#{{ $collapseId }}" aria-expanded="false" aria-controls="{{ $collapseId }}">
                    <div class="d-flex justify-content-between align-items-center w-100 pe-3">
                        <div>
                            <div class="fw-semibold">{{ $master->asset_code ?? '-' }}</div>
                            <div class="small text-muted">{{ $master->asset_name ?? '-' }}</div>
                        </div>
                        <span class="badge bg-light text-dark">{{ $assetGroup->count() }} asset</span>
                    </div>
                </button>
            </h2>
            <div id="{{ $collapseId }}" class="accordion-collapse collapse" aria-labelledby="{{ $headingId }}"
                data-bs-parent="#assetAccordion">
                <div class="accordion-body p-0">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 border-bottom">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <label class="small text-muted mb-0">Show</label>
                            <select class="form-select form-select-sm group-entries" data-group="{{ $groupKey }}"
                                style="width:90px;">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span class="small text-muted">entries</span>
                        </div>
                        <div class="position-relative" style="max-width: 280px; width:100%;">
                            <i class="bi bi-search position-absolute"
                                style="top:50%;left:10px;transform:translateY(-50%);color:var(--color-text-muted);font-size:12px;"></i>
                            <input type="search" class="form-control form-control-sm ps-4 group-search"
                                data-group="{{ $groupKey }}" placeholder="Search di grup ini...">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover mb-0 group-table" data-group="{{ $groupKey }}">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th>Kode Aset</th>
                                    <th>IMEI</th>
                                    <th>Kondisi</th>
                                    <th>Tanggal Beli</th>
                                    <th>Harga Aktual</th>
                                    <th>Garansi</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assetGroup as $asset)
                                    @php
                                        $searchText = strtolower(
                                            trim(
                                                ($asset->asset_code ?? '') .
                                                    ' ' .
                                                    ($asset->gps_number ?? '') .
                                                    ' ' .
                                                    ($asset->condition ?? '') .
                                                    ' ' .
                                                    ($asset->notes ?? ''),
                                            ),
                                        );
                                    @endphp
                                    <tr class="group-row" data-group="{{ $groupKey }}"
                                        data-search="{{ $searchText }}">
                                        <td>1</td>
                                        <td>{{ $asset->asset_code ?: '-' }}</td>
                                        <td>{{ $asset->gps_number ?: '-' }}</td>
                                        <td>
                                            @php $cond = strtolower((string) ($asset->condition ?? '')); @endphp
                                            @if (in_array($cond, ['good', 'minor', 'major', 'broken'], true))
                                                <span
                                                    class="condition-badge {{ $cond }}">{{ strtoupper($cond) }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') : '-' }}
                                        </td>
                                        <td>Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                                        <td>{{ $asset->warranty_expired ? \Carbon\Carbon::parse($asset->warranty_expired)->format('d M Y') : '-' }}
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex gap-1 justify-content-end">
                                                <button type="button" class="action-btn btn-detail" title="Detail"
                                                    data-id="{{ $asset->id }}"
                                                    data-master-id="{{ $asset->master_asset_id }}"
                                                    data-asset-code="{{ $asset->asset_code }}"
                                                    data-master-code="{{ $asset->masterAsset->asset_code ?? '' }}"
                                                    data-asset-name="{{ $asset->masterAsset->asset_name ?? '' }}"
                                                    data-master-category="{{ $asset->masterAsset->category ?? '' }}"
                                                    data-master-brand="{{ $asset->masterAsset->brand ?? '' }}"
                                                    data-master-model="{{ $asset->masterAsset->model ?? '' }}"
                                                    data-master-spec="{{ $asset->masterAsset->specifications ?? '' }}"
                                                    data-master-purchase-price="{{ $asset->masterAsset->purchase_price ?? '' }}"
                                                    data-master-useful-life="{{ $asset->masterAsset->useful_life ?? '' }}"
                                                    data-imei="{{ $asset->gps_number }}"
                                                    data-condition="{{ $asset->condition }}"
                                                    data-purchase-date="{{ $asset->purchase_date }}"
                                                    data-purchase-price="{{ $asset->purchase_price }}"
                                                    data-warranty-expired="{{ $asset->warranty_expired }}"
                                                    data-notes="{{ $asset->notes }}"
                                                    data-created="{{ $asset->created_at?->format('d M Y, H:i') }}"
                                                    data-updated="{{ $asset->updated_at?->format('d M Y, H:i') }}">
                                                    <i class="bi bi-eye"></i>
                                                </button>

                                                <button type="button" class="action-btn action-primary btn-edit"
                                                    title="Edit" data-id="{{ $asset->id }}"
                                                    data-master-id="{{ $asset->master_asset_id }}"
                                                    data-asset-code="{{ $asset->asset_code }}"
                                                    data-master-code="{{ $asset->masterAsset->asset_code ?? '' }}"
                                                    data-asset-name="{{ $asset->masterAsset->asset_name ?? '' }}"
                                                    data-imei="{{ $asset->gps_number }}"
                                                    data-condition="{{ $asset->condition }}"
                                                    data-purchase-date="{{ $asset->purchase_date }}"
                                                    data-purchase-price="{{ $asset->purchase_price }}"
                                                    data-warranty-expired="{{ $asset->warranty_expired }}"
                                                    data-notes="{{ $asset->notes }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                <form method="POST" action="{{ route('assets.destroy', $asset) }}"
                                                    class="form-delete" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="action-btn action-danger btn-delete"
                                                        title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 p-3 border-top">
                        <div class="small text-muted group-pagination-info" data-group="{{ $groupKey }}"></div>
                        <nav>
                            <ul class="pagination pagination-sm mb-0 group-pagination"
                                data-group="{{ $groupKey }}"></ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox" style="font-size: 32px; display:block; margin-bottom:10px;"></i>
            No asset found
        </div>
    @endforelse
</div>
