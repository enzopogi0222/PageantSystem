<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo htmlspecialchars($system_name); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: <?php echo $primary_color; ?>;
            --secondary-color: <?php echo $secondary_color; ?>;
            --accent-color: <?php echo $accent_color; ?>;
        }
        
        .sidebar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s;
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
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        .bg-success-gradient {
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
        }
        .bg-warning-gradient {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        .bg-info-gradient {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        .color-preview {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 2px solid #dee2e6;
            cursor: pointer;
        }
        .settings-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }
        .btn-success {
            background: linear-gradient(135deg, var(--accent-color) 0%, #20c997 100%);
            border: none;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <h4 class="mb-4"><?php echo htmlspecialchars($system_name ?? 'Pageant System'); ?></h4>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link active" href="/home">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-users me-2"></i> Contestants
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-gavel me-2"></i> Judges
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-trophy me-2"></i> Rounds
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-chart-line me-2"></i> Results
                        </a>
                        
                        <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">
                        
                        <a class="nav-link" href="#">
                            <i class="fas fa-cog me-2"></i> Settings
                        </a>
                        <a class="nav-link" href="#">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h2><i class="fas fa-tachometer-alt me-2"></i> Dashboard</h2>
                            <p class="text-muted mb-0">Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#systemSettingsModal">
                                <i class="fas fa-cog me-2"></i> Settings
                            </button>
                        </div>
                    </div>
                    
                    <?php if ($success_message): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>
                            <?php echo htmlspecialchars($success_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-primary-gradient me-3">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_contestants; ?></h3>
                                        <small class="text-muted">Total Contestants</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-success-gradient me-3">
                                        <i class="fas fa-gavel"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_judges; ?></h3>
                                        <small class="text-muted">Active Judges</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-warning-gradient me-3">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $total_rounds; ?></h3>
                                        <small class="text-muted">Total Rounds</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card stat-card">
                                <div class="card-body d-flex align-items-center">
                                    <div class="stat-icon bg-info-gradient me-3">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div>
                                        <h3 class="mb-0"><?php echo $score_stats['finalized_judges'] ?? 0; ?></h3>
                                        <small class="text-muted">Scores Submitted</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Current Round Info -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Current Round</h5>
                                    <span class="badge bg-success"><?php echo $active_round ? ucfirst($active_round['status']) : 'No Active Round'; ?></span>
                                </div>
                                <div class="card-body text-center">
                                    <h6><i class="fas fa-trophy me-2"></i>Current Round</h6>
                                    <?php if ($active_round): ?>
                                        <h5><?php echo htmlspecialchars($active_round['name']); ?></h5>
                                        <p class="text-muted mb-2"><?php echo htmlspecialchars($active_round['description']); ?></p>
                                        <span class="badge bg-success">Round <?php echo $active_round['round_order'] ?? 'N/A'; ?> - Active</span>
                                    <?php else: ?>
                                        <p class="text-muted">No active round currently. Create and activate a round to begin scoring.</p>
                                        <span class="badge bg-warning">No Active Round</span>
                                    <?php endif; ?>
                                    <div class="mt-3">
                                        <a href="rounds.php" class="btn btn-primary btn-sm">
                                            <i class="fas fa-cog me-1"></i> Manage Rounds
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Judge Progress -->
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Judge Progress</h5>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($judge_progress)): ?>
                                        <?php foreach (array_slice($judge_progress, 0, 5) as $judge): ?>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <small><?php echo htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']); ?></small>
                                                <div>
                                                    <span class="badge bg-<?php echo $judge['finalized_contestants'] > 0 ? 'success' : 'secondary'; ?>">
                                                        <?php echo $judge['finalized_contestants']; ?>/<?php echo $judge['total_contestants']; ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (count($judge_progress) > 5): ?>
                                            <small class="text-muted">And <?php echo count($judge_progress) - 5; ?> more judges...</small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <p class="text-muted">No scoring progress available.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Current Leaderboard -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Current Leaderboard</h5>
                                    <a href="results.php" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-chart-line me-1"></i> View Full Results
                                    </a>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($leaderboard)): ?>
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Rank</th>
                                                        <th>Contestant</th>
                                                        <th>Name</th>
                                                        <th>Score</th>
                                                        <th>Judges</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach (array_slice($leaderboard, 0, 10) as $index => $contestant): ?>
                                                        <tr>
                                                            <td>
                                                                <span class="badge bg-primary"><?php echo $index + 1; ?></span>
                                                            </td>
                                                            <td><?php echo htmlspecialchars($contestant['contestant_number']); ?></td>
                                                            <td><?php echo htmlspecialchars($contestant['first_name'] . ' ' . $contestant['last_name']); ?></td>
                                                            <td><?php echo number_format($contestant['total_score'], 2); ?></td>
                                                            <td><?php echo $contestant['judge_count']; ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted">No rankings available yet. Scoring is in progress.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="systemSettingsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">System Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <!-- Pageant Name -->
                        <div class="mb-4">
                            <label for="system_name" class="form-label">Pageant Name</label>
                            <input type="text" class="form-control" id="system_name" name="system_name" 
                                   value="<?php echo htmlspecialchars($system_name); ?>" required>
                            <small class="form-text text-muted">This name will appear throughout the system and public site.</small>
                        </div>
                        
                        <!-- Theme Colors -->
                        <div class="mb-4">
                            <label class="form-label">Theme Colors</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="primary_color" class="form-label">Primary Color</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" class="form-control form-control-color me-2" 
                                               id="primary_color" name="primary_color" value="<?php echo $primary_color; ?>">
                                        <input type="text" class="form-control" value="<?php echo $primary_color; ?>" 
                                               onchange="document.getElementById('primary_color').value = this.value">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="secondary_color" class="form-label">Secondary Color</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" class="form-control form-control-color me-2" 
                                               id="secondary_color" name="secondary_color" value="<?php echo $secondary_color; ?>">
                                        <input type="text" class="form-control" value="<?php echo $secondary_color; ?>" 
                                               onchange="document.getElementById('secondary_color').value = this.value">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="accent_color" class="form-label">Accent Color</label>
                                    <div class="d-flex align-items-center">
                                        <input type="color" class="form-control form-control-color me-2" 
                                               id="accent_color" name="accent_color" value="<?php echo $accent_color; ?>">
                                        <input type="text" class="form-control" value="<?php echo $accent_color; ?>" 
                                               onchange="document.getElementById('accent_color').value = this.value">
                                    </div>
                                </div>
                            </div>
                            <small class="form-text text-muted">Choose colors that match your pageant theme. Changes will apply immediately.</small>
                        </div>
                        
                        <!-- Public Voting -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="allow_public_voting" 
                                       name="allow_public_voting" <?php echo $allow_public_voting ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="allow_public_voting">
                                    Enable Public Voting (People's Choice)
                                </label>
                            </div>
                            <small class="form-text text-muted">Allow public to vote for their favorite contestants.</small>
                        </div>
                        
                        <!-- Preview -->
                        <div class="mb-4">
                            <label class="form-label">Theme Preview</label>
                            <div class="p-3 rounded" id="theme-preview" style="background: linear-gradient(135deg, <?php echo $primary_color; ?> 0%, <?php echo $secondary_color; ?> 100%); color: white;">
                                <h6 class="mb-2" id="preview-name"><?php echo htmlspecialchars($system_name); ?></h6>
                                <p class="mb-2">This is how your pageant theme will look</p>
                                <button type="button" class="btn btn-sm" id="preview-btn" style="background: <?php echo $accent_color; ?>; color: white; border: none;">Sample Button</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_settings" class="btn btn-primary">Save Settings</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Live preview updates
        document.getElementById('system_name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value;
        });
        
        document.getElementById('primary_color').addEventListener('input', updatePreview);
        document.getElementById('secondary_color').addEventListener('input', updatePreview);
        document.getElementById('accent_color').addEventListener('input', updatePreview);
        
        function updatePreview() {
            const primary = document.getElementById('primary_color').value;
            const secondary = document.getElementById('secondary_color').value;
            const accent = document.getElementById('accent_color').value;
            
            const preview = document.getElementById('theme-preview');
            const btn = document.getElementById('preview-btn');
            
            preview.style.background = `linear-gradient(135deg, ${primary} 0%, ${secondary} 100%)`;
            btn.style.background = accent;
        }
        
     
        function showAddContestantModal() {
            window.location.href = 'contestants.php';
        }
        
        // Add Judge Modal Function
        function showAddJudgeModal() {
            window.location.href = 'judges.php';
        }
        
        // Real-time statistics update
        function updateDashboardStats() {
            fetch('api/dashboard_stats.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update statistics cards
                        document.querySelector('.stat-card:nth-child(1) h3').textContent = data.total_contestants;
                        document.querySelector('.stat-card:nth-child(2) h3').textContent = data.total_judges;
                        document.querySelector('.stat-card:nth-child(3) h3').textContent = data.total_rounds;
                        document.querySelector('.stat-card:nth-child(4) h3').textContent = data.scores_submitted;
                    }
                })
                .catch(error => console.log('Stats update failed:', error));
        }
        
        // Update stats every 60 seconds
        setInterval(updateDashboardStats, 60000);
    </script>
</body>
</html>