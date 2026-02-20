<?php
$page_title = 'My Profile';
require_once __DIR__ . '/includes/header.php';
require_login();

$user_id = get_user_id();
$role = get_user_role();
$user = fetch_one("SELECT * FROM users WHERE user_id = ?", [$user_id]);

$success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize_input($_POST['name']);
    $contact = sanitize_input($_POST['contact']);
    $address = sanitize_input($_POST['address']);
    $avatar = sanitize_input($_POST['avatar'] ?? '');

    $update = execute_query("UPDATE users SET name = ?, contact = ?, address = ?, avatar = ? WHERE user_id = ?", [$name, $contact, $address, $avatar, $user_id]);

    if ($update) {
        $success = "Profile updated successfully!";
        // Refresh session name if changed
        $_SESSION['name'] = $name;
        $user = fetch_one("SELECT * FROM users WHERE user_id = ?", [$user_id]);
    }
}
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="flex-between" style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h1 style="font-size: 1.5rem;">My Profile</h1>
            <span class="badge badge-primary"><?php echo ucfirst($role); ?></span>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required value="<?php echo htmlspecialchars($user['name']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="background: var(--bg-body);">
                <p style="font-size: 0.75rem; color: var(--text-muted);">Email cannot be changed.</p>
            </div>

            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact" class="form-control" required value="<?php echo htmlspecialchars($user['contact']); ?>">
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Select Avatar</label>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 10px;">
                    <?php
                    $avatar_dir = __DIR__ . '/assets/images/Avatar/';
                    $avatars = glob($avatar_dir . '*.png');
                    foreach ($avatars as $avatar_path):
                        $avatar_file = basename($avatar_path);
                        $is_selected = ($user['avatar'] === $avatar_file) ? 'border-color: var(--primary); transform: scale(1.1); box-shadow: 0 4px 12px rgba(254, 176, 93, 0.4);' : 'border-color: transparent; opacity: 0.8;';
                    ?>
                        <label style="cursor: pointer; text-align: center;">
                            <input type="radio" name="avatar" value="<?php echo $avatar_file; ?>" style="display: none;" <?php echo ($user['avatar'] === $avatar_file) ? 'checked' : ''; ?> onchange="selectAvatar(this)">
                            <div class="avatar-option" style="width: 60px; height: 60px; border-radius: 50%; overflow: hidden; border: 3px solid transparent; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); <?php echo $is_selected; ?>">
                                <img src="<?php echo APP_URL; ?>/assets/images/Avatar/<?php echo $avatar_file; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
                <script>
                    function selectAvatar(input) {
                        document.querySelectorAll('.avatar-option').forEach(el => {
                            el.style.borderColor = 'transparent';
                            el.style.transform = 'scale(1)';
                            el.style.boxShadow = 'none';
                            el.style.opacity = '0.8';
                        });
                        const selected = input.nextElementSibling;
                        selected.style.borderColor = 'var(--primary)';
                        selected.style.transform = 'scale(1.1)';
                        selected.style.boxShadow = '0 4px 12px rgba(254, 176, 93, 0.4)';
                        selected.style.opacity = '1';
                    }
                </script>
            </div>

            <div class="flex-between" style="margin-top: 2rem;">
                <a href="<?php echo $role; ?>/dashboard.php" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>