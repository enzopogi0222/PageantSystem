<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rounds & Criteria - <?php echo htmlspecialchars($system_name); ?></title>
    <style>
        :root {
            --primary-color: <?php echo $primary_color ?? '#6f42c1'; ?>;
            --secondary-color: <?php echo $secondary_color ?? '#495057'; ?>;
            --accent-color: <?php echo $accent_color ?? '#28a745'; ?>;
        }
        
        body {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .sidebar {
            min-height: 100vh;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px 0;
        }
        
        .nav-link {
            color: var(--secondary-color);
            padding: 10px 20px;
            margin: 2px 10px;
            border-radius: 5px;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #5a32a3;
            border-color: #5a32a3;
        }
        
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .status-upcoming {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-completed {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="p-3">
                    <h4 class="mb-4"><?php echo htmlspecialchars($system_name ?? 'Pageant System') ?></h4>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="/contestant">
                            <i class="fas fa-users me-2"></i> Contestants
                        </a>
                        <a class="nav-link" href="/judges">
                            <i class="fas fa-gavel me-2"></i> Judges
                        </a>
                        <a class="nav-link active" href="/rounds">
                            <i class="fas fa-trophy me-2"></i> Rounds
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-trophy me-2"></i> Rounds & Criteria Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoundModal">
                        <i class="fas fa-plus me-2"></i> Add Round
                    </button>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Rounds List -->
                <?php if (empty($rounds_with_criteria)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <h5>No rounds found</h5>
                        <p class="text-muted">Create your first round to set up the competition structure.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($rounds_with_criteria as $round): ?>
                        <div class="card">
                            <div class="round-header">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <h5 class="mb-0">Round <?php echo $round['round_order']; ?>: <?php echo htmlspecialchars($round['name']); ?></h5>
                                        <p class="mb-0 opacity-75"><?php echo htmlspecialchars($round['description']); ?></p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <?php if ($round['status'] === 'upcoming'): ?>
                                            <span class="badge bg-warning text-dark">Upcoming</span>
                                        <?php elseif ($round['status'] === 'active'): ?>
                                            <span class="badge bg-success">Active</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Completed</span>
                                        <?php endif; ?>
                                        
                                        <div class="btn-group ms-2">
                                            <?php if ($round['status'] === 'upcoming'): ?>
                                                <button class="btn btn-success btn-sm" onclick="updateRoundStatus(<?php echo $round['id']; ?>, 'active', 'Activate')">
                                                    <i class="fas fa-play"></i> Activate
                                                </button>
                                            <?php elseif ($round['status'] === 'active'): ?>
                                                <button class="btn btn-warning btn-sm" onclick="updateRoundStatus(<?php echo $round['id']; ?>, 'completed', 'Close')">
                                                    <i class="fas fa-stop"></i> Close Round
                                                </button>
                                            <?php endif; ?>
                                            
                                            <button class="btn btn-primary btn-sm" onclick="editRound(<?php echo $round['id']; ?>, '<?php echo htmlspecialchars($round['name']); ?>', '<?php echo htmlspecialchars($round['description']); ?>', '<?php echo $round['status']; ?>')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-outline-danger btn-sm" onclick="deleteRound(<?php echo $round['id']; ?>, '<?php echo htmlspecialchars($round['name']); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card-body">
                                <?php if (empty($round['criteria'])): ?>
                                    <div class="text-center py-3">
                                        <i class="fas fa-clipboard-list fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No criteria defined for this round.</p>
                                        <button class="btn btn-outline-primary btn-sm mt-2" onclick="addCriteria(<?php echo $round['id']; ?>, '<?php echo htmlspecialchars($round['name']); ?>')">
                                            <i class="fas fa-plus me-1"></i> Add Criteria
                                        </button>
                                    </div>
                                <?php else: ?>
                                    <h6><i class="fas fa-clipboard-list me-2"></i>Scoring Criteria</h6>
                                    <div class="row">
                                        <?php foreach ($round['criteria'] as $criteria): ?>
                                            <div class="col-md-6">
                                                <div class="criteria-item">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div>
                                                            <strong><?php echo htmlspecialchars($criteria['name']); ?></strong>
                                                            <br><small class="text-muted"><?php echo htmlspecialchars($criteria['description']); ?></small>
                                                            <br><small><strong>Weight:</strong> <?php echo $criteria['weight_percentage']; ?>% | <strong>Score Range:</strong> <?php echo $criteria['max_score']; ?></small>
                                                        </div>
                                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteCriteria(<?php echo $criteria['id']; ?>, '<?php echo htmlspecialchars($criteria['name']); ?>')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    
                                    <!-- Add Another Criteria Button -->
                                    <div class="text-center mt-3">
                                        <?php 
                                        // Calculate current total weight for this round
                                        $total_weight = 0;
                                        foreach ($round['criteria'] as $criteria) {
                                            $total_weight += $criteria['weight_percentage'];
                                        }
                                        ?>
                                        <?php if ($total_weight < 100): ?>
                                            <button class="btn btn-outline-primary btn-sm" onclick="addCriteria(<?php echo $round['id']; ?>, '<?php echo htmlspecialchars($round['name']); ?>')">
                                                <i class="fas fa-plus me-1"></i> Add Another Criteria
                                            </button>
                                            <small class="text-muted d-block mt-1">
                                                Current weight: <?php echo $total_weight; ?>% | Remaining: <?php echo 100 - $total_weight; ?>%
                                            </small>
                                        <?php else: ?>
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                <i class="fas fa-check me-1"></i> Weight Complete (100%)
                                            </button>
                                            <small class="text-success d-block mt-1">
                                                All criteria weights total 100%. No more criteria can be added.
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Add Round Modal -->
    <div class="modal fade" id="addRoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Round</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Round Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="round_order" class="form-label">Round Order</label>
                                <input type="number" class="form-control" id="round_order" name="round_order" min="1" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="upcoming">Upcoming</option>
                                    <option value="active">Active</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_round" class="btn btn-primary">Add Round</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Round Modal -->
    <div class="modal fade" id="editRoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Round</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="round_id" id="edit_round_id">
                        
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Round Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="upcoming">Upcoming</option>
                                <option value="active">Active</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_round" class="btn btn-primary">Update Round</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Add Criteria Modal -->
    <div class="modal fade" id="addCriteriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Criteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="round_id" id="criteria_round_id">
                        
                        <p>Adding criteria to: <strong id="criteria_round_name"></strong></p>
                        
                        <div class="mb-3">
                            <label for="criteria_name" class="form-label">Criteria Name</label>
                            <input type="text" class="form-control" id="criteria_name" name="name" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="criteria_description" class="form-label">Description</label>
                            <textarea class="form-control" id="criteria_description" name="description" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="weight_percentage" class="form-label">Weight (%)</label>
                                <input type="number" class="form-control" id="weight_percentage" name="weight_percentage" min="1" max="100" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="max_score" class="form-label">Max Score</label>
                                <input type="number" class="form-control" id="max_score" name="max_score" step="0.1" value="10" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="criteria_order" class="form-label">Criteria Order</label>
                                <input type="number" class="form-control" id="criteria_order" name="criteria_order" min="1" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_criteria" class="btn btn-primary">Add Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Round Modal -->
    <div class="modal fade" id="deleteRoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Round</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="round_id" id="delete_round_id">
                        
                        <p>Are you sure you want to delete: <strong id="delete_round_name"></strong>?</p>
                        <p class="text-danger">This will also delete all associated criteria. This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_round" class="btn btn-danger">Delete Round</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Criteria Modal -->
    <div class="modal fade" id="deleteCriteriaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Criteria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="criteria_id" id="delete_criteria_id">
                        
                        <p>Are you sure you want to delete: <strong id="delete_criteria_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete_criteria" class="btn btn-danger">Delete Criteria</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Round Status Update Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Round Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="round_id" id="status_round_id">
                        <input type="hidden" name="new_status" id="status_new_status">
                        
                        <p id="status_confirmation_text"></p>
                        
                        <div class="alert alert-info">
                            <strong>Note:</strong>
                            <ul class="mb-0">
                                <li><strong>Activate:</strong> Judges can start scoring this round</li>
                                <li><strong>Close:</strong> Judges can no longer modify scores, results are finalized</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_round_status" class="btn btn-primary" id="status_confirm_btn">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Handle form submissions with fetch API
        document.addEventListener('DOMContentLoaded', function() {
            // Add form submission handlers
            const forms = ['addRoundForm', 'editRoundForm', 'addCriteriaForm', 'deleteRoundForm', 'deleteCriteriaForm', 'statusForm'];
            
            forms.forEach(formId => {
                const form = document.getElementById(formId);
                if (form) {
                    form.addEventListener('submit', handleFormSubmit);
                }
            });
        });
        
        async function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const action = form.getAttribute('action');
            const method = form.getAttribute('method') || 'POST';

            try {
                const response = await fetch(action, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="X-CSRF-TOKEN"]')?.content || ''
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccess(result.message || 'Operation completed successfully');
                    // Close any open modals
                    const modal = bootstrap.Modal.getInstance(document.querySelector('.modal.show'));
                    if (modal) modal.hide();
                    // Reload the page after 1.5 seconds
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    throw new Error(result.message || 'An error occurred');
                }
            } catch (error) {
                showError(error.message || 'An error occurred while processing your request');
            }
        }
        
        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
        
        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        }
    </script>
    <script>
        function editRound(round) {
            // If round is an ID, fetch the round data
            if (typeof round === 'number' || (typeof round === 'string' && !isNaN(round))) {
                // You'll need to implement an API endpoint to fetch round data
                fetch(`/api/rounds/${round}`)
                    .then(response => response.json())
                    .then(data => {
                        populateEditForm(data);
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Failed to load round data');
                    });
            } else {
                // If round is already an object
                populateEditForm(round);
            }
        }
        
        function populateEditForm(round) {
            document.getElementById('edit_round_id').value = round.id;
            document.getElementById('edit_name').value = round.name || '';
            document.getElementById('edit_description').value = round.description || '';
            document.getElementById('edit_status').value = round.status || 'upcoming';
            
            new bootstrap.Modal(document.getElementById('editRoundModal')).show();
        }
        
        function addCriteria(roundId, roundName) {
            document.getElementById('criteria_round_id').value = roundId;
            document.getElementById('criteria_round_name').textContent = roundName;
            
            new bootstrap.Modal(document.getElementById('addCriteriaModal')).show();
        }
        
        function deleteRound(roundId, roundName) {
            document.getElementById('delete_round_id').value = roundId;
            document.getElementById('delete_round_name').textContent = roundName;
            
            new bootstrap.Modal(document.getElementById('deleteRoundModal')).show();
        }
        
        function deleteCriteria(criteriaId, criteriaName) {
            document.getElementById('delete_criteria_id').value = criteriaId;
            document.getElementById('delete_criteria_name').textContent = criteriaName;
            
            new bootstrap.Modal(document.getElementById('deleteCriteriaModal')).show();
        }
        
        function updateRoundStatus(roundId, newStatus, actionText) {
            document.getElementById('status_round_id').value = roundId;
            document.getElementById('status_new_status').value = newStatus;
            document.getElementById('status_confirmation_text').textContent = 
                `Are you sure you want to ${actionText.toLowerCase()} this round?`;
            document.getElementById('status_confirm_btn').textContent = actionText;
            
            if (newStatus === 'active') {
                document.getElementById('status_confirm_btn').className = 'btn btn-success';
            } else if (newStatus === 'completed') {
                document.getElementById('status_confirm_btn').className = 'btn btn-warning';
            }
            
            new bootstrap.Modal(document.getElementById('statusModal')).show();
        }
    </script>
</body>
</html>