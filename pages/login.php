<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';

// If already logged in, redirect to dashboard
if (is_logged_in()) {
    redirect_to_dashboard();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
$success = isset($_GET['success']) ? $_GET['success'] : '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="../assets/css/modern.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            background: #fff;
        }

        .split-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .split-visual {
            flex: 1;
            background: #FFF7ED;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
            overflow: hidden;
            border-right: 1px solid #fed7aa;
        }

        .split-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fff;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
        }

        @media (max-width: 900px) {
            .split-layout {
                flex-direction: column;
            }

            .split-visual {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- Left Side: Visual -->
        <div class="split-visual">
            <div style="text-align: center; z-index: 2;">
                <h1 style="font-size: 2.5rem; color: #2B2A2A; margin-bottom: 1rem; font-weight: 800; letter-spacing: -0.05em;">Welcome Back!</h1>
                <p style="font-size: 1.25rem; color: #64748b; max-width: 400px; margin: 0 auto 3rem;">
                    Sign in to access your dashboard, manage your bids, and track your auctions.
                </p>
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-handshake.png" alt="Login Illustration" style="max-width: 80%; height: auto; transform: scale(1.1);">
            </div>
            <!-- Abstract Decorations -->
            <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: rgba(254, 176, 93, 0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -50px; right: -50px; width: 400px; height: 400px; background: rgba(90, 122, 205, 0.1); border-radius: 50%;"></div>
        </div>

        <!-- Right Side: Form -->
        <div class="split-form">
            <div class="form-container">
                <div class="text-center mb-4">
                    <a href="../index.php" style="text-decoration: none;">
                        <span style="font-size: 1.5rem; font-weight: 800; color: #2B2A2A; text-transform: uppercase;">
                            <?php echo APP_NAME; ?>
                        </span>
                    </a>
                    <h2 style="font-size: 1.8rem; margin-top: 2rem; color: #2B2A2A;">Sign In to your account</h2>
                    <p style="color: var(--text-muted);">Enter your details to proceed</p>
                </div>

                <?php if ($error): ?>
                    <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <span>
                            <?php
                            switch ($error) {
                                case 'invalid':
                                    echo 'Invalid email or password';
                                    break;
                                case 'inactive':
                                    echo 'Your account is inactive.';
                                    break;
                                case 'login_required':
                                    echo 'Please login to continue';
                                    break;
                                case 'access_denied':
                                    echo 'Access denied.';
                                    break;
                                default:
                                    echo 'An error occurred. Please try again.';
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($success === 'registered'): ?>
                    <div style="background: #dcfce7; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                        Registration successful! Please login.
                    </div>
                <?php endif; ?>

                <form action="../auth/login_process.php" method="POST">
                    <?php if ($redirect): ?>
                        <input type="hidden" name="redirect" value="<?php echo htmlspecialchars($redirect); ?>">
                    <?php endif; ?>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="email" class="form-label" style="font-weight: 500; color: #2B2A2A;">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus placeholder="name@example.com" style="height: 48px; background: #f8fafc; border: 1px solid #e2e8f0;">
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                            <label for="password" class="form-label" style="font-weight: 500; color: #2B2A2A; margin: 0;">Password</label>
                            <a href="#" style="font-size: 0.875rem; color: var(--primary); text-decoration: none;">Forgot password?</a>
                        </div>
                        <input type="password" id="password" name="password" class="form-control" required placeholder="••••••••" style="height: 48px; background: #f8fafc; border: 1px solid #e2e8f0;">
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1rem; margin-top: 1rem;">Sign In</button>
                </form>

                <!-- Quick Login Demo Section -->
                <?php
                // Fetch demo users dynamically
                require_once __DIR__ . '/../includes/database.php';
                $demo_users = [];
                try {
                    $roles = ['admin', 'company', 'client'];
                    foreach ($roles as $role) {
                        $user = fetch_one("SELECT email FROM users WHERE role = ? AND status = 'active' LIMIT 1", [$role]);
                        if ($user) {
                            $pass = 'password';
                            if ($role === 'admin') $pass = 'admin123';
                            if ($role === 'company') $pass = 'company123';
                            if ($role === 'client') $pass = 'client123';

                            $demo_users[$role] = ['email' => $user['email'], 'password' => $pass];
                        }
                    }
                } catch (Exception $e) { /* Fail silently */
                }
                ?>

                <?php if (!empty($demo_users)): ?>
                    <div style="margin-top: 2rem; border-top: 1px dashed #e2e8f0; padding-top: 1.5rem;">
                        <p style="text-align: center; color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.5px;">Quick Login (Demo)</p>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; justify-content: center;">
                            <?php if (isset($demo_users['admin'])): ?>
                                <button onclick="fillLogin('<?php echo $demo_users['admin']['email']; ?>', '<?php echo $demo_users['admin']['password']; ?>')" class="btn btn-sm" style="background: #1e293b; color: white; border: none;">Admin</button>
                            <?php endif; ?>
                            <?php if (isset($demo_users['company'])): ?>
                                <button onclick="fillLogin('<?php echo $demo_users['company']['email']; ?>', '<?php echo $demo_users['company']['password']; ?>')" class="btn btn-sm" style="background: #334155; color: white; border: none;">Company</button>
                            <?php endif; ?>
                            <?php if (isset($demo_users['client'])): ?>
                                <button onclick="fillLogin('<?php echo $demo_users['client']['email']; ?>', '<?php echo $demo_users['client']['password']; ?>')" class="btn btn-sm" style="background: #475569; color: white; border: none;">Client</button>
                            <?php endif; ?>
                        </div>
                    </div>
                    <script>
                        function fillLogin(email, password) {
                            document.getElementById('email').value = email;
                            document.getElementById('password').value = password;
                            document.querySelector('form').submit();
                        }
                    </script>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <p style="color: var(--text-muted);">Don't have an account? <br>
                        <a href="register_client.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Register as Client</a> or
                        <a href="register_company.php" style="color: var(--secondary); font-weight: 600; text-decoration: none;">Register as Company</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>