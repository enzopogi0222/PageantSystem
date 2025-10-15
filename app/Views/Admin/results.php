<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Results & Rankings - System Name</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6610f2;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            min-height: 100vh;
            color: white;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
        }

        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }

        .bg-success-gradient {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .bg-warning-gradient {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
        }

        .bg-info-gradient {
            background: linear-gradient(135deg, #17a2b8, #6f42c1);
        }

        .leaderboard-card {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 15px;
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        .rank-1 { background: linear-gradient(135deg, #ffd700, #ffed4e); color: #333; }
        .rank-2 { background: linear-gradient(135deg, #c0c0c0, #e8e8e8); color: #333; }
        .rank-3 { background: linear-gradient(135deg, #cd7f32, #deb887); }
        .rank-other { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); }

        .progress-bar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Placeholder -->
        <div class="col-md-2 sidebar p-3">
            <h4>Sidebar</h4>
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link active" href="#">Results</a></li>
                <!-- Add more links as needed -->
            </ul>
        </div>

        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <h2><i class="fas fa-chart-line me-2"></i> Results & Rankings</h2>
            <p class="text-muted">Competition scoring and leaderboard</p>

            <!-- Alert messages -->
            <!-- Uncomment if needed
            <div class="alert alert-success">Success message here.</div>
            <div class="alert alert-danger">Error message here.</div>
            -->

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-primary-gradient me-3"><i class="fas fa-users"></i></div>
                            <div>
                                <h3>100</h3>
                                <small>Total Contestants</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-success-gradient me-3"><i class="fas fa-gavel"></i></div>
                            <div>
                                <h3>10</h3>
                                <small>Active Judges</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-warning-gradient me-3"><i class="fas fa-star"></i></div>
                            <div>
                                <h3>450</h3>
                                <small>Total Scores</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body d-flex align-items-center">
                            <div class="stat-icon bg-info-gradient me-3"><i class="fas fa-chart-line"></i></div>
                            <div>
                                <h3>8.9</h3>
                                <small>Average Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Round Selector -->
            <div class="card mb-4">
                <div class="card-body row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-trophy me-2"></i>Select Round</h5>
                        <select class="form-select" onchange="changeRound(this.value)">
                            <option value="">All Rounds</option>
                            <option value="1" selected>Round 1: Introduction (Active)</option>
                            <option value="2">Round 2: Talent (Completed)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <h6>Current Round: Introduction</h6>
                        <p class="text-muted mb-0">Short round description here.</p>
                        <span class="badge bg-success">Active</span>
                    </div>
                </div>
            </div>

            <!-- Main Content Row -->
            <div class="row">
                <!-- Leaderboard -->
                <div class="col-md-8">
                    <div class="leaderboard-card">
                        <div class="card-header bg-primary-gradient text-white">
                            <h5><i class="fas fa-trophy me-2"></i> Current Leaderboard</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Rank</th>
                                            <th>Photo</th>
                                            <th>Number</th>
                                            <th>Name</th>
                                            <th>Score</th>
                                            <th>Judges</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><div class="rank-badge rank-1">1</div></td>
                                            <td><img src="profile1.jpg" alt="Photo" class="img-fluid rounded-circle" width="40"></td>
                                            <td>#101</td>
                                            <td>Jane Doe</td>
                                            <td><strong class="text-primary">95.5</strong></td>
                                            <td><span class="badge bg-info">10 judges</span></td>
                                            <td><span class="badge bg-success">Qualified</span></td>
                                        </tr>
                                        <tr>
                                            <td><div class="rank-badge rank-2">2</div></td>
                                            <td><img src="profile2.jpg" alt="Photo" class="img-fluid rounded-circle" width="40"></td>
                                            <td>#102</td>
                                            <td>John Smith</td>
                                            <td><strong class="text-primary">94.0</strong></td>
                                            <td><span class="badge bg-info">10 judges</span></td>
                                            <td><span class="badge bg-success">Qualified</span></td>
                                        </tr>
                                        <!-- Add more contestants -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Judge Progress -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5><i class="fas fa-tasks me-2"></i> Judge Progress</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <small class="fw-bold">Judge A</small>
                                    <small class="text-muted">10/10</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <small class="fw-bold">Judge B</small>
                                    <small class="text-muted">7/10</small>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar" style="width: 70%"></div>
                                </div>
                            </div>
                            <!-- Add more judges -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JS Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function changeRound(roundId) {
        // Simulate navigation
        alert("Changed to round ID: " + roundId);
    }
</script>

</body>
</html>
