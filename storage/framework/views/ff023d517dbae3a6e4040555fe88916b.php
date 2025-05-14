<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Log Aktivitas - ITP</title>

    <link href="<?php echo e(asset ('boostrap/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php echo e(asset ('boostrap/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(asset ('boostrap/css/sb-admin-2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset ('boostrap/vendor/datatables/dataTables.bootstrap4.min.css')); ?>" rel="stylesheet">

    


</head>
<body id="page-top" style="color:rgb(0, 0, 0);">
    <!-- Page Wrapper -->
    <div id="wrapper">
<?php echo $__env->make('layouts.menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->yieldContent('isi'); ?>
<?php echo $__env->make('layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

       

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
                    <a class="btn btn-primary" href="<?php echo e(url('logout')); ?>">Logout</a>
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
                        <h6 class="m-0 font-weight-bold text-primary">Profile <?php echo e(Auth::user()->name); ?></h6>
                    </div>
                <div class="card-body">
                    <h5 class="modal-title">Nama : <?php echo e(Auth::user()->name); ?></h5>
                    <?php if(Auth::user()->role == 1): ?>
                        <h5 class="modal-title">Akses : Admin</h5>
                    
                    <?php elseif(Auth::user()->role == 2): ?>
                        <h5 class="modal-title">Akses : User</h5>
                        
                    <?php endif; ?>
                   
                    <h5 class="modal-title">Email : <?php echo e(Auth::user()->email); ?></h5>
                    <?php
                    $profile = Auth::user()->profile ?? '';
                    $photo = filter_var($profile, FILTER_VALIDATE_URL)
                        ? $profile
                        : asset('storage' . $profile);
                    ?>
                    <img class="img-profile rounded-circle" src="<?php echo e($photo); ?>" style="max-width: 50%; max-weight:50%;">
                    <div class="modal-footer">
                        <a href="<?php echo e(url('logout')); ?>" class="btn btn-light btn-icon-split">
                            <span class="icon text-gray-600">
                                <i class="fas fa-arrow-right"></i>
                            </span>
                            <span class="text">Logout</span>
                        </a>
                        <a href="<?php echo e(url('logout')); ?>" class="btn btn-light btn-icon-split" onclick="return false;" >
                            <span class="icon text-gray-600">
                                <i class="fas fa-arrow-right"> Reset Password(Cooming Soon)</i>
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>


   
      <?php echo $__env->make('layouts.bottom', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php echo $__env->yieldPushContent('scripts'); ?>
      <script>
        window.currentUserId = <?php echo e(Auth::check() ? Auth::id() : 'null'); ?>;
    </script>
    
    <script>
        // Inisialisasi Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '0e6943fcf6b465eff50f',
            cluster: 'ap1',
            forceTLS: true
        });
    
        window.Echo.channel('daily-channel')
            .listen('.daily.created', (e) => {
                if (e.daily.user.id !== window.currentUserId) {
                    console.log("🚀 Event daily.created diterima:", e);
                    showToast('Aktivitas baru ditambahkan oleh <strong>' + e.daily.user.name + '</strong>');
                }
            })
            .listen('.daily.updated', (e) => {
                if (e.daily.user.id !== window.currentUserId) {
                    console.log("🚀 Event daily.updated diterima:", e);
                    showToast('Aktivitas diperbarui oleh <strong>' + e.daily.user.name + '</strong>');
                }
            })
            .listen('.daily.deleted', (e) => {
                if (e.daily.user.id !== window.currentUserId) {
                    console.log("🚀 Event daily.deleted diterima:", e);
                    showToast('Aktivitas dihapus oleh <strong>' + e.daily.user.name + '</strong>');
                }
            })
            .listen('.daily.report', (e) => {
                if (e.daily.user.id !== window.currentUserId) {
                    console.log("🚀 Event daily.report diterima:", e);
                    showToast(e.daily.user.name + ' Melaporkan Kegiatan Aktivitas Harian!');
                }
            })
            .listen('.daily.approve', (e) => {
                if (e.daily.user.id !== window.currentUserId) {
                    console.log("🚀 Event daily.report diterima:", e);
                    showToast(e.daily.user.name + ' Kegiatan Harian Kamu Telah Diterima Atasan!');
                }
            })
            
            ;
    
        // Fungsi untuk menampilkan toast menggunakan SweetAlert2
        function showToast(message) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
    
            Toast.fire({
                icon: 'success',
                title: message
            });
        }
    
        function confirmDelete(url) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus dan tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.action = url;
                    form.method = 'POST';
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                        <input type="hidden" name="_method" value="DELETE">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    
        <?php if(session('success')): ?>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '<?php echo e(session('success')); ?>',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        <?php endif; ?>
    </script>
    </body>
</html><?php /**PATH D:\Laravel\ITP\c\bismillah\resources\views/layouts/main.blade.php ENDPATH**/ ?>