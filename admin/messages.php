<?php
$page_title = 'Contact Messages';
require_once __DIR__ . '/../includes/header.php';
require_login();

// Restrict to admin
if (get_user_role() !== 'admin') {
    die("Access Denied");
}

$messages = fetch_all("SELECT cm.*, u.role, u.name as user_name 
                       FROM contact_messages cm 
                       LEFT JOIN users u ON cm.user_id = u.user_id 
                       ORDER BY cm.created_at DESC");
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <h1 style="margin-bottom: 2rem;">Inbox (Contact Us)</h1>

    <?php if (empty($messages)): ?>
        <div class="card text-center" style="padding: 3rem;">
            <p style="color: var(--text-muted);">No messages found.</p>
        </div>
    <?php else: ?>
        <div class="card" style="padding: 0; overflow: hidden;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                    <tr>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #64748b;">Source</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #64748b;">Sender</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #64748b;">Subject</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #64748b;">Date</th>
                        <th style="padding: 1rem; text-align: left; font-weight: 600; color: #64748b;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $msg): ?>
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem;">
                                <?php if ($msg['role']): ?>
                                    <span class="badge badge-<?php echo $msg['role'] === 'company' ? 'primary' : 'success'; ?>">
                                        <?php echo ucfirst($msg['role']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Guest</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem;">
                                <div style="font-weight: 500;"><?php echo htmlspecialchars($msg['name']); ?></div>
                                <div style="font-size: 0.85rem; color: var(--text-muted);"><?php echo htmlspecialchars($msg['email']); ?></div>
                                <?php if ($msg['user_id']): ?>
                                    <div style="font-size: 0.75rem; color: var(--primary);">ID: <?php echo $msg['user_id']; ?></div>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 1rem; max-width: 300px;">
                                <div style="font-weight: 500; margin-bottom: 0.25rem;"><?php echo htmlspecialchars($msg['subject']); ?></div>
                                <div style="font-size: 0.9rem; color: var(--text-light); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%;">
                                    <?php echo htmlspecialchars($msg['message']); ?>
                                </div>
                            </td>
                            <td style="padding: 1rem; white-space: nowrap; color: var(--text-muted); font-size: 0.9rem;">
                                <?php echo format_date($msg['created_at']); ?>
                            </td>
                            <td style="padding: 1rem;">
                                <button onclick="alert('<?php echo htmlspecialchars(addslashes($msg['message'])); ?>')" class="btn btn-sm btn-secondary">View</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>