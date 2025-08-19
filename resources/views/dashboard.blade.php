<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,.1);
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        .stat-card {
            text-align: center;
            padding: 2rem 1rem;
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 0.5rem 0;
        }
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .chart-container {
            position: relative;
            height: 400px;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">Admin Dashboard</a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">
                            <i class="fas fa-home"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">
                            <i class="fas fa-box"></i> Products
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="toolsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-tools"></i> Tools
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('even-numbers') }}">Even Numbers</a></li>
                            <li><a class="dropdown-item" href="{{ route('multiplication') }}">Multiplication</a></li>
                            <li><a class="dropdown-item" href="{{ route('gpa.index') }}">GPA Calculator</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('catalog') }}">
                            <i class="fas fa-book"></i> Catalog
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <span class="nav-link text-muted">
                            <i class="fas fa-user"></i> {{ Auth::user()->name }}
                        </span>
                    </li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Page Header -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h2 class="mb-0">Dashboard</h2>
            </div>
            <div class="col-md-6 text-end">
                <button id="refreshBtn" class="btn btn-primary me-2">
                    <i class="fas fa-sync-alt"></i> Refresh Data
                </button>
                <button class="btn btn-outline-secondary">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Total Users</div>
                        <div class="stat-number text-primary" id="totalUsers">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <i class="fas fa-users fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Total Products</div>
                        <div class="stat-number text-success" id="totalProducts">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <i class="fas fa-box fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Total Revenue</div>
                        <div class="stat-number text-info" id="totalRevenue">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3 mb-3">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="stat-label">Total Purchases</div>
                        <div class="stat-number text-warning" id="totalPurchases">
                            <i class="fas fa-spinner fa-spin"></i>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x text-muted"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Dashboard Analytics</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="mainChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let mainChart;

        // Function to fetch data from Laravel APIs
        async function fetchDashboardData() {
            try {
                const [usersResponse, productsResponse, statsResponse] = await Promise.all([
                    fetch('/api/users-data'),
                    fetch('/api/products-data'),
                    fetch('/api/statistics-data')
                ]);

                if (!usersResponse.ok || !productsResponse.ok || !statsResponse.ok) {
                    throw new Error('Failed to fetch data');
                }

                const usersData = await usersResponse.json();
                const productsData = await productsResponse.json();
                const statsData = await statsResponse.json();

                return {
                    totalUsers: usersData.totalUsers,
                    totalProducts: productsData.totalProducts,
                    totalRevenue: statsData.totalRevenue || 0,
                    totalPurchases: statsData.totalPurchases || 0,
                    chartData: statsData.usersGrowth || {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        data: [65, 59, 80, 81, 56, 55]
                    }
                };
            } catch (error) {
                console.error('Error fetching dashboard data:', error);
                // Return fallback data
                return {
                    totalUsers: 1543,
                    totalProducts: 45,
                    totalRevenue: 125000,
                    totalPurchases: 500,
                    chartData: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                        data: [65, 59, 80, 81, 56, 55]
                    }
                };
            }
        }

        // Function to update UI with data
        function updateDashboard(data) {
            // Update statistics cards
            document.getElementById('totalUsers').textContent = data.totalUsers.toLocaleString();
            document.getElementById('totalProducts').textContent = data.totalProducts.toLocaleString();
            document.getElementById('totalRevenue').textContent = '$' + data.totalRevenue.toLocaleString();
            document.getElementById('totalPurchases').textContent = data.totalPurchases.toLocaleString();

            // Update or create chart
            if (mainChart) {
                mainChart.data.labels = data.chartData.labels;
                mainChart.data.datasets[0].data = data.chartData.data;
                mainChart.update();
            } else {
                const ctx = document.getElementById('mainChart').getContext('2d');
                mainChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.chartData.labels,
                        datasets: [{
                            label: 'Growth Trend',
                            data: data.chartData.data,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', async function() {
            const data = await fetchDashboardData();
            updateDashboard(data);

            // Add refresh button functionality
            document.getElementById('refreshBtn').addEventListener('click', async function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                const newData = await fetchDashboardData();
                updateDashboard(newData);
                this.innerHTML = '<i class="fas fa-sync-alt"></i> Refresh Data';
            });
        });
    </script>
</body>
</html>
