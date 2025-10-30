<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12 page-section">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <div>
                    <h2 class="mb-0"><i class="fas fa-chart-line me-2"></i> Results & Rankings</h2>
                </div>
            </div>
            <p class="text-muted mb-4">Competition scoring and leaderboard.</p>

            <!-- Alert messages -->
            <!-- Uncomment if needed
            <div class="alert alert-success">Success message here.</div>
            <div class="alert alert-danger">Error message here.</div>
            -->

            <!-- Top stats (compact, matching dashboard style) -->
            <div class="stat-grid mb-4">
                <div class="stat-item"><div class="stat-icon"><i class="fas fa-users"></i></div><div class="stat-text"><h3>100</h3><small>Total Contestants</small></div></div>
                <div class="stat-item"><div class="stat-icon"><i class="fas fa-gavel"></i></div><div class="stat-text"><h3>10</h3><small>Active Judges</small></div></div>
                <div class="stat-item"><div class="stat-icon"><i class="fas fa-star"></i></div><div class="stat-text"><h3>450</h3><small>Total Scores</small></div></div>
                <div class="stat-item"><div class="stat-icon"><i class="fas fa-chart-line"></i></div><div class="stat-text"><h3>8.9</h3><small>Average Score</small></div></div>
            </div>

            <!-- Round Selector -->
            <div class="card card-soft mb-4">
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
                    <div class="card card-soft">
                        <div class="card-header d-flex align-items-center justify-content-between">
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
                    <div class="card card-soft">
                        <div class="card-header d-flex align-items-center justify-content-between">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    function changeRound(roundId) {
        // Simulate navigation
        alert("Changed to round ID: " + roundId);
    }
    </script>
<?= $this->endSection() ?>
