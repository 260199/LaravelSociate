@extends('layouts.main')
@php
    use Illuminate\Support\Str;
@endphp
@section('isi')
<div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
   <!-- Page Heading -->
        <div class="card border-left-info shadow h-100">
            <!-- User Table -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="card shadow">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="font-weight-bold text-black m-0">Users</h6>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addUserModal">
                                Tambah Pengguna Baru
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0" style="color:black;">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th style="width:5%">#</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>cek</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $no = 1; @endphp
                                        @foreach ($users as $user)
                                            <tr style="text-align: center;">
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @if ($user->role == 1)
                                                        <span class="badge badge-success">Admin</span>
                                                    @elseif ($user->role == 2)
                                                        <span class="badge badge-primary">User</span>
                                                    @else
                                                        <span class="badge badge-warning">Other</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($user->is_password_set)
                                                        <span class="badge badge-success">Password Set</span>
                                                    @else
                                                        <span class="badge badge-danger">Password Not Set</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('users.activities', $user->id) }}" class="btn btn-info btn-sm">Lihat Aktivitas</a>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <!-- Nama -->
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="2">User</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label>Password (Optional)</label>
                        <input type="password" name="password" class="form-control">
                        <div class="invalid-feedback">
                            Password and confirmation must match.
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" id="saveUserBtn" class="btn btn-primary">Save User</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('saveUserBtn').addEventListener('click', function() {
        const form = document.getElementById('addUserForm');
        const password = form.querySelector('input[name="password"]').value;
        const passwordConfirmation = form.querySelector('input[name="password_confirmation"]').value;

        // Reset error
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));

        // Validasi minimal 6 karakter
        if (password.length > 0 && password.length < 6) {
            form.querySelector('input[name="password"]').classList.add('is-invalid');

            Swal.fire({
                icon: 'error',
                title: 'Password Too Short',
                text: 'Password must be at least 6 characters long!'
            });
            return; // stop di sini, jangan submit
        }

        // Validasi konfirmasi password
        if (password !== passwordConfirmation) {
            form.querySelector('input[name="password"]').classList.add('is-invalid');
            form.querySelector('input[name="password_confirmation"]').classList.add('is-invalid');

            Swal.fire({
                icon: 'error',
                title: 'Password Mismatch',
                text: 'Password and Confirm Password must match!'
            });
            return; // stop di sini, jangan submit
        }

        // Kalau aman semua ➔ submit form
        form.submit();
    });
</script>
@endpush
