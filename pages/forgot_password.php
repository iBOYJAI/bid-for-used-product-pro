<?php
$page_title = 'Forgot Password';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mock functionality for now as per project scope (usually requires mail server)
    $email = $_POST['email'] ?? '';
    // In a real app: check DB, generate token, send email.
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "If an account exists for this email, you will receive password reset instructions.";
    } else {
        $error = "Please enter a valid email address.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/modern.css">
</head>

<body style="background: radial-gradient(circle at center, #e0e7ff 0%, #f8fafc 100%); display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 1rem;">

    <div class="card" style="max-width: 450px; width: 100%; text-align: center; padding: 2.5rem;">
        <div style="margin-bottom: 2rem;">
            <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-thinking.png" alt="" style="height: 150px; margin-bottom: 1rem;">
            <h1 style="font-size: 1.5rem; color: var(--text-main);">Forgot Password?</h1>
            <p style="color: var(--text-muted); font-size: 0.875rem;">Enter your email to receive reset instructions</p>
        </div>

        <?php if ($message): ?>
            <div class="alert alert-success" style="text-align: left; background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger" style="text-align: left; background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group" style="text-align: left; margin-bottom: 1.5rem;">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="name@example.com" required>
            </div>
            <button type="submit" class="btn btn-primary w-full" style="padding: 0.8rem;">Send Reset Link</button>
        </form>

        <div style="margin-top: 2rem;">
            <a href="login.php" style="color: var(--text-muted); font-size: 0.875rem; text-decoration: none;">&larr; Back to Login</a>
        </div>
    </div>

</body>

</html>