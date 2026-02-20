<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/session.php';

if (is_logged_in()) {
    redirect_to_dashboard();
}

$error = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration - <?php echo APP_NAME; ?></title>
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
            flex: 1.2;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fff;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 550px;
        }

        @media (max-width: 900px) {
            .split-layout {
                flex-direction: column;
            }

            .split-visual {
                display: none;
            }

            .split-form {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="split-layout">
        <!-- Visual Side -->
        <div class="split-visual">
            <div style="text-align: center; z-index: 2;">
                <h1 style="font-size: 2.5rem; color: #2B2A2A; margin-bottom: 1rem; font-weight: 800; letter-spacing: -0.05em;">Join our Community!</h1>
                <p style="font-size: 1.25rem; color: #64748b; max-width: 400px; margin: 0 auto 3rem;">
                    Create a free client account to bid on thousands of verified used machines and vehicles.
                </p>
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-hi-five.png" alt="Register Illustration" style="max-width: 80%; height: auto; transform: scale(1.1);">
            </div>
            <!-- Circles -->
            <div style="position: absolute; top: -50px; left: -50px; width: 300px; height: 300px; background: rgba(90, 122, 205, 0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -50px; right: -50px; width: 400px; height: 400px; background: rgba(254, 176, 93, 0.1); border-radius: 50%;"></div>
        </div>

        <!-- Form Side -->
        <div class="split-form">
            <div class="form-container">
                <div class="mb-4">
                    <a href="../index.php" style="text-decoration: none; color: #2B2A2A; font-weight: 800; font-size: 1.25rem;">&larr; Back</a>
                    <h2 style="font-size: 2rem; margin-top: 1rem; color: #2B2A2A; font-weight: 700;">Create Client Account</h2>
                    <p style="color: var(--text-muted);">Start your journey with <?php echo APP_NAME; ?> today.</p>
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
                                case 'email_exists':
                                    echo 'Email already registered. Try logging in?';
                                    break;
                                case 'validation':
                                    echo 'Please check all fields and try again.';
                                    break;
                                default:
                                    echo 'An error occurred. Please try again.';
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <form action="../auth/register_client_process.php" method="POST" id="clientRegForm">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="name" class="form-label" style="font-weight: 500;">Full Name *</label>
                            <input type="text" id="name" name="name" class="form-control" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>

                        <div class="form-group">
                            <label for="contact" class="form-label" style="font-weight: 500;">Phone Number *</label>
                            <input type="tel" id="contact" name="contact" class="form-control" pattern="[6-9][0-9]{9}" placeholder="9876543210" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="email" class="form-label" style="font-weight: 500;">Email Address *</label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="john@example.com" style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="address" class="form-label" style="font-weight: 500;">Address *</label>
                        <textarea id="address" name="address" class="form-control" rows="2" required style="background: #f8fafc; border-color: #e2e8f0;"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label for="dealership_details" class="form-label" style="font-weight: 500;">Organization / Dealership (Optional)</label>
                        <input type="text" id="dealership_details" name="dealership_details" class="form-control" placeholder="Company Name" style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                        <div class="form-group">
                            <label for="password" class="form-label" style="font-weight: 500;">Password *</label>
                            <input type="password" id="password" name="password" class="form-control" minlength="8" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>

                        <div class="form-group">
                            <label for="confirm_password" class="form-label" style="font-weight: 500;">Confirm Password *</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" minlength="8" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1rem; font-weight: 600;">Create Account</button>
                </form>

                <div class="text-center mt-4" style="color: var(--text-muted); font-size: 0.9rem;">
                    Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Log in</a>
                </div>
                <div class="text-center mt-2" style="color: var(--text-muted); font-size: 0.9rem;">
                    Want to sell products? <a href="register_company.php" style="color: var(--secondary); font-weight: 600; text-decoration: none;">Register as Company</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('clientRegForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }
        });
    </script>
</body>

</html>