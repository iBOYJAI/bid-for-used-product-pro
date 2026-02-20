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
    <title>Company Registration - <?php echo APP_NAME; ?></title>
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
            flex: 1.5;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: #fff;
            overflow-y: auto;
        }

        .form-container {
            width: 100%;
            max-width: 650px;
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
                <h1 style="font-size: 2.5rem; color: #2B2A2A; margin-bottom: 1rem; font-weight: 800; letter-spacing: -0.05em;">Grow Your Business</h1>
                <p style="font-size: 1.25rem; color: #64748b; max-width: 400px; margin: 0 auto 3rem;">
                    Register as a seller to reach thousands of buyers and auction your used assets.
                </p>
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-project-development.png" alt="Register Company" style="max-width: 90%; height: auto; transform: scale(1);">
            </div>
            <!-- Circles -->
            <div style="position: absolute; top: -50px; right: -50px; width: 300px; height: 300px; background: rgba(90, 122, 205, 0.1); border-radius: 50%;"></div>
            <div style="position: absolute; bottom: -50px; left: -50px; width: 400px; height: 400px; background: rgba(54, 176, 93, 0.1); border-radius: 50%;"></div>
        </div>

        <!-- Form Side -->
        <div class="split-form">
            <div class="form-container">
                <div class="mb-4">
                    <a href="../index.php" style="text-decoration: none; color: #2B2A2A; font-weight: 800; font-size: 1.25rem;">&larr; Back</a>
                    <h2 style="font-size: 2rem; margin-top: 1rem; color: #2B2A2A; font-weight: 700;">Seller Registration</h2>
                    <p style="color: var(--text-muted);">Company details required for verification.</p>
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
                                    echo 'Email already registered';
                                    break;
                                case 'file_upload':
                                    echo 'Error uploading identity proof (Max 5MB PDF/Image)';
                                    break;
                                case 'validation':
                                    echo 'Please check all fields';
                                    break;
                                default:
                                    echo 'An error occurred. Please try again.';
                            }
                            ?>
                        </span>
                    </div>
                <?php endif; ?>

                <form action="../auth/register_company_process.php" method="POST" enctype="multipart/form-data" id="companyRegForm">

                    <h3 style="font-size: 1.1rem; color: var(--primary); margin-bottom: 1rem; font-weight: 700;">Company Details</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="company_name" class="form-label" style="font-weight: 500;">Company Name *</label>
                            <input type="text" id="company_name" name="company_name" class="form-control" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>

                        <div class="form-group">
                            <label for="owner_name" class="form-label" style="font-weight: 500;">Owner Name *</label>
                            <input type="text" id="owner_name" name="owner_name" class="form-control" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="gst_number" class="form-label" style="font-weight: 500;">GST Number</label>
                            <input type="text" id="gst_number" name="gst_number" class="form-control" placeholder="Optional" style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>

                        <div class="form-group">
                            <label for="identity_proof" class="form-label" style="font-weight: 500;">Identity Proof (Max 5 files) *</label>
                            <input type="file" id="identity_proof" name="identity_proof[]" class="form-control" accept=".pdf,.jpg,.jpeg,.png" required multiple style="background: #f8fafc; border-color: #e2e8f0; height: 45px; padding-top: 0.6rem;">
                            <small id="file-count" style="display: none; margin-top: 0.5rem; font-weight: 500;"></small>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1.5rem;">
                        <label for="address" class="form-label" style="font-weight: 500;">Company Address *</label>
                        <textarea id="address" name="address" class="form-control" rows="2" required style="background: #f8fafc; border-color: #e2e8f0;"></textarea>
                    </div>

                    <h3 style="font-size: 1.1rem; color: var(--primary); margin-bottom: 1rem; font-weight: 700;">Contact & Security</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label for="email" class="form-label" style="font-weight: 500;">Email Address *</label>
                            <input type="email" id="email" name="email" class="form-control" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>

                        <div class="form-group">
                            <label for="contact" class="form-label" style="font-weight: 500;">Contact Number *</label>
                            <input type="tel" id="contact" name="contact" class="form-control" pattern="[6-9][0-9]{9}" placeholder="9876543210" required style="background: #f8fafc; border-color: #e2e8f0; height: 45px;">
                        </div>
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

                    <button type="submit" class="btn btn-primary w-full" style="padding: 1rem; font-size: 1rem; font-weight: 600;">Register Company</button>
                </form>

                <div class="text-center mt-4" style="color: var(--text-muted); font-size: 0.9rem;">
                    Already have an account? <a href="login.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Log in</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password validation
        document.getElementById('companyRegForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return;
            }

            // File count validation
            const files = document.getElementById('identity_proof').files;
            if (files.length > 5) {
                e.preventDefault();
                alert('You can upload a maximum of 5 identity proof files!');
                return;
            }
        });

        // File count display
        document.getElementById('identity_proof').addEventListener('change', function() {
            const files = this.files;
            const countDisplay = document.getElementById('file-count');

            if (files.length > 0) {
                countDisplay.style.display = 'block';
                if (files.length > 5) {
                    countDisplay.style.color = '#dc2626';
                    countDisplay.innerHTML = `⚠️ ${files.length} files selected (Maximum 5 allowed)`;
                } else {
                    countDisplay.style.color = '#16a34a';
                    countDisplay.innerHTML = `✓ ${files.length} file(s) selected`;
                }
            } else {
                countDisplay.style.display = 'none';
            }
        });
    </script>
</body>

</html>