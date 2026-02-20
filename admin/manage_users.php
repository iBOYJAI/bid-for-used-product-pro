<?php
$page_title = 'Manage Users';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

$filter_role = isset($_GET['role']) ? $_GET['role'] : '';

$sql = "SELECT * FROM users WHERE role != 'admin'";
$params = [];

if ($filter_role) {
    $sql .= " AND role = ?";
    $params[] = $filter_role;
}

$sql .= " ORDER BY created_at DESC";
$users = fetch_all($sql, $params);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Manage Users</h1>
        <div>
            <a href="add_user.php" class="btn btn-primary">+ Add New User</a>
            <a href="dashboard.php" class="btn btn-secondary">&larr; Back to Dashboard</a>
        </div>
    </div>

    <!-- Filter -->
    <div class="card" style="margin-bottom: 2rem; padding: 1rem;">
        <form action="" method="GET" style="display: flex; gap: 1rem; align-items: center;">
            <label for="role" style="font-weight: 500;">Filter by Role:</label>
            <select name="role" id="role" class="form-control" style="width: auto;">
                <option value="">All Users</option>
                <option value="company" <?php echo $filter_role === 'company' ? 'selected' : ''; ?>>Company</option>
                <option value="client" <?php echo $filter_role === 'client' ? 'selected' : ''; ?>>Client</option>
            </select>
            <button type="submit" class="btn btn-primary btn-sm">Apply</button>
            <?php if ($filter_role): ?>
                <a href="manage_users.php" class="btn btn-secondary btn-sm">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($user['name']); ?></div>
                            <div style="font-size: 0.75rem; color: var(--text-muted);">ID: <?php echo $user['user_id']; ?></div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $user['role'] === 'company' ? 'primary' : 'info'; ?>">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($user['contact']); ?></td>
                        <td>
                            <span class="badge badge-<?php echo $user['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($user['status'] === 'active'): ?>
                                <a href="toggle_user_status.php?id=<?php echo $user['user_id']; ?>&status=inactive" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to deactivate this user?')">Deactivate</a>
                            <?php else: ?>
                                <a href="toggle_user_status.php?id=<?php echo $user['user_id']; ?>&status=active" class="btn btn-sm btn-success" onclick="return confirm('Are you sure you want to activate this user?')">Activate</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($users)): ?>
            <p style="padding: 1rem; text-align: center; color: var(--text-muted);">No users found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>