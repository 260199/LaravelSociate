@extends('layouts.main')

@section('isi')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 ">Admin Dashboard</h1>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="actv" style="text-decoration:none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> 
                                    Tugas Harian
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $allduty }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <a href="jekegs" style="text-decoration: none;">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                     Jenis Kegiatan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $jekegs }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-primary"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <a href="users" style="text-decoration: none;">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                     User
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $usercount }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-success"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
    <div class="row">
        <!-- Chart Column -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Tugas Harian</h6>
                </div>
                <div class="card-body">
                    <!-- Filter inside chart card -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="filterTypeAdmin">Filter Tipe</label>
                            <select id="filterTypeAdmin" class="form-control">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                            </select>
                        </div>
                        <!-- Harian -->
                        <div class="col-md-6 filter-group" id="dailyInputAdmin">
                            <label for="filterDateAdmin">Pilih Tanggal</label>
                            <input type="date" id="filterDateAdmin" class="form-control">
                        </div>
                        <!-- Mingguan -->
                        <div class="col-md-6 filter-group d-none" id="weeklyInputAdmin">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="weekStartAdmin">Tanggal Mulai</label>
                                    <input type="date" id="weekStartAdmin" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="weekEndAdmin">Tanggal Akhir</label>
                                    <input type="date" id="weekEndAdmin" class="form-control">
                                </div>
                            </div>
                        </div>
                        <!-- Bulanan -->
                        <div class="col-md-6 filter-group d-none" id="monthlyInputAdmin">
                            <label for="filterMonthAdmin">Pilih Bulan</label>
                            <input type="month" id="filterMonthAdmin" class="form-control">
                        </div>
                    </div>
                    <!-- Chart -->
                    <div class="chart-bar" style="position: relative; height: 500px; width: 100%;">
                        <canvas id="adminBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- Info Boxes Column -->
        {{-- <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Info Daily</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h5>Total Daily: <span id="totalDaily">{{ $allduty }}</span></h5>
                    </div>
                    <div class="mb-3">
                        <h5>Total Daily Progress: <span id="totalDailyProgress">{{ $totalProgress }}</span></h5>
                    </div>
                    <div class="mb-3">
                        <h5>Total Daily Dilaporkan: <span id="totalDilaporkan">{{ $totalDilaporkan }}</span></h5>
                    </div>
                    <div class="mb-3">
                        <h5>Total Daily Diterima: <span id="totalDiterima">{{ $totalDiterima }}</span></h5>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Info Daily  -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Tugas Harian</h6>
                </div>
                <div class="card-body">
                    <!-- Total Daily -->
                    <div class="mb-3">
                        <h5>Total Tugas Harian: <span id="totalDaily">{{ $allduty }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allduty > 0 ? 100 : 0 }}%" 
                                aria-valuenow="{{ $allduty }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $allduty }} Items
                            </div>
                        </div>
                    </div>

                    <!-- Total Daily Progress -->
                    <div class="mb-3">
                        <h5>Tugas Harian Berlangung: <span id="totalDailyProgress">{{ $totalProgress }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allduty > 0 ? ($totalProgress / $allduty) * 100 : 0 }}%" 
                                aria-valuenow="{{ $totalProgress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $totalProgress }} Progress
                            </div>
                        </div>
                    </div>

                    <!-- Total Daily Dilaporkan -->
                    <div class="mb-3">
                        <h5>Tugas Harian Dilaporkan: <span id="totalDilaporkan">{{ $totalDilaporkan }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allduty > 0 ? ($totalDilaporkan / $allduty) * 100 : 0 }}%" 
                                aria-valuenow="{{ $totalDilaporkan }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $totalDilaporkan }} Dilaporkan
                            </div>
                        </div>
                    </div>

                    <!-- Total Daily Diterima -->
                    <div class="mb-3">
                        <h5>Tugas Harian Selesai: <span id="totalDiterima">{{ $totalDiterima }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allduty > 0 ? ($totalDiterima / $allduty) * 100 : 0 }}%" 
                                aria-valuenow="{{ $totalDiterima }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $totalDiterima }} Diterima
                            </div>
                        </div>
                    </div>
                    <!-- Total Daily Diterima -->
                    <div class="mb-3">
                        <h5>Tugas Harian Ditolak: <span id="totalDiterima">{{ $totalDitolak }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $allduty > 0 ? ($totalDitolak / $allduty) * 100 : 0 }}%" 
                                aria-valuenow="{{ $totalDitolak }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $totalDitolak }} Ditolak
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- Pusher dan Echo CDN -->
<script src="https://js.pusher.com/7.2/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: '0e6943fcf6b465eff50f', // Ganti sesuai key yang ada di .env atau broadcasting.php
    cluster: 'ap1',
    forceTLS: true
});

window.Echo.channel('daily-channel')
    .listen('.user.new', (e) => {
        console.log("🚀 Event user.new diterima:", e);
        showToast('Halo Admin, Ada Pengguna Baru,  <strong>' + e.user.name + '</strong>');
    });


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
        icon: 'success',  // Anda bisa mengganti jenis icon (misal: 'info', 'error', 'success')
        title: message  // Pesan notifikasi
    });
}

     document.addEventListener('DOMContentLoaded', function () {
        // Mengatur locale Indonesia untuk input tanggal
        const inputs = document.querySelectorAll('input[type="date"]');
        inputs.forEach(input => {
            // Set locale Indonesia untuk input
            input.setAttribute('lang', 'id');
        });
    });
    const ctxAdmin = document.getElementById('adminBarChart').getContext('2d');

    const userStats = @json($userStats);

    let adminBarChart = new Chart(ctxAdmin, {
    type: 'bar',
    data: {
        labels: userStats.map(user => user.name),
        datasets: [
            {
                label: 'Progress',
                data: userStats.map(user => user.progress),
                backgroundColor: '#edb70e',
                borderWidth: 1
            },
            {
                label: 'Dilaporkan',
                data: userStats.map(user => user.dilaporkan),
                backgroundColor: '#3498db',
                borderWidth: 1
            },
            {
                label: 'Diterima',
                data: userStats.map(user => user.diterima),
                backgroundColor: '#2ecc71',
                borderWidth: 1
            },{
                label: 'Ditolak',
                data: userStats.map(user => user.ditolak),
                backgroundColor :'#FF0000',
                borderWidth:1
            }
        ]
    },

        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Filter 
    const filterTypeAdminSelect = document.getElementById('filterTypeAdmin');
    const filterDateAdminInput = document.getElementById('filterDateAdmin');
    const weekStartAdminInput = document.getElementById('weekStartAdmin');
    const weekEndAdminInput = document.getElementById('weekEndAdmin');
    const filterMonthAdminInput = document.getElementById('filterMonthAdmin');

    const dailyInputAdmin = document.getElementById('dailyInputAdmin');
    const weeklyInputAdmin = document.getElementById('weeklyInputAdmin');
    const monthlyInputAdmin = document.getElementById('monthlyInputAdmin');

    function toggleInputsAdmin(type) {
        dailyInputAdmin.classList.add('d-none');
        weeklyInputAdmin.classList.add('d-none');
        monthlyInputAdmin.classList.add('d-none');

        if (type === 'daily') {
            dailyInputAdmin.classList.remove('d-none');
        } else if (type === 'weekly') {
            weeklyInputAdmin.classList.remove('d-none');
        } else if (type === 'monthly') {
            monthlyInputAdmin.classList.remove('d-none');
        }
    }

   function fetchAndUpdateAdminChart() {
    const type = filterTypeAdminSelect.value;
    let url = `/filter-admin?type=${type}`;

    if (type === 'daily') {
        const date = filterDateAdminInput.value;
        if (!date) return;
        url += `&date=${date}`;
    } else if (type === 'weekly') {
        const start = weekStartAdminInput.value;
        const end = weekEndAdminInput.value;
        if (!start || !end) return;
        url += `&start=${start}&end=${end}`;
    } else if (type === 'monthly') {
        const month = filterMonthAdminInput.value;
        if (!month) return;
        url += `&month=${month}`;
    }

    fetch(url)
        .then(res => res.json())
        .then(response => {
            const data = response.userStats;

            if (!data || Object.keys(data).length === 0) {
                // Lebih ramah user
                Swal.fire({
                    icon: 'info',
                    title: 'Tidak Ada Data',
                    text: 'Data tidak ditemukan untuk filter yang dipilih.'
                });
                return;
            }

            // Update chart
            adminBarChart.data.labels = Object.keys(data);
            adminBarChart.data.datasets[0].data = Object.values(data).map(user => user.progress || 0);
            adminBarChart.data.datasets[1].data = Object.values(data).map(user => user.dilaporkan || 0);
            adminBarChart.data.datasets[2].data = Object.values(data).map(user => user.diterima || 0);
            adminBarChart.data.datasets[3].data = Object.values(data).map(user => user.ditolak || 0); // Tambahan ini

            adminBarChart.update();

            // Update informasi total
            document.getElementById('totalDaily').textContent = response.totalDaily;
            document.getElementById('totalDailyProgress').textContent = response.totalProgress;
            document.getElementById('totalDilaporkan').textContent = response.totalDilaporkan;
            document.getElementById('totalDiterima').textContent = response.totalDiterima;
            document.getElementById('totalUsers').textContent = response.totalUsers;
        })
        .catch(error => {
            console.error('Error fetching data:', error);
        });
}


    filterTypeAdminSelect.addEventListener('change', () => {
        const type = filterTypeAdminSelect.value;
        toggleInputsAdmin(type);
        fetchAndUpdateAdminChart();
    });

    filterDateAdminInput.addEventListener('change', fetchAndUpdateAdminChart);
    weekStartAdminInput.addEventListener('change', fetchAndUpdateAdminChart);
    weekEndAdminInput.addEventListener('change', fetchAndUpdateAdminChart);
    filterMonthAdminInput.addEventListener('change', fetchAndUpdateAdminChart);

    toggleInputsAdmin(filterTypeAdminSelect.value);

</script>
@endsection
