<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Dashboard</title>

    <link href="{{ asset ('boostrap/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset ('boostrap/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset ('boostrap/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <link href="{{ asset ('boostrap/vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">


</head>
<body id="page-top" style="color:rgb(0, 0, 0);">
    <!-- Page Wrapper -->
    <div id="wrapper">
@include('layouts.menu')
@yield('isi')
@include('layouts.footer')

       

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="showProfile" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Basic Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Profile {{ Auth::user()->name }}</h6>
                    </div>
                <div class="card-body">
                    <h5 class="modal-title">Nama : {{ Auth::user()->name }}</h5>
                    @if(Auth::user()->role == 1)
                        <h5 class="modal-title">Akses : Admin</h5>
                    
                    @elseif(Auth::user()->role == 2)
                        <h5 class="modal-title">Akses : User</h5>
                        
                    @endif
                   
                    <h5 class="modal-title">Email : {{ Auth::user()->email }}</h5>
                    @php
                    $profile = Auth::user()->profile ?? '';
                    $photo = filter_var($profile, FILTER_VALIDATE_URL)
                        ? $profile
                        : asset('storage' . $profile);
                    @endphp
                    <img class="img-profile rounded-circle" src="{{ $photo }}" style="max-width: 50%; max-weight:50%;">
                    <div class="modal-footer">
                        <a href="{{ url('logout') }}" class="btn btn-light btn-icon-split">
                            <span class="icon text-gray-600">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text">Logout</span>
                        </a>
                        <a href="{{ url('logout') }}" class="btn btn-light btn-icon-split" onclick="return false;" >
                            <span class="icon text-gray-600">
                                <i class="fas fa-arrow-right"> Reset Password(Cooming Soon)</i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


   
      @include('layouts.bottom')
      @stack('scripts')
{{-- // function addUploadField(id) {
//     const container = document.getElementById('imageUploadContainer-' + id);

//     // Wrapper untuk semua elemen baru (agar bisa dihapus sekaligus)
//     const wrapper = document.createElement('div');
//     wrapper.classList.add('upload-wrapper', 'mb-2');

//     // Input file
//     const inputGroupFile = document.createElement('div');
//     inputGroupFile.classList.add('input-group', 'mb-2');

//     const inputFile = document.createElement('input');
//     inputFile.type = 'file';
//     inputFile.name = 'images[]';
//     inputFile.classList.add('form-control');
//     inputFile.accept = 'image/*';

//     inputGroupFile.appendChild(inputFile);

//     // Input deskripsi
//     const inputGroupDesc = document.createElement('div');
//     inputGroupDesc.classList.add('input-group', 'mb-2');

//     const inputDesc = document.createElement('input');
//     inputDesc.type = 'text';
//     inputDesc.name = 'desc[]';
//     inputDesc.placeholder = 'Deskripsi foto';
//     inputDesc.classList.add('form-control');
//     inputDesc.required = true;

//     inputGroupDesc.appendChild(inputDesc);

//     // Tombol hapus
//     const removeBtn = document.createElement('button');
//     removeBtn.type = 'button';
//     removeBtn.classList.add('btn', 'btn-danger', 'btn-sm', 'mt-1');
//     removeBtn.textContent = 'Hapus';
//     removeBtn.onclick = function () {
//         container.removeChild(wrapper);
//     };

//     // Gabungkan semua ke wrapper
//     wrapper.appendChild(inputGroupFile);
//     wrapper.appendChild(inputGroupDesc);
//     wrapper.appendChild(removeBtn);

//     container.appendChild(wrapper);
// } --}}
</body>
</html>