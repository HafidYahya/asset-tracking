@extends('layouts.admin')

@section('title', 'Users')

@section('breadcrumb')
    <li class="breadcrumb-item active">Users</li>
@endsection

@section('page-title', 'Users')
@section('page-subtitle', 'Kelola semua pengguna terdaftar.')

@section('page-actions')
    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddUser"
        style="border-radius: 8px; font-size: 13px;">
        <i class="bi bi-plus-lg me-1"></i> Add User
    </button>
@endsection

@push('styles')
    <style>
        /* ===== Custom Pagination ===== */
        .users-pagination nav {
            display: flex;
            justify-content: flex-end;
        }

        .users-pagination .pagination {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .users-pagination .page-item .page-link {
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
            background-color: #ffffff;
            box-shadow: none;
            line-height: 1;
            transition: background-color 0.15s, color 0.15s, border-color 0.15s;
        }

        .users-pagination .page-item .page-link:hover {
            background-color: var(--color-accent-light);
            color: var(--color-accent);
            border-color: #c7d2fb;
        }

        .users-pagination .page-item.active .page-link {
            background-color: var(--color-accent);
            border-color: var(--color-accent);
            color: #ffffff;
            font-weight: 600;
        }

        .users-pagination .page-item.disabled .page-link {
            background-color: #f8fafc;
            color: #cbd5e1;
            border-color: var(--color-border);
            cursor: not-allowed;
            pointer-events: none;
        }

        /* Hide default Bootstrap span text for prev/next, keep icons readable */
        .users-pagination .page-item:first-child .page-link,
        .users-pagination .page-item:last-child .page-link {
            font-size: 14px;
        }
    </style>
@endpush

@section('content')

    <div class="card">

        {{-- Filter Bar --}}
        <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <form method="GET" id="usersFilterForm" class="d-flex align-items-center gap-2 flex-wrap m-0">
                <input type="search" id="searchInput" name="search" class="form-control form-control"
                    placeholder="Search name or email..." value="{{ request('search') }}"
                    style="max-width: 290px; border-radius: 8px; font-size: 13px;">
                <label class="small text-muted mb-0">Show</label>
                <select name="per_page" id="usersPerPage" class="form-select form-select-sm"
                    style="width: 90px; border-radius: 8px;">
                    @foreach ([10, 25, 50, 100] as $size)
                        <option value="{{ $size }}" @selected((int) request('per_page', 10) === $size)>
                            {{ $size }}
                        </option>
                    @endforeach
                </select>
                <span class="small text-muted">entries</span>
            </form>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover mb-0 text-nowrap">
                <thead>
                    <tr>
                        <th style="width: 48px;">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                @include('users.partials.table')
            </table>
        </div>

        {{-- Modal Add User --}}
        <div class="modal fade" id="modalAddUser" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px;">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Add New User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST" action="{{ route('users.store') }}">
                        @csrf

                        <div class="modal-body pt-2">

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name') }}"
                                    class="form-control @error('name') is-invalid @enderror">

                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="form-control @error('email') is-invalid @enderror">

                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div class="mb-1">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Save User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit --}}
        <div class="modal fade" id="modalEditUser" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="border-radius:16px;">

                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-semibold">Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form method="POST" id="formEditUser">
                        @csrf
                        @method('PUT')

                        <div class="modal-body pt-2">

                            {{-- Name --}}
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" id="editName"
                                    class="form-control @error('name') is-invalid @enderror">

                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" id="editEmail"
                                    class="form-control @error('email') is-invalid @enderror">

                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div class="mb-3">
                                <label class="form-label">
                                    Password <small class="text-muted">(Kosongkan jika tidak diubah)</small>
                                </label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">

                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Confirm --}}
                            <div class="mb-1">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                        </div>

                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>

                            <button type="submit" class="btn btn-primary">
                                Update User
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- Pagination --}}
        @if (isset($users) && $users->hasPages())
            <div class="card-footer d-flex align-items-center justify-content-between flex-wrap gap-2">
                <div style="font-size: 13px; color: var(--color-text-muted);">
                    Showing {{ $users->firstItem() }}-{{ $users->lastItem() }} of {{ $users->total() }} users
                </div>
                <div class="users-pagination">
                    {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.btn-edit').forEach(function(button) {

                button.addEventListener('click', function() {

                    let id = this.dataset.id;
                    let name = this.dataset.name;
                    let email = this.dataset.email;

                    // isi input
                    document.getElementById('editName').value = name;
                    document.getElementById('editEmail').value = email;

                    // set action form
                    document.getElementById('formEditUser')
                        .setAttribute('action', `/users/${id}`);

                    let modal = new bootstrap.Modal(
                        document.getElementById('modalEditUser')
                    );

                    modal.show();
                });

            });

        });
    </script>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var modal = new bootstrap.Modal(document.getElementById('modalAddUser'));
                modal.show();
            });
        </script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterForm = document.getElementById('usersFilterForm');
            const searchInput = document.getElementById('searchInput');
            const perPageSelect = document.getElementById('usersPerPage');
            let timeout = null;

            searchInput?.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => filterForm?.submit(), 350);
            });

            perPageSelect?.addEventListener('change', function() {
                filterForm?.submit();
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.btn-delete').forEach(function(button) {
                button.addEventListener('click', function(e) {

                    let form = this.closest('.form-delete');

                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });

                });
            });

        });
    </script>
@endpush
