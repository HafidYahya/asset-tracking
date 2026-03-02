<tbody>
    @forelse ($assets as $asset)
        <tr>
            <td class="text-muted" style="font-family: 'DM Mono', monospace; font-size: 12px;">
                {{ ($assets->firstItem() ?? 1) + $loop->index }}
            </td>
            <td>
                <span
                    style="font-family: 'DM Mono', monospace; font-size: 12px; font-weight: 600;
                             color: var(--color-accent); background: var(--color-accent-light);
                             padding: 3px 8px; border-radius: 6px;">
                    {{ $asset->masterAsset->asset_code ?? '-' }}
                </span>
            </td>
            <td style="font-weight: 500;">{{ $asset->masterAsset->asset_name ?? '-' }}</td>
            <td>{{ $asset->gps_number ?: '-' }}</td>
            <td class="text-uppercase">{{ $asset->condition }}</td>
            <td>{{ $asset->purchase_date ? \Carbon\Carbon::parse($asset->purchase_date)->format('d M Y') : '-' }}</td>
            <td>Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
            <td>{{ $asset->warranty_expired ? \Carbon\Carbon::parse($asset->warranty_expired)->format('d M Y') : '-' }}</td>
            <td class="text-end pe-4">
                <div class="d-flex gap-1 justify-content-end">
                    <button type="button" class="action-btn btn-detail" title="Detail"
                        data-id="{{ $asset->id }}"
                        data-master-id="{{ $asset->master_asset_id }}"
                        data-asset-code="{{ $asset->masterAsset->asset_code ?? '' }}"
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

                    <button type="button" class="action-btn action-primary btn-edit" title="Edit"
                        data-id="{{ $asset->id }}"
                        data-master-id="{{ $asset->master_asset_id }}"
                        data-asset-code="{{ $asset->masterAsset->asset_code ?? '' }}"
                        data-asset-name="{{ $asset->masterAsset->asset_name ?? '' }}"
                        data-imei="{{ $asset->gps_number }}"
                        data-condition="{{ $asset->condition }}"
                        data-purchase-date="{{ $asset->purchase_date }}"
                        data-purchase-price="{{ $asset->purchase_price }}"
                        data-warranty-expired="{{ $asset->warranty_expired }}"
                        data-notes="{{ $asset->notes }}">
                        <i class="bi bi-pencil"></i>
                    </button>

                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" class="form-delete" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="action-btn action-danger btn-delete" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="9" class="text-center py-5" style="color: var(--color-text-muted);">
                <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                <div style="font-size: 14px; font-weight: 500;">No asset found</div>
            </td>
        </tr>
    @endforelse
</tbody>
