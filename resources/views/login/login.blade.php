<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>SB Admin 2 - Login</title>

        <!-- Custom fonts for this template-->
        <link href="{{ asset('boostrap/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"rel="stylesheet">
        <!-- Custom styles for this template-->
        <link href="{{ asset('boostrap/css/sb-admin-2.min.css') }}" rel="stylesheet">
    </head>
<body class="bg-gradient-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-11 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                            <div class="card-body">
                                @if (session()->has('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                </div>
                                @endif
                                <br>
                                <p class="login-box-msg">Silahkan Login!!</p>
                                <form action="{{ url('login/proses') }}" method="post">
                                    @csrf
                                    <div class="input-group mb-3">
                                    <input type="text" class="form-control
                                    @error('username')
                                    is-invalid
                                    @enderror
                                    " placeholder="username" name="username" autofocus disabled>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                        <span class="fas fa-user "></span>
                                        </div>
                                    </div>
                                    @error('username')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    </div>

                                    <div class="input-group mb-3">
                                    <input type="password" class="form-control
                                    @error('password')
                                    is-invalid
                                    @enderror
                                    " placeholder="Password" name="password" disabled>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                    </div>
                                    <div class="row">
                                    <!-- /.col -->
                                    <div class="col-12 mt-6 mb-6">
                                        <button type="submit" class="btn btn-primary btn-block" disabled>Sign In</button>
                                        <a href="{{ route('redirect') }}" class="btn btn-primary btn-block">Login With Google</a>
                                    </div>
                                    <!-- /.col -->
                                    </div>
                                </form>

                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('boostrap/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('boostrap/vendor/bootstrap/js/bootstra p.bundle.min.js') }}"></script>
        <!-- Core plugin JavaScript-->
        <script src="{{ asset('boostrap/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <!-- Custom scripts for all pages-->
        <script src="{{ asset('boostrap/js/sb-admin-2.min.js') }}"></script>
    </body>
</html>
