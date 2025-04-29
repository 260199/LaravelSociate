@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Profile</h1>
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        @php
                        use Illuminate\Support\Str;
                        $profile = $user->profile;
                        if ($profile) {
                            if (Str::startsWith($profile, ['http://', 'https://'])) {
                                $photo = $profile;
                            } else {
                                $photo = asset('storage/' . $profile);
                            }
                        } else {
                            $photo = asset('default-profile.png');
                        }
                    @endphp
                    <img class="img-profile rounded-circle shadow"
                        src="{{ $photo }}?v={{ $user->updated_at->timestamp }}"
                        alt="Profile Picture"
                        style="width: 180px; height: 180px; object-fit: cover;">

                        <div class="col-md-8">
                            <div class="mb-3">
                                <h4 class="font-weight-bold">{{ $user->name }}</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i class="fas fa-envelope text-primary"></i>
                                    <strong class="ml-2">Email:</strong>
                                    <span class="text-muted ml-1">{{ $user->email }}</span>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-user-tag text-success"></i>
                                    <strong class="ml-2">Role:</strong>
                                    <span class="ml-1">
                                        @if($user->role == 1)
                                            <span class="badge badge-primary">Admin</span>
                                        @else
                                            <span class="badge badge-success">User</span>
                                        @endif
                                    </span>
                                </li>
                                <li class="mb-2">
                                    <i class="fas fa-key text-warning"></i>
                                    <strong class="ml-2">Password Status:</strong>
                                    <span class="text-muted ml-1">
                                        {{ $user->is_password_set ? 'Sudah Diset' : 'Belum Diset' }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <hr>

                   <!-- Tombol Ganti Password dan Edit Profil sejajar -->
                    <div class="d-flex justify-content-center mb-4">
                        <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fas fa-key"></i> Ganti Password
                        </button>
                        
                        <button type="button" class="btn btn-success ml-2" data-toggle="modal" data-target="#editProfileModal">
                            <i class="fas fa-user-edit"></i> Edit Profil
                        </button>
                    </div>


                </div>
            </div>

        </div>

    </div>

    {{-- Modal Ganti Password --}}
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.change-password') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changePasswordModalLabel">Ganti Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <!-- Password Lama -->
                        <div class="form-group">
                            <label for="current_password">Password Lama</label>
                            <input type="password" name="current_password" id="current_password" class="form-control" required>
                        </div>

                        <!-- Password Baru -->
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <input type="password" name="new_password" id="new_password" class="form-control" required>
                        </div>

                        <!-- Konfirmasi Password Baru -->
                        <div class="form-group">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Profile -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProfileModalLabel">Edit Profil</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    
                    <div class="modal-body">

                        <div class="form-group text-center">
                            <img src="{{ asset($user->profile) }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                        </div>

                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" name="name" id="name" class="form-control"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Alamat Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="form-group">
                            <label for="profile_picture">Foto Profil (Opsional)</label>
                            <input type="file" name="profile_picture" id="profile_picture" class="form-control-file">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

{{-- Password Confirmation Validation --}}
<script>
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('new_password_confirmation');

    function validatePassword() {
        if (newPassword.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("Password konfirmasi tidak cocok.");
        } else {
            confirmPassword.setCustomValidity("");
        }
    }

    newPassword.addEventListener('change', validatePassword);
    confirmPassword.addEventListener('keyup', validatePassword);
</script>

@endsection
