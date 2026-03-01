<?php
$page_title = 'Verify Companies';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

$sql = "SELECT c.*, u.name, u.email, u.contact, u.address, u.created_at 
        FROM companies c 
        INNER JOIN users u ON c.user_id = u.user_id 
        ORDER BY c.verified_status ASC, u.created_at DESC";
$companies = fetch_all($sql);
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Verify Companies</h1>
        <a href="dashboard.php" class="btn btn-secondary">&larr; Dashboard</a>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] === 'verified'): ?>
        <div class="alert alert-success">Company has been verified successfully.</div>
    <?php endif; ?>

    <div class="card" style="overflow-x: auto;">
        <table>
            <thead>
                <tr>
                    <th>Company</th>
                    <th>Owner/Contact</th>
                    <th>GST & Proof</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($companies as $company): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600; font-size: 1rem;"><?php echo htmlspecialchars($company['company_name']); ?></div>
                            <div style="font-size: 0.875rem; color: var(--text-muted);"><?php echo htmlspecialchars($company['address']); ?></div>
                        </td>
                        <td>
                            <div style="font-weight: 500;"><?php echo htmlspecialchars($company['owner_name']); ?></div>
                            <div style="font-size: 0.875rem; color: var(--text-muted);">
                                <?php echo htmlspecialchars($company['email']); ?><br>
                                <?php echo htmlspecialchars($company['contact']); ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-size: 0.875rem;">
                                <strong>GST:</strong> <?php echo htmlspecialchars($company['gst_number'] ?: 'N/A'); ?>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-<?php echo $company['verified_status'] === 'verified' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($company['verified_status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($company['verified_status'] === 'pending'): ?>
                                <a href="verify_company.php?id=<?php echo $company['company_id']; ?>" class="btn btn-sm btn-success" onclick="return confirm('Confirm verification for this company?')">
                                    Approve
                                </a>
                            <?php else: ?>
                                <span class="badge badge-secondary">Verified</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (empty($companies)): ?>
            <p style="padding: 1.5rem; text-align: center; color: var(--text-muted);">No companies found.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>