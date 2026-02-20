<?php
$page_title = 'Notifications';
require_once __DIR__ . '/includes/header.php';
require_login();

$user_id = get_user_id();
$notifications = get_notifications($user_id);

// Mark all displayed as read
foreach ($notifications as $n) {
    if (!$n['is_read']) {
        mark_as_read($n['notification_id'], $user_id);
    }
}
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <h1 style="font-size: 1.75rem; margin-bottom: 2rem;">Notifications</h1>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <?php if (empty($notifications)): ?>
            <p style="text-align: center; color: var(--text-muted); padding: 2rem;">No notifications yet.</p>
        <?php else: ?>
            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                <?php foreach ($notifications as $n): ?>
                    <?php
                    $link = $n['target_url'] ? APP_URL . '/' . ltrim($n['target_url'], '/') : '#';
                    $clickable = (bool)$n['target_url'];
                    ?>
                    <a href="<?php echo htmlspecialchars($link); ?>" class="notification-item" style="text-decoration: none; color: inherit; display: block;">
                        <div style="padding: 1.25rem; border-radius: 0.5rem; background: white; border: 1px solid var(--border-color); transition: all 0.2s; <?php echo !$n['is_read'] ? 'border-left: 4px solid var(--primary); background: #f8fafc;' : ''; ?>:hover { transform: translateY(-2px); box-shadow: var(--shadow-sm); }">
                            <div class="flex-between" style="margin-bottom: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <?php if ($n['type'] === 'success'): ?>
                                        <span style="color: #16a34a;">●</span>
                                    <?php elseif ($n['type'] === 'warning'): ?>
                                        <span style="color: #eab308;">●</span>
                                    <?php else: ?>
                                        <span style="color: #3b82f6;">●</span>
                                    <?php endif; ?>
                                    <h3 style="font-size: 1rem; margin: 0; font-weight: 600;"><?php echo htmlspecialchars($n['title']); ?></h3>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--text-muted); white-space: nowrap;"><?php echo format_date($n['created_at']); ?></span>
                            </div>
                            <p style="color: var(--text-light); font-size: 0.95rem; margin: 0; padding-left: 1rem; line-height: 1.5;"><?php echo htmlspecialchars($n['message']); ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>