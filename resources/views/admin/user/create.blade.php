@extends('layouts.main')
@section('isi')
<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow rounded">
                <div class="card-body">
                    <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            <h3 class="card-title">User </h3>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-5 mb-3 mb-sm-0">
                                <label class="font-weight-bold">Name</label>
                                <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" placeholder="Nama Kamu" required>
                                <!-- error message untuk title -->
                                @error('name')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="font-weight-bold">Username</label>
                                <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" placeholder="Username Kamu" required>
                                <!-- error message untuk title -->
                                @error('username')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-sm-3">
                                <label class="font-weight-bold">Email</label>
                                <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" placeholder="Email kamu" required>
                                <!-- error message untuk title -->
                                @error('email')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-sm-4 mb-3 mb-sm-0">
                                <label for="role"> Role  </label>
                                    <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                                        <option value="">--||--</option>

                                        <option value="1"> Admin</option>
                                        <option value="2"> Pegwai </option>

                                    </select>
                                    @error('role')
                                    <div class="invalid-feedback">
                                        @php
                                        $message = ' Data Telah Diinputkan sebelumnya';
                                        @endphp
                                        {{ $message }}
                                    </div>
                                    @enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="font-weight-bold">Password </label>
                                <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" placeholder="password " required>
                                <!-- error message untuk title -->
                                @error('password')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-sm-4">
                                <label class="font-weight-bold">Password </label>
                                <input type="password" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder="password_confirmation" required>
                                <!-- error message untuk title -->
                                @error('password_confirmation')
                                <div class="alert alert-danger mt-2">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-icon-split">
                            <span class="icon text-white-150">
                                <i class="fa-solid fa-floppy-disk fa-2xl" style="color: #412540;"></i>
                            </span>
                            <span class="text">Simpan</span>
                        </button>
                    </form>
                    <hr>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
