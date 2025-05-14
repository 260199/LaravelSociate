@extends('layouts.main')

@section('isi')
<div class="container-fluid mb-4">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-3">
        <h1 class="h3 mb-0 text-gray-800">Aktivitas Harian</h1>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daily') }}" style="text-decoration: none;">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aktivitas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow-mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text primary">Bar Chart Aktivitas {{ $user->name }}</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="filterType">Filter Tipe</label>
                            <select id="filterType" class="form-control">
                                <option value="daily">Harian</option>
                                <option value="weekly">Mingguan</option>
                                <option value="monthly">Bulanan</option>
                            </select>
                        </div>
                        <div class="col-md-6 filter-group" id="dailyInput">
                            <label for="filterDate">Pilih Tanggal</label>
                            <input type="date" id="filterDate" class="form-control">
                        </div>
                        <div class="col-md-6 filter-group d-none" id="weeklyInput">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="weekStart">Tanggal Mulai</label>
                                    <input type="date" id="weekStart" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="weekEnd">Tanggal Akhir</label>
                                    <input type="date" id="weekEnd" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 filter-group d-none" id="monthlyInput">
                            <label for="filterMonth">Pilih Bulan</label>
                            <input type="month" id="filterMonth" class="form-control">
                        </div>
                    </div>
                    <div class="chart-bar" style="position: relative; height: 500px; width: 100%;">
                        <canvas id="userBarChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Info Aktivitas</h6>
                </div>
                <div class="card-body">
                    <!-- Total -->
                    <div class="mb-3">
                        <h5>Total: <span id="totalCount">{{ $totalCount }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-secondary" role="progressbar"
                                 style="width: {{ $totalCount > 0 ? 100 : 0 }}%"
                                 aria-valuenow="{{ $totalCount }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $totalCount }} Total
                            </div>
                        </div>
                    </div>
        
                    <!-- Progress -->
                    <div class="mb-3">
                        <h5>Berlangsung: <span id="progressCount">{{ $progressCount }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar"
                                 style="width: {{ $totalCount > 0 ? ($progressCount / $totalCount) * 100 : 0 }}%"
                                 aria-valuenow="{{ $progressCount }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $progressCount }} Progress
                            </div>
                        </div>
                    </div>
        
                    <!-- Dilaporkan -->
                    <div class="mb-3">
                        <h5>Dilaporkan: <span id="dilaporkanCount">{{ $dilaporkanCount }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-info" role="progressbar"
                                 style="width: {{ $totalCount > 0 ? ($dilaporkanCount / $totalCount) * 100 : 0 }}%"
                                 aria-valuenow="{{ $dilaporkanCount }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $dilaporkanCount }} Dilaporkan
                            </div>
                        </div>
                    </div>
        
                    <!-- Diterima -->
                    <div class="mb-3">
                        <h5>Selesai: <span id="diterimaCount">{{ $diterimaCount }}</span></h5>
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar"
                                 style="width: {{ $totalCount > 0 ? ($diterimaCount / $totalCount) * 100 : 0 }}%"
                                 aria-valuenow="{{ $diterimaCount }}" aria-valuemin="0" aria-valuemax="100">
                                {{ $diterimaCount }} Diterima
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
        showToast('Selamat Datang  <strong>' + e.user.name + '</strong>');
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


    // Ambil elemen filter
const filterTypeSelect = document.getElementById('filterType');
const filterDateInput = document.getElementById('filterDate');
const weeklyInput = document.getElementById('weeklyInput');
const monthlyInput = document.getElementById('monthlyInput');
const weekStartInput = document.getElementById('weekStart');
const weekEndInput = document.getElementById('weekEnd');
const filterMonthInput = document.getElementById('filterMonth');
const progressCountElement = document.getElementById('progressCount');
const dilaporkanCountElement = document.getElementById('dilaporkanCount');
const diterimaCountElement = document.getElementById('diterimaCount');
const totalCountElement = document.getElementById('totalCount');

// Chart Initialization
const ctx = document.getElementById('userBarChart').getContext('2d');
let userBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total', 'Progress', 'Dilaporkan', 'Diterima'],
        datasets: [
            {
                label: 'Total',
                data: [{{ $totalCount }}, 0, 0, 0], // Total count (first bar)
                backgroundColor: '#3498db',
                borderWidth: 1
            },
            {
                label: 'Progress',
                data: [0, {{ $progressCount }}, 0, 0], // Progress count (second bar)
                backgroundColor: '#f1c40f',
                borderWidth: 1
            },
            {
                label: 'Dilaporkan',
                data: [0, 0, {{ $dilaporkanCount }}, 0], // Dilaporkan count (third bar)
                backgroundColor: '#e67e22',
                borderWidth: 1
            },
            {
                label: 'Diterima',
                data: [0, 0, 0, {{ $diterimaCount }}], // Diterima count (fourth bar)
                backgroundColor: '#2ecc71',
                borderWidth: 1
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


// Fungsi untuk memperbarui data chart
function fetchAndUpdateChart() {
    const type = filterTypeSelect.value;
    let url = `/filter-daily?type=${type}`;

    // Menentukan URL berdasarkan tipe filter yang dipilih (harian, mingguan, bulanan)
    if (type === 'daily') {
        const date = filterDateInput.value;
        if (!date) return; // Pastikan tanggal dipilih
        url += `&date=${date}`;
    } else if (type === 'weekly') {
        const start = weekStartInput.value;
        const end = weekEndInput.value;
        if (!start || !end) return; // Pastikan rentang tanggal lengkap
        url += `&start=${start}&end=${end}`;
    } else if (type === 'monthly') {
        const month = filterMonthInput.value;
        if (!month) return; // Pastikan bulan dipilih
        url += `&month=${month}`;
    }

    fetch(url)
    .then(res => res.json())
    .then(data => {
        console.log(data);  // Debugging response dari server
        
        // Memperbarui data chart dengan hasil dari API
        userBarChart.data.datasets[0].data = [data.total, 0, 0, 0]; // Total
        userBarChart.data.datasets[1].data = [0, data.progress, 0, 0]; // Progress
        userBarChart.data.datasets[2].data = [0, 0, data.dilaporkan, 0]; // Dilaporkan
        userBarChart.data.datasets[3].data = [0, 0, 0, data.diterima]; // Diterima

        // Memperbarui chart visual
        userBarChart.update();

        // Memperbarui count yang ditampilkan
        progressCountElement.innerText = data.progress;
        dilaporkanCountElement.innerText = data.dilaporkan;
        diterimaCountElement.innerText = data.diterima;
        totalCountElement.innerText = data.total;
    })
    .catch(error => {
        console.error("Error fetching data:", error);
    });
}

// Listener perubahan filter
filterTypeSelect.addEventListener('change', (e) => {
    const selectedType = e.target.value;
    
    // Sembunyikan semua filter input terlebih dahulu
    filterDateInput.closest('.filter-group').classList.add('d-none');
    weeklyInput.classList.add('d-none');
    monthlyInput.classList.add('d-none');

    // Tampilkan input yang sesuai dengan tipe yang dipilih
    if (selectedType === 'daily') {
        filterDateInput.closest('.filter-group').classList.remove('d-none');
    } else if (selectedType === 'weekly') {
        weeklyInput.classList.remove('d-none');
    } else if (selectedType === 'monthly') {
        monthlyInput.classList.remove('d-none');
    }

    fetchAndUpdateChart(); // Trigger chart update
});

// Trigger perubahan filter
document.querySelectorAll('input').forEach(input => {
    input.addEventListener('change', fetchAndUpdateChart);
});

filterTypeSelect.dispatchEvent(new Event('change'));  // Trigger initial filter input display

</script>
@endsection
