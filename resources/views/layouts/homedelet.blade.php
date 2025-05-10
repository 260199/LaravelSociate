@extends('layouts.main')
@section('isi')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>
    <!-- Content Row -->
    <div class="row">
        @if($user->role==1)
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daprog') }}" style="text-decoration: none;">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Daily(Proggress)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $notclear }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                                </div>
                                <div class="col">
                                    <div class="progress progress-sm mr-2">
                                        <div class="progress-bar bg-info" role="progressbar"
                                            style="width: 100%" aria-valuenow="50" aria-valuemin="0"
                                            aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                User</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $usercount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-sm-flex align-items-center justify-content-between mb-4 ml-3">
            <h1 class="h3 mb-0 text-gray-800">You're Activity</h1>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('actv') }}" style="text-decoration: none;">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Daily(Proggress)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyuser }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('actv') }}" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Daily</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countusernotyets }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('actv') }}" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Daily(Done)
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $countuserdone }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: 100%" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @elseif($user->role==2)      
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daily') }}" style="text-decoration: none;">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Daily</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $dailyuser }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daily') }}" style="text-decoration: none;">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Daily</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $countusernotyets }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ url('daily') }}" style="text-decoration: none;">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Daily(Done)
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{ $countuserdone }}</div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-info" role="progressbar"
                                                style="width: 100%" aria-valuenow="50" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endif
    </div>

    <!-- Content Row -->
    @if($user->role == 1)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4" style="padding: 20px; border-radius: 12px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f8f9fc; border-radius: 8px; padding: 10px 20px;">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                    <div class="d-flex align-items-center">
                        <label class="mb-0 mr-2">Filter:</label>
                        <select id="filterType" class="form-control mr-2" style="width: auto;">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                        <div id="filterInputs" class="d-flex align-items-center"></div>
                    </div>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="chart-bar">
                        <canvas id="myBarChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @elseif($user->role == 2)
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4" style="padding: 20px; border-radius: 12px;">
                <div class="card-header d-flex justify-content-between align-items-center" style="background: #f8f9fc; border-radius: 8px; padding: 10px 20px;">
                    <h6 class="m-0 font-weight-bold text-primary">Bar Chart</h6>
                    <div class="d-flex align-items-center">
                        <label class="mb-0 mr-2">Filter:</label>
                        <select id="filterTypeUser" class="form-control mr-2" style="width: auto;">
                            <option value="daily">Harian</option>
                            <option value="weekly">Mingguan</option>
                            <option value="monthly">Bulanan</option>
                        </select>
                        <div id="filterInputsUser" class="d-flex align-items-center"></div>
                    </div>
                </div>
                <div class="card-body" style="padding: 30px;">
                    <div class="chart-bar">
                        <canvas id="userBarChart" style="width: 100%; height: 400px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
   
<!-- /.container-fluid -->

</div>
<!-- End of Main Content -->

{{-- Profile --}}



<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var progressData = {!! json_encode($progressCounts) !!};
    var doneData = {!! json_encode($doneCounts) !!};

    var allData = progressData.concat(doneData);
    var maxData = Math.max(...allData);
    var suggestedMax = Math.ceil(maxData / 10) * 10;

    var ctx = document.getElementById("myBarChart").getContext('2d');
    var myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [
                {
                    label: "Progress",
                    backgroundColor: "#f6c23e",
                    borderColor: "#f6c23e",
                    data: progressData
                },
                {
                    label: "Selesai",
                    backgroundColor: "#1cc88a",
                    borderColor: "#1cc88a",
                    data: doneData
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    // stacked: false secara default
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 5,
                        suggestedMax: suggestedMax
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top'
                }
            }
        }
    });
    document.getElementById('filterAdmin')?.addEventListener('change', function () {
    const filter = this.value;
    fetch(`/chart-data?filter=${filter}`)
        .then(response => response.json())
        .then(data => {
            myBarChart.data.labels = data.labels;
            myBarChart.data.datasets[0].data = data.progress;
            myBarChart.data.datasets[1].data = data.done;
            myBarChart.update();
        });
});
document.addEventListener('DOMContentLoaded', function () {
    const filterType = document.getElementById('filterType');
    const filterInputs = document.getElementById('filterInputs');

    function updateFilterInputs() {
        const value = filterType.value;
        filterInputs.innerHTML = '';

        if (value === 'daily') {
            filterInputs.innerHTML = `<input type="date" id="filterDate" class="form-control w-auto d-inline-block" />`;
        } else if (value === 'weekly') {
            filterInputs.innerHTML = `
                <input type="date" id="startDate" class="form-control w-auto d-inline-block" placeholder="Mulai" />
                <span class="mx-1">s/d</span>
                <input type="date" id="endDate" class="form-control w-auto d-inline-block" placeholder="Selesai" />
            `;
        } else if (value === 'monthly') {
            filterInputs.innerHTML = `
                <input type="month" id="filterMonth" class="form-control w-auto d-inline-block" />
            `;
        }
    }

    filterType.addEventListener('change', updateFilterInputs);
    updateFilterInputs();

    filterInputs.addEventListener('change', function () {
        const type = filterType.value;
        let url = '/chart-data?filter=' + type;

        if (type === 'daily') {
            const date = document.getElementById('filterDate').value;
            url += `&date=${date}`;
        } else if (type === 'weekly') {
            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;
            url += `&start=${start}&end=${end}`;
        } else if (type === 'monthly') {
            const month = document.getElementById('filterMonth').value;
            url += `&month=${month}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                myBarChart.data.labels = data.labels;
                myBarChart.data.datasets[0].data = data.progress;
                myBarChart.data.datasets[1].data = data.done;
                myBarChart.update();
            });
    });
});
</script>
@if($user->role == 2)
<script>
document.addEventListener('DOMContentLoaded', function () {
    var ctxUser = document.getElementById("userBarChart").getContext('2d');
    var userBarChart = new Chart(ctxUser, {
        type: 'bar',
        data: {
            labels: ["Progress", "Selesai"],
            datasets: [{
                label: "My Tasks",
                backgroundColor: ["#f6c23e", "#1cc88a"],
                borderColor: ["#f6c23e", "#1cc88a"],
                data: [{{ $userProgress }}, {{ $userDone }}]
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        suggestedMax: Math.max({{ $userProgress }}, {{ $userDone }}) + 5
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    const filterTypeUser = document.getElementById('filterTypeUser');
    const filterInputsUser = document.getElementById('filterInputsUser');

    function updateUserFilterInputs() {
        const type = filterTypeUser.value;
        filterInputsUser.innerHTML = '';

        if (type === 'daily') {
            filterInputsUser.innerHTML = `<input type="date" id="filterDateUser" class="form-control w-auto d-inline-block" />`;
        } else if (type === 'weekly') {
            filterInputsUser.innerHTML = `
                <input type="date" id="startDateUser" class="form-control w-auto d-inline-block" />
                <span class="mx-1">s/d</span>
                <input type="date" id="endDateUser" class="form-control w-auto d-inline-block" />
            `;
        } else if (type === 'monthly') {
            filterInputsUser.innerHTML = `<input type="month" id="filterMonthUser" class="form-control w-auto d-inline-block" />`;
        }
    }

    filterTypeUser.addEventListener('change', updateUserFilterInputs);
    updateUserFilterInputs();

    filterInputsUser.addEventListener('change', function () {
        const type = filterTypeUser.value;
        let url = `/chart-data?filter=${type}`;

        if (type === 'daily') {
            const date = document.getElementById('filterDateUser').value;
            url += `&date=${date}`;
        } else if (type === 'weekly') {
            const start = document.getElementById('startDateUser').value;
            const end = document.getElementById('endDateUser').value;
            url += `&start=${start}&end=${end}`;
        } else if (type === 'monthly') {
            const month = document.getElementById('filterMonthUser').value;
            url += `&month=${month}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                userBarChart.data.datasets[0].data = [
                    data.progress[0] ?? 0,
                    data.done[0] ?? 0
                ];
                userBarChart.update();
            });
    });
});
</script>
@endif


@endsection
