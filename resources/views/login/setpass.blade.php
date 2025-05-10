<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Set Password - ITP</title>

    <!-- Custom fonts and styles -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,800,900" rel="stylesheet">
    <link href="boostrap/css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="{{ asset('storage/default/itp.png') }}">
    <style>
        .alert i {
            margin-right: 8px;
        }
    </style>
</head>

<body class="bg-gradient-primary">

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <div class="col-lg-6">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h1 class="h4 text-gray-900">Set Password!</h1>
                </div>

                {{-- Error Notification --}}
                @if ($errors->any())
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-triangle"></i>
                        <div>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                {{-- Session Alert --}}
                @if (session('alert'))
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="fas fa-info-circle"></i>
                        <div>
                            {{ session('alert') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('setup-password.submit') }}">
                    @csrf
                    <div class="form-group">
                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                    </div>
                    <div class="form-group mt-3">
                        <input type="password" class="form-control form-control-user" id="password_confirmation" name="password_confirmation" placeholder="Repeat Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block mt-4">Simpan Password</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('boostrap/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('boostrap/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('boostrap/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('boostrap/js/sb-admin-2.min.js') }}"></script>

</body>
</html>
