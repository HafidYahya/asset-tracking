<tbody>
    @forelse ($masterAssets as $master)
        <tr>
            <td class="text-muted" style="font-family: 'DM Mono', monospace; font-size: 12px;">
                {{ ($masterAssets->firstItem() ?? 1) + $loop->index }}
            </td>

            <td>
                <span
                    style="font-family: 'DM Mono', monospace; font-size: 12px; font-weight: 600;
                             color: var(--color-accent); background: var(--color-accent-light);
                             padding: 3px 8px; border-radius: 6px;">
                    {{ $master->asset_code }}
                </span>
            </td>

            <td style="font-weight: 500;">{{ $master->asset_name }}</td>

            <td>
                @if ($master->category)
                    <span class="badge-status badge-inactive">{{ $master->category }}</span>
                @else
                    <span style="color: var(--color-text-muted); font-size: 12px;">—</span>
                @endif
            </td>

            <td style="color: var(--color-text-muted);">
                {{ $master->brand ?: '—' }}
            </td>

            <td style="color: var(--color-text-muted);">
                {{ $master->model ?: '—' }}
            </td>
            <td style="color: var(--color-text-muted);">
                {{ number_format($master->purchase_price, 0, ',', '.') ?: '—' }}
            </td>
            <td style="color: var(--color-text-muted);">
                {{ $master->useful_life ?: '—' }} Tahun
            </td>
            <td style="color: var(--color-text-muted);">
                @if ((int) $master->status === 1)
                    <span class="badge-status badge-active">Aktif</span>
                @else
                    <span class="badge-status badge-inactive">Nonaktif</span>
                @endif
            </td>

            <td class="text-end pe-4">
                <div class="d-flex gap-1 justify-content-end">

                    {{-- CHANGE STATUS --}}
                    <form method="POST" action="{{ route('master-assets.change-status', $master) }}"
                        class="form-change-status" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        @if ((int) $master->status === 1)
                            <input type="hidden" name="status" value="0">
                            <button type="button" class="action-btn action-warning btn-change-status"
                                title="Nonaktifkan">
                                <i class="bi bi-toggle-off"></i>
                            </button>
                        @else
                            <input type="hidden" name="status" value="1">
                            <button type="button" class="action-btn action-success btn-change-status" title="Aktifkan">
                                <i class="bi bi-toggle-on"></i>
                            </button>
                        @endif
                    </form>

                    {{-- DETAIL --}}
                    <button type="button" class="action-btn btn-detail" title="Detail" data-id="{{ $master->id }}"
                        data-code="{{ $master->asset_code }}" data-name="{{ $master->asset_name }}"
                        data-category="{{ $master->category }}"
                        data-status="{{ (int) $master->status === 1 ? 'Aktif' : 'Nonaktif' }}"
                        data-brand="{{ $master->brand }}" data-model="{{ $master->model }}"
                        data-specification="{{ $master->specifications }}" data-price="{{ $master->purchase_price }}"
                        data-life="{{ $master->useful_life }}"
                        data-created="{{ $master->created_at?->format('d M Y, H:i') }}"
                        data-updated="{{ $master->updated_at?->format('d M Y, H:i') }}">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </td>
        </tr>

    @empty
        <tr>
            <td colspan="10" class="text-center py-5" style="color: var(--color-text-muted);">
                <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 10px; opacity: 0.4;"></i>
                <div style="font-size: 14px; font-weight: 500;">No master asset found</div>
                <div style="font-size: 12px; margin-top: 4px; opacity: 0.7;">Coba ubah kata kunci pencarian</div>
            </td>
        </tr>
    @endforelse
</tbody>
