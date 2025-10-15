<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Judge Management - <?php echo htmlspecialchars($system_name); ?></title>
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
        .judge-photo {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
        }
        
        .status-active {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-inactive {
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
                    <h4 class="mb-4"><?php echo htmlspecialchars($system_name ?? 'Pageant System'); ?></h4>
                    
                    <nav class="nav flex-column">
                        <a class="nav-link" href="/dashboard">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                        <a class="nav-link" href="/contestant">
                            <i class="fas fa-users me-2"></i> Contestants
                        </a>
                        <a class="nav-link active" href="/judges">
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
            <div class="col-md-9 col-lg-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-gavel me-2"></i> Judge Management</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJudgeModal">
                        <i class="fas fa-plus me-2"></i> Add Judge
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
                
                <!-- Judges Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Status</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($judges)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-gavel fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No judges found. Add your first judge to get started.</p>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($judges as $judge): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']); ?></td>
                                                <td><?php echo htmlspecialchars($judge['username']); ?></td>
                                                <td><?php echo htmlspecialchars($judge['email']); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo $judge['status']; ?>">
                                                        <?php echo ucfirst($judge['status']); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M j, Y', strtotime($judge['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary me-1" 
                                                            onclick="editJudge(<?php echo htmlspecialchars(json_encode($judge)); ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-warning me-1" 
                                                            onclick="resetPassword(<?php echo $judge['id']; ?>, '<?php echo htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']); ?>')">
                                                        <i class="fas fa-key"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" 
                                                            onclick="deleteJudge(<?php echo $judge['id']; ?>, '<?php echo htmlspecialchars($judge['first_name'] . ' ' . $judge['last_name']); ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add Judge Modal -->
    <div class="modal fade" id="addJudgeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Judge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                      
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_judge" class="btn btn-primary">Add Judge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Edit Judge Modal -->
    <div class="modal fade" id="editJudgeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Judge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/judges/update" method="POST">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="judge_id" id="edit_judge_id">
                        <input type="hidden" name="_method" value="PUT">
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edit_first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="edit_last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="edit_status" class="form-label">Status</label>
                            <select class="form-control" id="edit_status" name="status" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Judge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Reset Password Modal -->
    <div class="modal fade" id="resetPasswordModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/judges/reset-password" method="POST">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="judge_id" id="reset_judge_id">
                        
                        <p>Reset password for: <strong id="reset_judge_name"></strong></p>
                        
                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                            <div class="form-text">Minimum 6 characters</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning">Reset Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Delete Judge Modal -->
    <div class="modal fade" id="deleteJudgeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Judge</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/judges/delete" method="POST">
                    <div class="modal-body">
                        <?= csrf_field() ?>
                        <input type="hidden" name="judge_id" id="delete_judge_id">
                        <input type="hidden" name="_method" value="DELETE">
                        
                        <p>Are you sure you want to delete: <strong id="delete_judge_name"></strong>?</p>
                        <p class="text-danger">This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Judge</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Handle form submissions with fetch API
        document.addEventListener('DOMContentLoaded', function() {
            // Add Judge Form
            const addForm = document.querySelector('form[action="/judges/create"]');
            if (addForm) {
                addForm.addEventListener('submit', handleFormSubmit);
            }

            // Edit Judge Form
            const editForm = document.querySelector('form[action="/judges/update"]');
            if (editForm) {
                editForm.addEventListener('submit', handleFormSubmit);
            }

            // Reset Password Form
            const resetForm = document.querySelector('form[action="/judges/reset-password"]');
            if (resetForm) {
                resetForm.addEventListener('submit', handleFormSubmit);
            }

            // Delete Judge Form
            const deleteForm = document.querySelector('form[action="/judges/delete"]');
            if (deleteForm) {
                deleteForm.addEventListener('submit', handleFormSubmit);
            }
        });

        async function handleFormSubmit(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const action = form.getAttribute('action');
            const method = form.getAttribute('method');

            try {
                const response = await fetch(action, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="csrf_token"]')?.value || ''
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok) {
                    showSuccess(result.message || 'Operation completed successfully');
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

        function editJudge(judge) {
            document.getElementById('edit_judge_id').value = judge.id;
            document.getElementById('edit_first_name').value = judge.first_name;
            document.getElementById('edit_last_name').value = judge.last_name;
            document.getElementById('edit_email').value = judge.email;
            document.getElementById('edit_status').value = judge.status;
            
            new bootstrap.Modal(document.getElementById('editJudgeModal')).show();
        }
        
        function resetPassword(judgeId, judgeName) {
            document.getElementById('reset_judge_id').value = judgeId;
            document.getElementById('reset_judge_name').textContent = judgeName;
            
            new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
        }
        
        function deleteJudge(judgeId, judgeName) {
            document.getElementById('delete_judge_id').value = judgeId;
            document.getElementById('delete_judge_name').textContent = judgeName;
            
            new bootstrap.Modal(document.getElementById('deleteJudgeModal')).show();
        }
    </script>
</body>
</html>