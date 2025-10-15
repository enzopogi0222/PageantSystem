<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contestant Management - <?php echo htmlspecialchars($system_name); ?></title>
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
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            border: none;
        }
    </style>
    <style>
        .contestant-photo {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 50%;
        }
        .status-badge {
            font-size: 0.75rem;
        }
        .stat-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s;
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
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link active" href="/contestant">
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
                        <h1>Contestant Management</h1>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addContestantModal">
                            <i class="fas fa-plus me-2"></i> Add Contestant
                        </button>
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
                    
                    <!-- Contestants Table -->
                    <div class="card">
                        <div class="card-header">
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
                                                        <?php if ($c['profile_photo']): ?>
                                                            <img src="../uploads/profiles/<?php echo htmlspecialchars($c['profile_photo']); ?>" 
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
                                                    <td><?php echo $c['age'] ? $c['age'] . ' years' : '-'; ?></td>
                                                    <td><?php echo htmlspecialchars($c['hometown'] ?: '-'); ?></td>
                                                    <td>
                                                        <span class="badge status-badge bg-<?php 
                                                            echo $c['status'] === 'qualified' ? 'success' : 
                                                                ($c['status'] === 'registered' ? 'warning' : 'danger'); 
                                                        ?>">
                                                            <?php echo ucfirst($c['status']); ?>
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
                <form method="POST" enctype="multipart/form-data">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function changeStatus(contestantId, status) {
            const statusText = status === 'qualified' ? 'qualify' : 'disqualify';
            if (confirm(`Are you sure you want to ${statusText} this contestant?`)) {
                document.getElementById('statusContestantId').value = contestantId;
                document.getElementById('statusValue').value = status;
                document.getElementById('statusForm').submit();
            }
        }
        
        function viewContestant(contestantId) {
            // Fetch contestant details via AJAX
            fetch(`api/get_contestant_details.php?id=${contestantId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const contestant = data.contestant;
                        
                        // Populate modal with contestant data
                        document.getElementById('contestantPhoto').src = contestant.profile_photo ? 
                            `../uploads/profiles/${contestant.profile_photo}` : 
                            '../uploads/profiles/default-avatar.png';
                        document.getElementById('contestantName').textContent = 
                            `${contestant.first_name} ${contestant.last_name}`;
                        document.getElementById('contestantNumber').textContent = 
                            contestant.contestant_number || 'Not assigned';
                        document.getElementById('contestantAge').textContent = 
                            contestant.age ? `${contestant.age} years` : 'Not specified';
                        document.getElementById('contestantEmail').textContent = 
                            contestant.email || 'Not provided';
                        document.getElementById('contestantPhone').textContent = 
                            contestant.phone || 'Not provided';
                        document.getElementById('contestantHeight').textContent = 
                            contestant.height || 'Not specified';
                        document.getElementById('contestantWeight').textContent = 
                            contestant.weight || 'Not specified';
                        document.getElementById('contestantHometown').textContent = 
                            contestant.hometown || 'Not specified';
                        document.getElementById('contestantEducation').textContent = 
                            contestant.education || 'Not specified';
                        document.getElementById('contestantOccupation').textContent = 
                            contestant.occupation || 'Not specified';
                        document.getElementById('contestantTalents').textContent = 
                            contestant.talents || 'Not specified';
                        document.getElementById('contestantBio').textContent = 
                            contestant.bio || 'No bio available';
                        document.getElementById('contestantCreated').textContent = 
                            new Date(contestant.created_at).toLocaleDateString();
                        
                        // Set status badge
                        const statusBadge = document.getElementById('contestantStatus');
                        statusBadge.textContent = contestant.status.charAt(0).toUpperCase() + contestant.status.slice(1);
                        statusBadge.className = 'badge ' + 
                            (contestant.status === 'qualified' ? 'bg-success' : 
                             contestant.status === 'registered' ? 'bg-warning' : 'bg-danger');
                        
                        // Show modal
                        new bootstrap.Modal(document.getElementById('viewContestantModal')).show();
                    } else {
                        alert('Error loading contestant details: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading contestant details');
                });
        }
        
        function editContestant() {
            // Close view modal and open edit modal (to be implemented)
            bootstrap.Modal.getInstance(document.getElementById('viewContestantModal')).hide();
            alert('Edit contestant feature - to be implemented');
        }
    </script>
</body>
</html>