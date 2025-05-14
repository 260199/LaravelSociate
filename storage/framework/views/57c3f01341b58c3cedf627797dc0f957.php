        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <img src="<?php echo e(asset('storage/default/itp.png')); ?>" alt="logo" style="width:60%; height:60%; margin-top: -10px;" class="img-profile">
                </div>
                <div class="sidebar-brand-text mx-3">Log Aktivitas</div>
            </a>
            <!-- Divider -->
            <hr class="sidebar-divider my-0">
            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            
            <!-- Nav Item - Pages Collapse Menu -->
            <?php if($user->role==1): ?>
                <div class="sidebar-heading">
                    Log Aktivitas
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Aktivitas</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">User Aktivitas:</h6>
                            <a class="collapse-item" style="color:black;" href="<?php echo e(url('users')); ?>"><i class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></i> User</a>
                            <a class="collapse-item" style="color:black;" href="<?php echo e(url('actv')); ?>"><i class="fas fa-clipboard-list fa-sm fa-fw -mr-2 text-black-400"></i> Semua Kegiatan Aktivitas</a>
                            <a class="collapse-item" style="color:black;" href="<?php echo e(url('jekegs')); ?>"><i class="fas fa-cogs fa-sm fa-fw mr-2"></i>Jenis</a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider d-none d-md-block">
                <?php elseif($user->role==2): ?>
                <div class="sidebar-heading">
                    Aktivitas
                </div>
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>Aktivitas</span>
                    </a>
                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Aktivitas Harian</h6>
                            <a class="collapse-item" href="<?php echo e(route('daily.index')); ?>"><i class="fas fa-clipboard-list fa-sm fa-fw -mr-2 text-black-400"></i> Aktivitas</a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider d-none d-md-block">            
            <?php endif; ?>

            <!-- Divider -->
           
            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">
                    <!-- Main Content -->
                    <div id="content">
                        <!-- Topbar -->
                        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                            <!-- Sidebar Toggle (Topbar) -->
                            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                                <i class="fa fa-bars"></i>
                            </button>
                            <!-- Topbar Search -->
                            
                            <form
                                class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group">
                                   <span class="mr-2 d-none d-lg-inline text-gray-600 small">Log Aktivitas</span>
                                </div>
                            </form>

                            <!-- Topbar Navbar -->
                            <ul class="navbar-nav ml-auto">
        
                                
        
                                <!-- Nav Item - Alerts -->
                                
                                <?php
                                $user = Auth::user();
                            ?>


                                 <!-- Nav Item - Alerts -->
                                 <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bell fa-fw"></i>
                                        <!-- Counter - Alerts -->
                                        <span class="badge badge-danger badge-counter"><?php echo e($notiff); ?></span>
                                    </a>
                                    
                                    <!-- Dropdown - Alerts -->
                                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <h6 class="dropdown-header">
                                        Notifikasi
                                    </h6>
                                        <?php $__empty_1 = true; $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notif): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                            <a class="dropdown-item d-flex align-items-center <?php echo e($notif->read ? '' : 'bg-gray-200'); ?>"
                                                href="<?php echo e(route('notifications.read', $notif->id)); ?>">
                                             
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-clipboard-list text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-black-500"><?php echo e($notif->created_at->translatedformat('d M Y H:i')); ?></div>
                                               <span class="font-weight-bold" style="text-align: justify;">
                                                    <?php if(auth()->user()->role == 1): ?>
                                                        <?php echo e($notif->message_admin); ?>

                                                    <?php else: ?>
                                                        <?php echo e($notif->message); ?>

                                                    <?php endif; ?>
                                                </span>

                                                 <?php
                                                    $taggable = $notif->taggable;
                                                ?>
                                                <?php if($taggable): ?>
                                                    <?php if($notif->taggable_type === App\Models\Daily::class): ?>
                                                        <p>Kegiatan : <?php echo e($taggable->kegiatan); ?></p>
                                                    <?php elseif($notif->taggable_type === App\Models\User::class): ?>
                                                        <p>Nama User: <?php echo e($taggable->name); ?></p>   
                                                    <?php else: ?>
                                                        
                                                    <?php endif; ?>
                                                <?php endif; ?>

                                            </div>
                                        </a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                        <span class="dropdown-item text-center">Tidak ada notifikasi</span>
                                        <?php endif; ?>
                                        <hr>

                                       
                                        
                                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                    </div>
                                </li>



                                <div class="topbar-divider d-none d-sm-block"></div>
                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo e(Auth::user()->name); ?></span>
                                    <img class="img-profile rounded-circle" src="<?php echo e(asset('storage/default/undraw_profile.svg')); ?>">
                                </a>
                                
                                
                                
                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                        aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="/profile" >
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Profile
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo e(url ('logout')); ?>" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Logout
                                        </a>
                                    </div>
                                </li>
        
                            </ul>
                        </nav>
                        <!-- End of Topbar --><?php /**PATH D:\Laravel\ITP\c\bismillah\resources\views/layouts/menu.blade.php ENDPATH**/ ?>