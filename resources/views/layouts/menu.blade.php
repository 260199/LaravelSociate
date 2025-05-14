        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion toggled" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('storage/default/itp.png') }}" alt="logo" style="width:60%; height:60%; margin-top: -10px;" class="img-profile">
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
            @if($user->role==1)
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
                            <a class="collapse-item" style="color:black;" href="{{ url('users') }}"><i class="fas fa-user fa-sm fa-fw mr-2 text-black-400"></i> User</a>
                            <a class="collapse-item" style="color:black;" href="{{ url('actv') }}"><i class="fas fa-clipboard-list fa-sm fa-fw -mr-2 text-black-400"></i> Semua Kegiatan Aktivitas</a>
                            <a class="collapse-item" style="color:black;" href="{{ url('jekegs') }}"><i class="fas fa-cogs fa-sm fa-fw mr-2"></i>Jenis</a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider d-none d-md-block">
                @elseif($user->role==2)
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
                            <a class="collapse-item" href="{{ route('daily.index') }}"><i class="fas fa-clipboard-list fa-sm fa-fw -mr-2 text-black-400"></i> Aktivitas</a>
                        </div>
                    </div>
                </li>
                <hr class="sidebar-divider d-none d-md-block">            
            @endif

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
        
                                {{-- <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                                <li class="nav-item dropdown no-arrow d-sm-none">
                                    <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-search fa-fw"></i>
                                    </a>
                                    <!-- Dropdown - Messages -->
                                    <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                        aria-labelledby="searchDropdown">
                                        <form class="form-inline mr-auto w-100 navbar-search">
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light border-0 small"
                                                    placeholder="Search for..." aria-label="Search"
                                                    aria-describedby="basic-addon2">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="button">
                                                        <i class="fas fa-search fa-sm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </li> --}}
        
                                <!-- Nav Item - Alerts -->
                                
                                @php
                                $user = Auth::user();
                            @endphp
{{--                             
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter">{{ $notiff }}</span>
                                </a>
                            
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <h6 class="dropdown-header">
                                        Notifikasi
                                    </h6>
                                        @forelse ($notifications as $notif)
                                            <a class="dropdown-item d-flex align-items-center {{ $notif->read ? '' : 'bg-gradient-secondary' }}" 
                                               href="{{ route('notifications.read', $notif->id) }}">
                                                <div class="mr-3">
                                                    <div class="icon-circle bg-secondary">
                                                        <i class="fas fa-donate text-white"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="small text-gray-500">{{ $notif->created_at->format('d M Y H:i') }}</div>
                                                    {{ $notif->message }}
                                                </div>
                                            </a>
                                        @empty
                                            <span class="dropdown-item text-center">Tidak ada notifikasi</span>
                                        @endforelse
                                    <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                </div>
                            </li>
                             --}}

                                 <!-- Nav Item - Alerts -->
                                 <li class="nav-item dropdown no-arrow mx-1">
                                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-bell fa-fw"></i>
                                        <!-- Counter - Alerts -->
                                        <span class="badge badge-danger badge-counter">{{ $notiff }}</span>
                                    </a>
                                    
                                    <!-- Dropdown - Alerts -->
                                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown"
                                    style="max-height: 300px; overflow-y: auto;">
                                    <h6 class="dropdown-header">
                                        Notifikasi
                                    </h6>
                                        @forelse ($notifications as $notif )
                                            <a class="dropdown-item d-flex align-items-center {{ $notif->read ? '' : 'bg-gray-200' }}"
                                                href="{{ route('notifications.read', $notif->id) }}">
                                             
                                            <div class="mr-3">
                                                <div class="icon-circle bg-primary">
                                                    <i class="fas fa-clipboard-list text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-black-500">{{ $notif->created_at->translatedformat('d M Y H:i') }}</div>
                                               <span class="font-weight-bold" style="text-align: justify;">
                                                    @if(auth()->user()->role == 1)
                                                        {{ $notif->message_admin }}
                                                    @else
                                                        {{ $notif->message }}
                                                    @endif
                                                </span>

                                                 @php
                                                    $taggable = $notif->taggable;
                                                @endphp
                                                @if ($taggable)
                                                    @if ($notif->taggable_type === App\Models\Daily::class)
                                                        <p>Kegiatan : {{ $taggable->kegiatan }}</p>
                                                    @elseif ($notif->taggable_type === App\Models\User::class)
                                                        <p>Nama User: {{ $taggable->name }}</p>   
                                                    @else
                                                        {{-- <p>ID terkait: {{ $taggable->id }}</p> --}}
                                                    @endif
                                                @endif

                                            </div>
                                        </a>
                                        @empty
                                        <span class="dropdown-item text-center">Tidak ada notifikasi</span>
                                        @endforelse
                                        <hr>

                                       
                                        {{-- <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-success">
                                                    <i class="fas fa-donate text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">December 7, 2019</div>
                                                $290.29 has been deposited into your account!
                                            </div>
                                        </a>
                                        <a class="dropdown-item d-flex align-items-center" href="#">
                                            <div class="mr-3">
                                                <div class="icon-circle bg-warning">
                                                    <i class="fas fa-exclamation-triangle text-white"></i>
                                                </div>
                                            </div>
                                            <div>
                                                <div class="small text-gray-500">December 2, 2019</div>
                                                Spending Alert: We've noticed unusually high spending for your account.
                                            </div>
                                        </a> --}}
                                        <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                                    </div>
                                </li>



                                <div class="topbar-divider d-none d-sm-block"></div>
                                <!-- Nav Item - User Information -->
                                <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                    <img class="img-profile rounded-circle" src="{{ asset('storage/default/undraw_profile.svg') }}">
                                </a>
                                
                                
                                
                                    <!-- Dropdown - User Information -->
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                        aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="/profile" >
                                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Profile
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{url ('logout') }}" data-toggle="modal" data-target="#logoutModal">
                                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                            Logout
                                        </a>
                                    </div>
                                </li>
        
                            </ul>
                        </nav>
                        <!-- End of Topbar -->