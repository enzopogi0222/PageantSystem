<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 page-section">
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

                <!-- Top actions -->
                <div class="mb-3">
                    <button type="button" class="btn btn-light border" data-bs-toggle="modal" data-bs-target="#systemSettingsModal">
                        <i class="fas fa-cog me-1"></i> Settings
                    </button>
                </div>

                <!-- Stats grid -->
                <div class="stat-grid mb-4">
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-users"></i></div>
                        <div class="stat-text">
                            <h3><?php echo $total_contestants; ?></h3>
                            <small>Total Contestants</small>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-gavel"></i></div>
                        <div class="stat-text">
                            <h3><?php echo $total_judges; ?></h3>
                            <small>Active Judges</small>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-trophy"></i></div>
                        <div class="stat-text">
                            <h3><?php echo $total_rounds; ?></h3>
                            <small>Total Rounds</small>
                        </div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-icon"><i class="fas fa-star"></i></div>
                        <div class="stat-text">
                            <h3><?php echo $score_stats['finalized_judges'] ?? 0; ?></h3>
                            <small>Scores Submitted</small>
                        </div>
                    </div>
                </div>
                    
                    <div class="row">
                        <!-- Current Round Info -->
                        <div class="col-md-6 mb-4">
                            <div class="card card-soft">
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
                                        <a href="<?= base_url('rounds') ?>" class="btn btn-primary btn-sm">
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
                            <div class="card card-soft">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Current Leaderboard</h5>
                                    <a href="<?= base_url('results') ?>" class="btn btn-outline-primary btn-sm">
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
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        // Live preview updates
        document.getElementById('system_name').addEventListener('input', function() {
            document.getElementById('preview-name').textContent = this.value;
        });
        
        // Real-time statistics update
        function updateDashboardStats() {
            fetch('<?= base_url('api/dashboard-stats') ?>')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update statistics cards
                        const statNumbers = document.querySelectorAll('.stat-item h3');
                        if (statNumbers.length >= 4) {
                            statNumbers[0].textContent = data.total_contestants ?? 0;
                            statNumbers[1].textContent = data.total_judges ?? 0;
                            statNumbers[2].textContent = data.total_rounds ?? 0;
                            statNumbers[3].textContent = data.scores_submitted ?? 0;
                        }
                    }
                })
                .catch(error => console.log('Stats update failed:', error));
        }
        
        // Update stats every 60 seconds
        setInterval(updateDashboardStats, 60000);
    </script>
<?= $this->endSection() ?>