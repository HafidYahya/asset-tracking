<tbody>
    @forelse ($users as $user)
        <tr>
            <td class="text-muted" style="font-family: 'DM Mono', monospace; font-size: 12px;">
                {{ ($users->firstItem() ?? 1) + $loop->index }}
            </td>
            <td>
                <div class="d-flex align-items-center gap-2">
                    <div
                        style="width: 30px; height: 30px; border-radius: 8px;
                                                background: var(--color-accent-light); color: var(--color-accent);
                                                display: flex; align-items: center; justify-content: center;
                                                font-size: 11px; font-weight: 700; flex-shrink: 0;">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <span style="font-weight: 500;">{{ $user->name }}</span>
                </div>
            </td>
            <td style="color: var(--color-text-muted);">{{ $user->email }}</td>
            <td class="text-end pe-4">
                <div class="d-flex gap-1 justify-content-end">
                    <a href="#" class="nav-icon-btn shadow border me-2 btn-edit" style="font-size: 15px;"
                        data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-email="{{ $user->email }}"
                        title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="nav-icon-btn shadow border me-2 bg-danger text-white btn-delete"
                            style="font-size: 15px;" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="text-center py-5" style="color: var(--color-text-muted);">
                <i class="bi bi-inbox" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                No users found.
            </td>
        </tr>
    @endforelse
</tbody>
