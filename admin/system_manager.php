<?php
$page_title = 'System Manager';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

// Master password constant
define('MASTER_PASSWORD', 'iBOY#2026-200cr@iSoulSync');

// Check if authenticated in this session
$is_authenticated = isset($_SESSION['system_manager_auth']) && $_SESSION['system_manager_auth'] === true;

// Handle authentication
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['authenticate'])) {
    if ($_POST['password'] === MASTER_PASSWORD) {
        $_SESSION['system_manager_auth'] = true;
        $is_authenticated = true;
        header('Location: system_manager.php');
        exit;
    } else {
        $auth_error = 'Invalid password';
    }
}

// Logout from system manager
if (isset($_GET['logout'])) {
    unset($_SESSION['system_manager_auth']);
    header('Location: system_manager.php');
    exit;
}
?>

<style>
    .system-manager-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
    }

    .auth-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        backdrop-filter: blur(10px);
    }

    .auth-modal-content {
        background: white;
        padding: 3rem;
        border-radius: 1rem;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        max-width: 500px;
        width: 90%;
        text-align: center;
    }

    .auth-modal h2 {
        color: #dc2626;
        font-size: 2rem;
        margin-bottom: 1rem;
    }

    .auth-modal p {
        color: #64748b;
        margin-bottom: 2rem;
    }

    .auth-input {
        width: 100%;
        padding: 1rem;
        font-size: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
        font-family: monospace;
    }

    .auth-input:focus {
        outline: none;
        border-color: #dc2626;
    }

    .danger-zone {
        background: linear-gradient(135deg, #fee2e2 0%, #fef2f2 100%);
        border: 2px solid #dc2626;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .warning-zone {
        background: linear-gradient(135deg, #fef3c7 0%, #fffbeb 100%);
        border: 2px solid #f59e0b;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .safe-zone {
        background: linear-gradient(135deg, #dbeafe 0%, #f0f9ff 100%);
        border: 2px solid #3b82f6;
        border-radius: 1rem;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .action-button {
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
    }

    .btn-danger {
        background: #dc2626;
        color: white;
    }

    .btn-danger:hover {
        background: #b91c1c;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
    }

    .btn-warning {
        background: #f59e0b;
        color: white;
    }

    .btn-warning:hover {
        background: #d97706;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(245, 158, 11, 0.3);
    }

    .btn-primary {
        background: #3b82f6;
        color: white;
    }

    .btn-primary:hover {
        background: #2563eb;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
    }

    .mode-selector {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin: 1.5rem 0;
    }

    .mode-card {
        padding: 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.3s;
    }

    .mode-card:hover {
        border-color: #dc2626;
        transform: translateY(-2px);
    }

    .mode-card.selected {
        border-color: #dc2626;
        background: #fee2e2;
    }

    .mode-card h4 {
        margin-bottom: 0.5rem;
        color: #1e293b;
    }

    .mode-card p {
        font-size: 0.875rem;
        color: #64748b;
    }

    .status-log {
        background: #1e293b;
        color: #10b981;
        padding: 1.5rem;
        border-radius: 0.5rem;
        font-family: monospace;
        max-height: 300px;
        overflow-y: auto;
        margin-top: 1rem;
        display: none;
    }

    .status-log.active {
        display: block;
    }

    .user-selector {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
        margin: 1rem 0;
    }

    .password-display {
        background: #1e293b;
        color: #10b981;
        padding: 1.5rem;
        border-radius: 0.5rem;
        font-family: monospace;
        font-size: 1.25rem;
        margin: 1rem 0;
        display: none;
        text-align: center;
    }

    .password-display.active {
        display: block;
    }
</style>

<?php if (!$is_authenticated): ?>
    <div class="auth-modal">
        <div class="auth-modal-content">
            <h2>🔐 System Manager Access</h2>
            <p><strong>⚠️ WARNING:</strong> This area contains destructive system operations.<br>Authentication required to proceed.</p>

            <?php if (isset($auth_error)): ?>
                <div style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <?php echo htmlspecialchars($auth_error); ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <input type="password" name="password" class="auth-input" placeholder="Enter master password" required autofocus>
                <button type="submit" name="authenticate" class="action-button btn-danger" style="width: 100%;">
                    Authenticate
                </button>
            </form>

            <a href="dashboard.php" style="display: inline-block; margin-top: 1rem; color: #64748b;">← Back to Dashboard</a>
        </div>
    </div>
<?php else: ?>

    <div class="system-manager-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 2rem; color: #1e293b;">⚙️ System Manager</h1>
                <p style="color: #64748b;">Advanced system administration and maintenance tools</p>
            </div>
            <a href="?logout=1" class="action-button btn-warning">🚪 Logout</a>
        </div>

        <!-- 1. DATABASE WIPE -->
        <div class="danger-zone">
            <h3 style="color: #dc2626; margin-bottom: 1rem;">💾 Database Wipe</h3>
            <p style="margin-bottom: 1.5rem; color: #64748b;">Select a wipe mode and execute database cleanup</p>

            <div class="mode-selector">
                <div class="mode-card" data-mode="selective">
                    <h4>Mode 1: Selective</h4>
                    <p>Keep one user by ID and password, wipe everything else</p>
                </div>
                <div class="mode-card" data-mode="role-based">
                    <h4>Mode 2: Role-Based</h4>
                    <p>Keep admin, company, and client users, wipe all data</p>
                </div>
                <div class="mode-card selected" data-mode="complete">
                    <h4>Mode 3: Complete</h4>
                    <p>Full wipe, only default admin remains</p>
                </div>
            </div>

            <div id="selective-options" style="display: none; margin: 1rem 0;">
                <input type="number" id="keep-user-id" class="auth-input" placeholder="User ID to keep" style="width: auto; display: inline-block; margin-right: 1rem;">
                <input type="password" id="keep-user-pass" class="auth-input" placeholder="User password to verify" style="width: auto; display: inline-block;">
            </div>

            <button onclick="executeDbWipe()" class="action-button btn-danger">
                🗑️ Execute Database Wipe
            </button>

            <div id="db-log" class="status-log"></div>
        </div>

        <!-- 2. UPLOAD FOLDER WIPE -->
        <div class="danger-zone">
            <h3 style="color: #dc2626; margin-bottom: 1rem;">📁 Upload Folder Wipe</h3>
            <p style="margin-bottom: 1.5rem; color: #64748b;">Permanently delete all files in the uploads directory</p>

            <div style="background: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <p><strong>Current Status:</strong> <span id="upload-stats">Loading...</span></p>
            </div>

            <button onclick="executeUploadWipe()" class="action-button btn-danger">
                🗑️ Wipe Upload Folder
            </button>

            <div id="upload-log" class="status-log"></div>
        </div>

        <!-- 3. SELF-DESTRUCT -->
        <div class="danger-zone">
            <h3 style="color: #dc2626; margin-bottom: 1rem;">💣 Complete System Wipe (Self-Destruct)</h3>
            <p style="margin-bottom: 1.5rem; color: #64748b;">
                <strong>⚠️ NUCLEAR OPTION:</strong> Completely wipe EVERYTHING from the system and reset to factory defaults
            </p>

            <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <p style="color: #dc2626; font-weight: 600;">This will COMPLETELY WIPE:</p>
                <ul style="margin: 0.5rem 0 0 1.5rem; color: #64748b;">
                    <li><strong>All database tables</strong> - Drop and recreate from schema</li>
                    <li><strong>All uploaded files</strong> - Permanently delete everything</li>
                    <li><strong>All users except admin</strong> - Only default admin (ID: 1) remains</li>
                    <li><strong>Complete factory reset</strong> - As if fresh installation</li>
                </ul>
                <p style="color: #dc2626; margin-top: 1rem; font-weight: 600;">
                    ☠️ THIS REMOVES EVERYTHING FROM THE SYSTEM - USE WITH EXTREME CAUTION!
                </p>
            </div>

            <button onclick="executeSelfDestruct()" class="action-button btn-danger">
                💣 COMPLETE SYSTEM WIPE (SELF-DESTRUCT)
            </button>

            <div id="destruct-log" class="status-log"></div>
        </div>

        <!-- 4. BULK PASSWORD RESET -->
        <div class="warning-zone">
            <h3 style="color: #f59e0b; margin-bottom: 1rem;">🔑 Bulk Password Reset</h3>
            <p style="margin-bottom: 1.5rem; color: #64748b;">Select multiple users to reset passwords and display them</p>

            <div style="background: white; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <label style="display: flex; align-items: center; margin-bottom: 0.5rem;">
                    <input type="checkbox" id="select-all-users" style="margin-right: 0.5rem;">
                    <strong>Select All Users</strong>
                </label>
                <div id="user-list" style="max-height: 300px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 0.5rem; padding: 0.5rem;">
                    <!-- User checkboxes will be loaded here -->
                </div>
            </div>

            <button onclick="executeBulkPasswordReset()" class="action-button btn-warning">
                🔑 Reset Selected Passwords
            </button>

            <div id="password-display" class="password-display"></div>
            <div id="reset-log" class="status-log"></div>
        </div>

        <!-- 5. SEED DATA GENERATOR -->
        <div class="safe-zone">
            <h3 style="color: #3b82f6; margin-bottom: 1rem;">🌱 Seed Data Generator</h3>
            <p style="margin-bottom: 1.5rem; color: #64748b;">Generate comprehensive seed data with product images</p>

            <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                <p><strong>This will create:</strong></p>
                <ul style="margin: 0.5rem 0 0 1.5rem; color: #64748b;">
                    <li>Complete SQL seed file with 50+ products</li>
                    <li>Download and save product images locally</li>
                    <li>Tamil Nadu-specific data</li>
                    <li>Downloadable package for offline use</li>
                </ul>
            </div>

            <button onclick="executeSeedGeneration()" class="action-button btn-primary">
                🌱 Generate Seed Data
            </button>

            <div id="seed-log" class="status-log"></div>
        </div>
    </div>

    <script>
        let selectedMode = 'complete';

        // Mode selection
        document.querySelectorAll('.mode-card').forEach(card => {
            card.addEventListener('click', function() {
                document.querySelectorAll('.mode-card').forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
                selectedMode = this.dataset.mode;

                // Show/hide selective options
                document.getElementById('selective-options').style.display =
                    selectedMode === 'selective' ? 'block' : 'none';
            });
        });

        // Load users for bulk password reset
        async function loadUsers() {
            try {
                const response = await fetch('api/system_actions.php?action=get_users');
                const data = await response.json();

                if (data.success) {
                    const userList = document.getElementById('user-list');
                    userList.innerHTML = '';

                    data.users.forEach(user => {
                        const label = document.createElement('label');
                        label.style.cssText = 'display: block; padding: 0.5rem; cursor: pointer; border-radius: 0.25rem;';
                        label.onmouseover = () => label.style.background = '#f3f4f6';
                        label.onmouseout = () => label.style.background = 'transparent';

                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'user-checkbox';
                        checkbox.value = user.user_id;
                        checkbox.dataset.name = user.name;
                        checkbox.style.marginRight = '0.5rem';

                        label.appendChild(checkbox);
                        label.appendChild(document.createTextNode(`${user.name} (${user.role}) - ${user.email}`));
                        userList.appendChild(label);
                    });

                    // Select all functionality
                    document.getElementById('select-all-users').addEventListener('change', function() {
                        document.querySelectorAll('.user-checkbox').forEach(cb => {
                            cb.checked = this.checked;
                        });
                    });
                }
            } catch (error) {
                console.error('Error loading users:', error);
            }
        }

        // Execute bulk password reset
        async function executeBulkPasswordReset() {
            const selectedUsers = Array.from(document.querySelectorAll('.user-checkbox:checked'));

            if (selectedUsers.length === 0) {
                alert('Please select at least one user');
                return;
            }

            const confirmMsg = `Reset passwords for ${selectedUsers.length} user(s)?`;
            if (!confirm(confirmMsg)) return;

            const log = document.getElementById('reset-log');
            const display = document.getElementById('password-display');
            log.classList.add('active');
            display.classList.add('active');
            log.innerHTML = `> Resetting ${selectedUsers.length} password(s)...\\n`;
            display.innerHTML = '<strong>New Passwords:</strong><br><br>';

            const results = [];

            for (const checkbox of selectedUsers) {
                const userId = checkbox.value;
                const userName = checkbox.dataset.name;

                try {
                    const response = await fetch('api/system_actions.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: `action=reset_password&user_id=${userId}`
                    });
                    const data = await response.json();

                    if (data.success) {
                        log.innerHTML += `> ✅ ${userName}: ${data.new_password}\\n`;
                        results.push(`<div style="padding: 0.5rem; background: rgba(16, 185, 129, 0.1); margin-bottom: 0.5rem; border-radius: 0.25rem;">${userName}: <strong>${data.new_password}</strong></div>`);
                    } else {
                        log.innerHTML += `> ❌ ${userName}: ${data.error}\\n`;
                    }
                } catch (error) {
                    log.innerHTML += `> ❌ ${userName}: ${error.message}\\n`;
                }
            }

            display.innerHTML += results.join('');
            display.innerHTML += '<br><small>Click anywhere to copy all passwords</small>';
            display.onclick = () => {
                const passwords = selectedUsers.map((cb, i) => {
                    return `${cb.dataset.name}: ${results[i]}`;
                }).join('\\n');
                navigator.clipboard.writeText(log.textContent);
                alert('All passwords copied to clipboard!');
            };

            log.innerHTML += `> ✅ Bulk password reset completed!\\n`;
        }

        // Load upload stats
        async function loadUploadStats() {
            try {
                const response = await fetch('api/system_actions.php?action=get_upload_stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('upload-stats').textContent =
                        `${data.stats.total_files} files (${data.stats.total_size})`;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Execute database wipe
        async function executeDbWipe() {
            if (!confirm('⚠️ WARNING: This will permanently delete data. Continue?')) return;

            const log = document.getElementById('db-log');
            log.classList.add('active');
            log.innerHTML = '> Starting database wipe...\n';

            const params = new URLSearchParams({
                action: 'db_wipe',
                mode: selectedMode
            });

            if (selectedMode === 'selective') {
                params.append('user_id', document.getElementById('keep-user-id').value);
                params.append('user_password', document.getElementById('keep-user-pass').value);
            }

            try {
                const response = await fetch('api/system_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: params
                });
                const data = await response.json();

                log.innerHTML += data.message + '\n';
                if (data.success) {
                    log.innerHTML += '> ✅ Database wipe completed successfully!\n';
                } else {
                    log.innerHTML += '> ❌ Error: ' + data.error + '\n';
                }
            } catch (error) {
                log.innerHTML += '> ❌ Error: ' + error.message + '\n';
            }
        }

        // Execute upload wipe
        async function executeUploadWipe() {
            if (!confirm('⚠️ WARNING: This will permanently delete all uploaded files. Continue?')) return;

            const log = document.getElementById('upload-log');
            log.classList.add('active');
            log.innerHTML = '> Starting upload folder wipe...\n';

            try {
                const response = await fetch('api/system_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=upload_wipe'
                });
                const data = await response.json();

                log.innerHTML += data.message + '\n';
                if (data.success) {
                    log.innerHTML += '> ✅ Upload folder wiped successfully!\n';
                    loadUploadStats(); // Refresh stats
                } else {
                    log.innerHTML += '> ❌ Error: ' + data.error + '\n';
                }
            } catch (error) {
                log.innerHTML += '> ❌ Error: ' + error.message + '\n';
            }
        }

        // Execute self-destruct
        async function executeSelfDestruct() {
            const password = prompt('⚠️ FINAL WARNING: Re-enter master password to confirm SELF-DESTRUCT:');
            if (!password || password !== '<?php echo MASTER_PASSWORD; ?>') {
                alert('Invalid password. Self-destruct cancelled.');
                return;
            }

            const log = document.getElementById('destruct-log');
            log.classList.add('active');
            log.innerHTML = '> 💣 INITIATING SELF-DESTRUCT SEQUENCE...\n';

            try {
                const response = await fetch('api/system_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=self_destruct'
                });
                const data = await response.json();

                log.innerHTML += data.message + '\n';
                if (data.success) {
                    log.innerHTML += '> ✅ Self-destruct completed!\n';
                    log.innerHTML += '> Redirecting to setup wizard in 3 seconds...\n';
                    setTimeout(() => {
                        window.location.href = '../setup_wizard.php';
                    }, 3000);
                } else {
                    log.innerHTML += '> ❌ Error: ' + data.error + '\n';
                }
            } catch (error) {
                log.innerHTML += '> ❌ Error: ' + error.message + '\n';
            }
        }

        // Execute seed generation
        async function executeSeedGeneration() {
            const log = document.getElementById('seed-log');
            log.classList.add('active');
            log.innerHTML = '> Starting seed data generation...\n';

            try {
                const response = await fetch('api/system_actions.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'action=generate_seed'
                });
                const data = await response.json();

                log.innerHTML += data.message + '\n';
                if (data.success) {
                    log.innerHTML += '> ✅ Seed data generated successfully!\n';
                    if (data.download_url) {
                        log.innerHTML += `> <a href="${data.download_url}" style="color: #10b981;">📥 Download Seed Package</a>\n`;
                    }
                } else {
                    log.innerHTML += '> ❌ Error: ' + data.error + '\n';
                }
            } catch (error) {
                log.innerHTML += '> ❌ Error: ' + error.message + '\n';
            }
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadUsers();
            loadUploadStats();
        });
    </script>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>