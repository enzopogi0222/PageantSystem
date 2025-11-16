<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 page-section">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h2 class="mb-0"><i class="fas fa-users me-2"></i> Contestants</h2>
                    </div>
                    <p class="text-muted mb-4">Manage contestant profiles, photos, and registration details.</p>
                    
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
                    
                    <!-- Contestants Table -->
                    <div class="card card-soft">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">All Contestants (<?php echo count($contestants); ?>)</h5>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addContestantModal">
                                <i class="fas fa-plus me-2"></i> Add Contestant
                            </button>
                        </div>
                        <div class="card-body">
                            <?php if (empty($contestants)): ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No contestants yet</h4>
                                    <p class="text-muted">Add your first contestant to get started.</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContestantModal">
                                        <i class="fas fa-plus me-2"></i> Add First Contestant
                                    </button>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Photo</th>
                                                <th>Number</th>
                                                <th>Name</th>
                                                <th>Age</th>
                                                <th>Hometown</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($contestants as $c): ?>
                                                <tr>
                                                    <td>
                                                        <?php if (!empty($c['photo_path'])): ?>
                                                            <img src="../uploads/profiles/<?php echo htmlspecialchars($c['photo_path']); ?>" 
                                                                 class="contestant-photo" alt="Photo">
                                                        <?php else: ?>
                                                            <div class="contestant-photo bg-light d-flex align-items-center justify-content-center">
                                                                <i class="fas fa-user text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><strong><?php echo htmlspecialchars($c['contestant_number']); ?></strong></td>
                                                    <td>
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($c['first_name'] . ' ' . $c['last_name']); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($c['email']); ?></small>
                                                        </div>
                                                    </td>
                                                    <td><?php echo (isset($c['age']) && $c['age']) ? $c['age'] . ' years' : '-'; ?></td>
                                                    <td><?php echo !empty($c['hometown']) ? htmlspecialchars($c['hometown']) : '-'; ?></td>
                                                    <td>
                                                        <?php $status = $c['status'] ?? 'active'; ?>
                                                        <span class="badge status-badge bg-<?php 
                                                            echo $status === 'active' ? 'success' : 
                                                                ($status === 'withdrawn' ? 'warning' : 'danger'); 
                                                        ?>">
                                                            <?php echo ucfirst($status); ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group btn-group-sm">
                                                            <button class="btn btn-outline-primary" 
                                                                    onclick="viewContestant(<?php echo $c['id']; ?>)">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                            <button class="btn btn-outline-success" 
                                                                    onclick="changeStatus(<?php echo $c['id']; ?>, 'qualified')">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                            <button class="btn btn-outline-danger" 
                                                                    onclick="changeStatus(<?php echo $c['id']; ?>, 'disqualified')">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <!-- View Contestant Details Modal -->
    <div class="modal fade" id="viewContestantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user me-2"></i>Contestant Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img id="contestantPhoto" src="" alt="Contestant Photo" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                            <h5 id="contestantName"></h5>
                            <span id="contestantStatus" class="badge"></span>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Contestant Number:</label>
                                    <p id="contestantNumber" class="mb-0"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Age:</label>
                                    <p id="contestantAge" class="mb-0"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email:</label>
                                    <p id="contestantEmail" class="mb-0"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phone:</label>
                                    <p id="contestantPhone" class="mb-0"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Height:</label>
                                    <p id="contestantHeight" class="mb-0"></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Weight:</label>
                                    <p id="contestantWeight" class="mb-0"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Hometown:</label>
                                    <p id="contestantHometown" class="mb-0"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Education:</label>
                                    <p id="contestantEducation" class="mb-0"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Occupation:</label>
                                    <p id="contestantOccupation" class="mb-0"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Talents:</label>
                                    <p id="contestantTalents" class="mb-0"></p>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold">Bio:</label>
                                    <p id="contestantBio" class="mb-0"></p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-bold">Registration Date:</label>
                                    <p id="contestantCreated" class="mb-0"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="editContestant()">
                        <i class="fas fa-edit me-2"></i>Edit Contestant
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Contestant Modal -->
    <div class="modal fade" id="addContestantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Contestant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="<?= site_url('events/' . ($active_event_id ?? 0) . '/contestants/create') ?>" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="phone" name="phone">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age" min="16" max="50">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="height" class="form-label">Height</label>
                                <input type="text" class="form-control" id="height" name="height" placeholder="e.g., 5'6&quot;">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="weight" class="form-label">Weight</label>
                                <input type="text" class="form-control" id="weight" name="weight" placeholder="e.g., 120 lbs">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="hometown" class="form-label">Hometown</label>
                                <input type="text" class="form-control" id="hometown" name="hometown">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="occupation" name="occupation">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="education" class="form-label">Education</label>
                            <input type="text" class="form-control" id="education" name="education">
                        </div>
                        
                        <div class="mb-3">
                            <label for="hobbies" class="form-label">Hobbies & Interests</label>
                            <textarea class="form-control" id="hobbies" name="hobbies" rows="2"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="bio" class="form-label">Biography</label>
                            <textarea class="form-control" id="bio" name="bio" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="profile_photo" class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" 
                                   accept="image/jpeg,image/jpg,image/png">
                            <small class="form-text text-muted">Upload a high-quality photo (JPG, PNG). Max 5MB.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_contestant" class="btn btn-primary">Add Contestant</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Change Form (Hidden) -->
    <form id="statusForm" method="POST" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
        <input type="hidden" name="contestant_id" id="statusContestantId">
        <input type="hidden" name="status" id="statusValue">
        <input type="hidden" name="update_status" value="1">
    </form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        function changeStatus(contestantId, status) {
            const statusText = status === 'qualified' ? 'qualify' : 'disqualify';
            if (confirm(`Are you sure you want to ${statusText} this contestant?`)) {
                document.getElementById('statusContestantId').value = contestantId;
                document.getElementById('statusValue').value = status;
                document.getElementById('statusForm').submit();
            }
        }
        
        function editContestant() {
            // Close view modal and open edit modal (to be implemented)
            bootstrap.Modal.getInstance(document.getElementById('viewContestantModal')).hide();
            alert('Edit contestant feature - to be implemented');
        }
    </script>
<?= $this->endSection() ?>