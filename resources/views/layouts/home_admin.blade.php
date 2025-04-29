@extends('layouts.main')

@section('isi')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 ">Admin Dashboard</h1>
    </div>    
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="daall" style="text-decoration:none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1"> Daily
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $allduty }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daprog') }}" style="text-decoration: none;">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Daily(Progress)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notclear }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('dadone') }}" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Daily(Done)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $done }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-tasks fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <a href="users" style="text-decoration: none;">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    User</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usercount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-info"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-3">
            <h1 class="h3 mb-0 ">You're Activity</h1>
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ url('actv') }}" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    You're Daily</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyuser }}</div>
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
                <a href="{{ url('actvprog') }}" style="text-decoration: none;">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        Daily(Progress)</div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countusernotyets }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-calendar fa-2x text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <a href="{{ url('actvdone') }}" style="text-decoration: none;">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Daily(Done)
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $countuserdone }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-tasks fa-2x text-success"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="filterTypeAdmin">Filter Tipe</label>
            <select id="filterTypeAdmin" class="form-control">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>
    
        {{-- Harian --}}
        <div class="col-md-3 filter-group" id="dailyInputAdmin">
            <label for="filterDateAdmin">Pilih Tanggal</label>
            <input type="date" id="filterDateAdmin" class="form-control">
        </div>
    
        {{-- Mingguan --}}
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
    
        {{-- Bulanan --}}
        <div class="col-md-3 filter-group d-none" id="monthlyInputAdmin">
            <label for="filterMonthAdmin">Pilih Bulan</label>
            <input type="month" id="filterMonthAdmin" class="form-control">
        </div>
    </div>
    {{-- Chart --}}

    <div class="container">
        <div class="mb-4 ml-3" style="text-align: center;">
            <h1>BAR CHART</h1>
        </div>
        <div class="d-flex justify-content-between align-items-start">
            <div style="position: relative; width: 75%; height: 60vh;">
                <canvas id="adminBarChart"></canvas>
            </div>
            {{-- Info Section --}}
            <div class="info-container" style="width: 20%; padding: 20px;">
                <div class="info-box">
                    <h5>Total Users: <span id="totalUsers">{{ count($userStats) }}</span></h5>
                </div>
                <div class="info-box">
                    <h5>Total Daily Progress: <span id="totalDailyProgress">{{ $totalProgress }}</span></h5>
                </div>
                <div class="info-box">
                    <h5>Total Selesai: <span id="totalSelesai">{{ $totalSelesai }}</span></h5>
                </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxAdmin = document.getElementById('adminBarChart').getContext('2d');

    const userStats = @json($userStats);

    let adminBarChart = new Chart(ctxAdmin, {
        type: 'bar',
        data: {
            labels: userStats.map(user => user.name), //ini untuk nama user di sumbu bawah
            datasets: [
                {
                    label: 'Progress', // Label untuk diatas bar
                    data: userStats.map(user => user.progress), //pengambilan berdasarkan status progress
                    backgroundColor: '#edb70e', // Warna untik background
                    borderWidth: 1
                },
                {
                    label: 'Selesai', 
                    data: userStats.map(user => user.selesai), 
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
        .then(data => {
            console.log(data); 
            // Cek
            if (!data || Object.keys(data).length === 0) {
                alert('Data tidak ditemukan');
                return;
            }
            // ini untuk realtime filternya
            adminBarChart.data.labels = Object.keys(data); 

            adminBarChart.data.datasets[0].data = Object.values(data).map(user => user.progress || 0); // Progress per user
            adminBarChart.data.datasets[1].data = Object.values(data).map(user => user.selesai || 0); // Selesai per user

            adminBarChart.update();
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
