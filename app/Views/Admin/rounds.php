<?= $this->extend('layouts/template') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
  <div class="row">
    <div class="col-12 page-section">
      <div class="d-flex justify-content-between align-items-center mb-1">
        <h2 class="mb-0"><i class="fas fa-trophy me-2"></i> Rounds & Criteria Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRoundModal">
          <i class="fas fa-plus me-2"></i> Add Round
        </button>
      </div>
      <p class="text-muted mb-4">Create rounds, define criteria, and control the competition flow.</p>

      <?php if (!empty($message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($message) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <?= htmlspecialchars($error) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>

      <?php if (empty($rounds_with_criteria)): ?>
        <div class="text-center py-5">
          <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
          <h5>No rounds found</h5>
          <p class="text-muted">Create your first round to set up the competition structure.</p>
        </div>
      <?php else: ?>
        <?php foreach ($rounds_with_criteria as $round): ?>
          <div class="card card-soft">
            <div class="round-header">
              <div class="row align-items-center">
                <div class="col-md-8">
                  <h5 class="mb-0">Round <?= $round['round_order'] ?>: <?= htmlspecialchars($round['name']) ?></h5>
                  <p class="mb-0 opacity-75"><?= htmlspecialchars($round['description'] ?? '') ?></p>
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
                      <button class="btn btn-success btn-sm" onclick="updateRoundStatus(<?= $round['id'] ?>, 'active', 'Activate')">
                        <i class="fas fa-play"></i> Activate
                      </button>
                    <?php elseif ($round['status'] === 'active'): ?>
                      <button class="btn btn-warning btn-sm" onclick="updateRoundStatus(<?= $round['id'] ?>, 'completed', 'Close')">
                        <i class="fas fa-stop"></i> Close Round
                      </button>
                    <?php endif; ?>

                    <button class="btn btn-primary btn-sm" onclick="editRound(<?= $round['id'] ?>, '<?= htmlspecialchars($round['name']) ?>', '<?= htmlspecialchars($round['description'] ?? '') ?>', '<?= $round['status'] ?>')">
                      <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteRound(<?= $round['id'] ?>, '<?= htmlspecialchars($round['name']) ?>')">
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
                  <button class="btn btn-outline-primary btn-sm mt-2" onclick="addCriteria(<?= $round['id'] ?>, '<?= htmlspecialchars($round['name']) ?>')">
                    <i class="fas fa-plus me-1"></i> Add Criteria
                  </button>
                </div>
              <?php else: ?>
                <h6><i class="fas fa-clipboard-list me-2"></i>Scoring Criteria</h6>
                <div class="row">
                  <?php foreach ($round['criteria'] as $criteria): ?>
                    <div class="col-md-6">
                      <div class="criteria-item card-soft p-3">
                        <div class="d-flex justify-content-between align-items-start">
                          <div>
                            <strong><?= htmlspecialchars($criteria['name']) ?></strong>
                            <br><small class="text-muted"><?= htmlspecialchars($criteria['description'] ?? '') ?></small>
                            <br><small><strong>Weight:</strong> <?= (float)$criteria['weight_percentage'] ?>% | <strong>Score Range:</strong> <?= (float)$criteria['max_score'] ?></small>
                          </div>
                          <button class="btn btn-outline-danger btn-sm" onclick="deleteCriteria(<?= $criteria['id'] ?>, '<?= htmlspecialchars($criteria['name']) ?>')">
                            <i class="fas fa-trash"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
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
      <form id="editRoundForm" method="POST" action="#">
        <div class="modal-body">
          <?= csrf_field() ?>
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

<!-- Delete Round Modal -->
<div class="modal fade" id="deleteRoundModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delete Round</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="deleteRoundForm" method="POST" action="#">
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

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Update Round Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="statusForm" method="POST" action="#">
        <div class="modal-body">
          <?= csrf_field() ?>
          <input type="hidden" name="_method" value="PUT">
          <input type="hidden" name="round_id" id="status_round_id">
          <input type="hidden" name="new_status" id="status_new_status">
          <p id="status_confirmation_text"></p>
          <div class="alert alert-info">
            <strong>Note:</strong>
            <ul class="mb-0">
              <li><strong>Activate:</strong> judges can start scoring this round</li>
              <li><strong>Close:</strong> judges can no longer score this round</li>
            </ul>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" id="status_confirm_btn" class="btn btn-primary">Confirm</button>
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
      <form id="addCriteriaForm" method="POST" action="#">
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
              <input type="number" class="form-control" id="weight_percentage" name="weight" min="1" max="100" required>
            </div>
            <div class="col-md-4 mb-3">
              <label for="max_score" class="form-label">Max Score</label>
              <input type="number" class="form-control" id="max_score" name="max_score" step="0.1" value="10" required>
            </div>
            <div class="col-md-4 mb-3">
              <label for="criteria_order" class="form-label">Criteria Order</label>
              <input type="number" class="form-control" id="criteria_order" name="sort_order" min="1" required>
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

<!-- Add Round Modal -->
<div class="modal fade" id="addRoundModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Round</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="addRoundForm" method="POST" action="<?= site_url('rounds') ?>">
        <div class="modal-body">
          <?= csrf_field() ?>
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

<!-- Minimal JS to submit forms via fetch and refresh -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const addForm = document.getElementById('addRoundForm');
    if (addForm) {
      addForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        const fd = new FormData(addForm);
        const res = await fetch(addForm.action, {
          method: 'POST',
          headers: { 'X-Requested-With': 'XMLHttpRequest' },
          body: fd
        });
        const data = await res.json();
        if (res.ok) {
          window.location.reload();
        } else {
          alert(data.error || 'Failed');
        }
      });
    }
    // wire other forms
    const editForm = document.getElementById('editRoundForm');
    if (editForm) {
      editForm.addEventListener('submit', submitJson);
    }
    const delForm = document.getElementById('deleteRoundForm');
    if (delForm) {
      delForm.addEventListener('submit', submitJson);
    }
    const statusForm = document.getElementById('statusForm');
    if (statusForm) {
      statusForm.addEventListener('submit', submitJson);
    }
    const addCritForm = document.getElementById('addCriteriaForm');
    if (addCritForm) {
      addCritForm.addEventListener('submit', submitJson);
    }
  });

  async function submitJson(e) {
    e.preventDefault();
    const form = e.target;
    const fd = new FormData(form);
    const res = await fetch(form.action, {
      method: form.method || 'POST',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      body: fd
    });
    try {
      const data = await res.json();
      if (!res.ok) throw new Error(data.error || 'Failed');
      window.location.reload();
    } catch (err) {
      alert(err.message || 'Request failed');
    }
  }

  function editRound(id, name, description, status) {
    document.getElementById('edit_round_id').value = id;
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_status').value = status || 'upcoming';
    document.getElementById('editRoundForm').action = '<?= site_url('rounds') ?>/' + id + '/update';
    new bootstrap.Modal(document.getElementById('editRoundModal')).show();
  }

  function deleteRound(id, name) {
    document.getElementById('delete_round_id').value = id;
    document.getElementById('delete_round_name').textContent = name;
    document.getElementById('deleteRoundForm').action = '<?= site_url('rounds') ?>/' + id + '/delete';
    new bootstrap.Modal(document.getElementById('deleteRoundModal')).show();
  }

  function updateRoundStatus(id, newStatus, actionText) {
    document.getElementById('status_round_id').value = id;
    document.getElementById('status_new_status').value = newStatus;
    document.getElementById('status_confirmation_text').textContent = 'Are you sure you want to ' + actionText.toLowerCase() + ' this round?';
    const btn = document.getElementById('status_confirm_btn');
    btn.textContent = actionText;
    btn.className = 'btn ' + (newStatus === 'active' ? 'btn-success' : 'btn-warning');
    document.getElementById('statusForm').action = '<?= site_url('rounds') ?>/' + id + '/status';
    new bootstrap.Modal(document.getElementById('statusModal')).show();
  }

  function addCriteria(roundId, roundName) {
    document.getElementById('criteria_round_id').value = roundId;
    document.getElementById('criteria_round_name').textContent = roundName;
    document.getElementById('addCriteriaForm').action = '<?= site_url('rounds') ?>/' + roundId + '/criteria';
    new bootstrap.Modal(document.getElementById('addCriteriaModal')).show();
  }
</script>

<?= $this->endSection() ?>