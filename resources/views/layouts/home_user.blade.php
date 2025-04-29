@extends('layouts.main')

@section('isi')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-3">
        <h1 class="h3 mb-0 text-gray-800">You're Activity</h1>
    </div>
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daily') }}" style="text-decoration: none;">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                You're Daily</div>
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
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('dailprog') }}" style="text-decoration: none;">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Daily(Progress)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $progressCount }}</div>
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
            <a href="{{ url('daildone') }}" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Daily(Done)
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $selesaiCount }}</div>
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

    {{-- Filter Section --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <label for="filterType">Filter Tipe</label>
            <select id="filterType" class="form-control">
                <option value="daily">Harian</option>
                <option value="weekly">Mingguan</option>
                <option value="monthly">Bulanan</option>
            </select>
        </div>

        {{-- Harian --}}
        <div class="col-md-3 filter-group" id="dailyInput">
            <label for="filterDate">Pilih Tanggal</label>
            <input type="date" id="filterDate" class="form-control">
        </div>

        {{-- Mingguan --}}
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

        {{-- Bulanan --}}
        <div class="col-md-3 filter-group d-none" id="monthlyInput">
            <label for="filterMonth">Pilih Bulan</label>
            <input type="month" id="filterMonth" class="form-control">
        </div>
    </div>

    {{-- Chart --}}
    <div class="container">
        <h2>User Dashboard</h2>
    
        {{-- Flexbox Container --}}
        <div class="d-flex justify-content-between align-items-start">
            
            {{-- Chart --}}
            <div style="position: relative; width: 100%; height: 60vh;">
                <canvas id="userBarChart"></canvas>
            </div>
    
            {{-- Info Section on the Right --}}
            <div class="info-container" style="width: 20%; padding: 20px;">
                <div class="info-box">
                    <h5>Progress: <span id="progressCount">{{ $progressCount }}</span></h5>
                    <h5>Selesai: <span id="selesaiCount">{{ $selesaiCount }}</span></h5>
                    <h5>Total: <span id="totalCount">{{ $totalCount }}</span></h5>
                </div>
            </div>
    
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctx = document.getElementById('userBarChart').getContext('2d');

    // Chart Initialization
    let userBarChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Total', 'Progres', 'Selesai'], // Labels for each bar
        datasets: [
            {
                label: 'Total', // Label for Total
                data: [{{ $totalCount }}, 0, 0], // Total only in the first bar
                backgroundColor: '#3498db', // Blue color for Total
                borderWidth: 1
            },
            {
                label: 'Progres', // Label for Progres
                data: [0, {{ $progressCount }}, 0], // Progress only in the second bar
                backgroundColor: '#f1c40f', // Yellow color for Progres
                borderWidth: 1
            },
            {
                label: 'Selesai', // Label for Selesai
                data: [0, 0, {{ $selesaiCount }}], // Selesai only in the third bar
                backgroundColor: '#2ecc71', // Green color for Selesai
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            datalabels: {
                display: true, // Ensure that data labels are shown on bars
                color: '#fff', // White color for data labels
                font: {
                    weight: 'bold',
                    size: 14
                },
                formatter: function(value) {
                    return value; // Display the value on top of the bars
                }
            }
        },
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

    const filterTypeSelect = document.getElementById('filterType');
    const filterDateInput = document.getElementById('filterDate');
    const weekStartInput = document.getElementById('weekStart');
    const weekEndInput = document.getElementById('weekEnd');
    const filterMonthInput = document.getElementById('filterMonth');

    const dailyInput = document.getElementById('dailyInput');
    const weeklyInput = document.getElementById('weeklyInput');
    const monthlyInput = document.getElementById('monthlyInput');

    function toggleInputs(type) {
        dailyInput.classList.add('d-none');
        weeklyInput.classList.add('d-none');
        monthlyInput.classList.add('d-none');

        if (type === 'daily') {
            dailyInput.classList.remove('d-none');
        } else if (type === 'weekly') {
            weeklyInput.classList.remove('d-none');
        } else if (type === 'monthly') {
            monthlyInput.classList.remove('d-none');
        }
    }

    function fetchAndUpdateChart() {
        const type = filterTypeSelect.value;
        let url = `/filter-daily?type=${type}`;

        if (type === 'daily') {
            const date = filterDateInput.value;
            if (!date) return;
            url += `&date=${date}`;
        } else if (type === 'weekly') {
            const start = weekStartInput.value;
            const end = weekEndInput.value;
            if (!start || !end) return;
            url += `&start=${start}&end=${end}`;
        } else if (type === 'monthly') {
            const month = filterMonthInput.value;
            if (!month) return;
            url += `&month=${month}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                // Update dataset with new data
                userBarChart.data.datasets[0].data = [data.total, 0, 0]; // Total only in the first bar
                userBarChart.data.datasets[1].data = [0, data.progress, 0]; // Progress only in the second bar
                userBarChart.data.datasets[2].data = [0, 0, data.selesai]; // Selesai only in the third bar

                // Update chart visually
                userBarChart.update();

                // Update info section
                document.getElementById('progressCount').innerText = data.progress;
                document.getElementById('selesaiCount').innerText = data.selesai;
                document.getElementById('totalCount').innerText = data.total;
            });
    }

    filterTypeSelect.addEventListener('change', () => {
        const type = filterTypeSelect.value;
        toggleInputs(type);
        fetchAndUpdateChart();
    });

    // Listen input changes
    filterDateInput.addEventListener('change', fetchAndUpdateChart);
    weekStartInput.addEventListener('change', fetchAndUpdateChart);
    weekEndInput.addEventListener('change', fetchAndUpdateChart);
    filterMonthInput.addEventListener('change', fetchAndUpdateChart);

    // On page load, show default
    toggleInputs(filterTypeSelect.value);
</script>
@endsection
