<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>


    <div class="container-fluid">
        <div class="row">
            <!-- Main Content -->
            <div class="col-12 page-section">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h2 class="mb-0"><i class="fas fa-users me-2"></i> Contestants</h2>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContestantModal">
                            <i class="fas fa-plus me-2"></i> Add Contestant
                        </button>
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
                    
                    <!-- Contestants Grid -->
                    <div class="card card-soft">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">All Contestants (<?php echo count($contestants); ?>)</h5>
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
                                <div class="row g-3">
                                    <?php foreach ($contestants as $c): ?>
                                        <?php $photo = $c['profile_photo'] ?? ($c['photo_path'] ?? null); ?>
                                        <?php $status = $c['status'] ?? 'registered'; ?>
                                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
                                            <div class="card h-100 shadow-sm">
                                                <div class="card-img-top d-flex align-items-center justify-content-center" style="height: 200px; overflow: hidden; background: #f8f9fa; border-top-left-radius: .5rem; border-top-right-radius: .5rem;">
                                                    <?php if (!empty($photo)): ?>
                                                        <img src="<?= base_url('uploads/profiles/' . ($photo ?? '')) ?>" alt="Photo" style="max-height: 100%; max-width: 100%; object-fit: contain; object-position: center; display:block;">
                                                    <?php else: ?>
                                                        <div class="d-flex align-items-center justify-content-center" style="height: 100%; width: 100%;">
                                                            <i class="fas fa-user fa-3x text-muted"></i>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <span class="badge bg-secondary">#<?php echo htmlspecialchars($c['contestant_number'] ?? '-'); ?></span>
                                                        <span class="badge status-badge bg-<?php echo $status === 'qualified' ? 'success' : ($status === 'registered' ? 'warning' : 'danger'); ?>"><?php echo ucfirst($status); ?></span>
                                                    </div>
                                                    <h6 class="card-title mb-1"><?php echo htmlspecialchars(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? '')); ?></h6>
                                                    <div class="text-muted small">Age: <?php echo (isset($c['age']) && $c['age'] !== '') ? ($c['age']) : '-'; ?></div>
                                                    <div class="text-muted small">Hometown: <?php echo htmlspecialchars($c['hometown'] ?? '-'); ?></div>
                                                </div>
                                                <div class="card-footer bg-white border-0 d-flex justify-content-between">
                                                    <button class="btn btn-outline-secondary btn-sm"
                                                        onclick="openEditContestant(this)"
                                                        data-id="<?php echo $c['id']; ?>"
                                                        data-number="<?php echo htmlspecialchars($c['contestant_number'] ?? ''); ?>"
                                                        data-first_name="<?php echo htmlspecialchars($c['first_name'] ?? ''); ?>"
                                                        data-last_name="<?php echo htmlspecialchars($c['last_name'] ?? ''); ?>"
                                                        data-email="<?php echo htmlspecialchars($c['email'] ?? ''); ?>"
                                                        data-phone="<?php echo htmlspecialchars($c['phone'] ?? ''); ?>"
                                                        data-age="<?php echo htmlspecialchars($c['age'] ?? ''); ?>"
                                                        data-gender="<?php echo htmlspecialchars($c['gender'] ?? ''); ?>"
                                                        data-height="<?php echo htmlspecialchars($c['height'] ?? ''); ?>"
                                                        data-weight="<?php echo htmlspecialchars($c['weight'] ?? ''); ?>"
                                                        data-hometown="<?php echo htmlspecialchars($c['hometown'] ?? ''); ?>"
                                                        data-education="<?php echo htmlspecialchars($c['education'] ?? ''); ?>"
                                                        data-occupation="<?php echo htmlspecialchars($c['occupation'] ?? ''); ?>"
                                                        data-hobbies="<?php echo htmlspecialchars($c['hobbies'] ?? ''); ?>"
                                                        data-bio="<?php echo htmlspecialchars($c['bio'] ?? ''); ?>"
                                                        data-status="<?php echo htmlspecialchars($status); ?>"
                                                        data-photo="<?php echo htmlspecialchars($photo ?? ''); ?>">
                                                        <i class="fas fa-edit me-1"></i> Edit
                                                    </button>
                                                    <button class="btn btn-outline-primary btn-sm"
                                                        onclick="viewContestant(this)"
                                                        data-id="<?php echo $c['id']; ?>"
                                                        data-number="<?php echo htmlspecialchars($c['contestant_number'] ?? ''); ?>"
                                                        data-name="<?php echo htmlspecialchars(($c['first_name'] ?? '') . ' ' . ($c['last_name'] ?? '')); ?>"
                                                        data-email="<?php echo htmlspecialchars($c['email'] ?? ''); ?>"
                                                        data-phone="<?php echo htmlspecialchars($c['phone'] ?? ''); ?>"
                                                        data-age="<?php echo htmlspecialchars($c['age'] ?? ''); ?>"
                                                        data-height="<?php echo htmlspecialchars($c['height'] ?? ''); ?>"
                                                        data-weight="<?php echo htmlspecialchars($c['weight'] ?? ''); ?>"
                                                        data-hometown="<?php echo htmlspecialchars($c['hometown'] ?? ''); ?>"
                                                        data-education="<?php echo htmlspecialchars($c['education'] ?? ''); ?>"
                                                        data-occupation="<?php echo htmlspecialchars($c['occupation'] ?? ''); ?>"
                                                        data-talents="<?php echo htmlspecialchars($c['hobbies'] ?? ''); ?>"
                                                        data-bio="<?php echo htmlspecialchars($c['bio'] ?? ''); ?>"
                                                        data-status="<?php echo htmlspecialchars($status); ?>"
                                                        data-photo="<?php echo htmlspecialchars($photo ?? ''); ?>">
                                                        <i class="fas fa-eye me-1"></i> View Info
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
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
                <form method="POST" action="<?= site_url('contestant') ?>" enctype="multipart/form-data">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="contestant_number" class="form-label">Contestant Number *</label>
                                <input type="number" class="form-control" id="contestant_number" name="contestant_number" min="1" required>
                            </div>
                        </div>

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
                            <div class="col-md-3 mb-3">
                                <label for="age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="age" name="age" min="16" max="50">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="height" class="form-label">Height</label>
                                <input type="text" class="form-control" id="height" name="height" placeholder="e.g., 5'6&quot;">
                            </div>
                            <div class="col-md-3 mb-3">
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

    <!-- Edit Contestant Modal -->
    <div class="modal fade" id="editContestantModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Contestant</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editContestantForm" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="edit_contestant_number" class="form-label">Contestant Number *</label>
                                <input type="number" class="form-control" id="edit_contestant_number" name="contestant_number" min="1" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="edit_email" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="edit_phone" name="phone">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="edit_age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="edit_age" name="age" min="16" max="50">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_gender" class="form-label">Gender</label>
                                <select class="form-select" id="edit_gender" name="gender">
                                    <option value="">Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_height" class="form-label">Height</label>
                                <input type="text" class="form-control" id="edit_height" name="height">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="edit_weight" class="form-label">Weight</label>
                                <input type="text" class="form-control" id="edit_weight" name="weight">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_hometown" class="form-label">Hometown</label>
                                <input type="text" class="form-control" id="edit_hometown" name="hometown">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" id="edit_occupation" name="occupation">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_education" class="form-label">Education</label>
                            <input type="text" class="form-control" id="edit_education" name="education">
                        </div>
                        <div class="mb-3">
                            <label for="edit_hobbies" class="form-label">Hobbies & Interests</label>
                            <textarea class="form-control" id="edit_hobbies" name="hobbies" rows="2"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_bio" class="form-label">Biography</label>
                            <textarea class="form-control" id="edit_bio" name="bio" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_profile_photo" class="form-label">Replace Photo</label>
                            <input type="file" class="form-control" id="edit_profile_photo" name="profile_photo" accept="image/jpeg,image/jpg,image/png">
                            <small class="form-text text-muted">Leave blank to keep current photo.</small>
                        </div>
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="active">Active</option>
                                <option value="registered">Registered</option>
                                <option value="qualified">Qualified</option>
                                <option value="disqualified">Disqualified</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Status Change Form (Hidden) -->
    <form id="statusForm" method="POST" style="display: none;">
        <?= csrf_field() ?>
        <input type="hidden" name="contestant_id" id="statusContestantId">
        <input type="hidden" name="status" id="statusValue">
        <input type="hidden" name="update_status" value="1">
    </form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
    <script>
        const PROFILE_UPLOADS_BASE = "<?= rtrim(base_url('uploads/profiles'), '/') ?>/";
        function changeStatus(contestantId, status) {
            const statusText = status === 'qualified' ? 'qualify' : 'disqualify';
            if (confirm(`Are you sure you want to ${statusText} this contestant?`)) {
                document.getElementById('statusContestantId').value = contestantId;
                document.getElementById('statusValue').value = status;
                document.getElementById('statusForm').submit();
            }
        }
        
        function viewContestant(button) {
            const d = button.dataset;
            const photoEl = document.getElementById('contestantPhoto');
            if (d.photo) {
                photoEl.src = PROFILE_UPLOADS_BASE + d.photo;
            } else {
                photoEl.src = '';
            }
            document.getElementById('contestantName').textContent = d.name || '';
            const statusEl = document.getElementById('contestantStatus');
            statusEl.textContent = (d.status || '').charAt(0).toUpperCase() + (d.status || '').slice(1);
            statusEl.className = 'badge ' + (d.status === 'qualified' ? 'bg-success' : (d.status === 'registered' ? 'bg-warning' : 'bg-danger'));
            document.getElementById('contestantNumber').textContent = d.number || '';
            document.getElementById('contestantAge').textContent = d.age || '-';
            document.getElementById('contestantEmail').textContent = d.email || '';
            document.getElementById('contestantPhone').textContent = d.phone || '';
            document.getElementById('contestantHeight').textContent = d.height || '-';
            document.getElementById('contestantWeight').textContent = d.weight || '-';
            document.getElementById('contestantHometown').textContent = d.hometown || '-';
            document.getElementById('contestantEducation').textContent = d.education || '-';
            document.getElementById('contestantOccupation').textContent = d.occupation || '-';
            document.getElementById('contestantTalents').textContent = d.talents || '-';
            document.getElementById('contestantBio').textContent = d.bio || '-';

            const modal = new bootstrap.Modal(document.getElementById('viewContestantModal'));
            modal.show();
        }

        function editContestant() {
            // Close view modal and open edit modal (to be implemented)
            bootstrap.Modal.getInstance(document.getElementById('viewContestantModal')).hide();
            // Open edit modal instead
            const modal = new bootstrap.Modal(document.getElementById('editContestantModal'));
            modal.show();
        }

        function openEditContestant(button) {
            const d = button.dataset;
            const form = document.getElementById('editContestantForm');
            form.action = `<?= site_url('contestant/update') ?>/${d.id}`;
            document.getElementById('edit_contestant_number').value = d.number || '';
            document.getElementById('edit_first_name').value = d.first_name || '';
            document.getElementById('edit_last_name').value = d.last_name || '';
            document.getElementById('edit_email').value = d.email || '';
            document.getElementById('edit_phone').value = d.phone || '';
            document.getElementById('edit_age').value = d.age || '';
            document.getElementById('edit_gender').value = d.gender || '';
            document.getElementById('edit_height').value = d.height || '';
            document.getElementById('edit_weight').value = d.weight || '';
            document.getElementById('edit_hometown').value = d.hometown || '';
            document.getElementById('edit_education').value = d.education || '';
            document.getElementById('edit_occupation').value = d.occupation || '';
            document.getElementById('edit_hobbies').value = d.hobbies || '';
            document.getElementById('edit_bio').value = d.bio || '';
            document.getElementById('edit_status').value = d.status || 'active';
            const modal = new bootstrap.Modal(document.getElementById('editContestantModal'));
            modal.show();
        }
    </script>
<?= $this->endSection() ?>