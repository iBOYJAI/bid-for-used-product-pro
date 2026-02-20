<?php
$page_title = 'Add New User';
require_once __DIR__ . '/../includes/header.php';
require_login('admin');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $role = sanitize_input($_POST['role']);
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $contact = sanitize_input($_POST['contact']);
    $address = sanitize_input($_POST['address']);

    // Check if email exists
    $exists = fetch_one("SELECT user_id FROM users WHERE email = ?", [$email]);

    if ($exists) {
        $error = "Email already registered.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $conn = get_connection();
        $conn->beginTransaction();

        try {
            // Insert user
            $sql = "INSERT INTO users (name, email, password, role, contact, address, status, created_at) VALUES (?, ?, ?, ?, ?, ?, 'active', NOW())";
            execute_query($sql, [$name, $email, $hashed_password, $role, $contact, $address]);
            $user_id = $conn->lastInsertId();

            // If company, insert into companies table
            if ($role === 'company') {
                $gst = sanitize_input($_POST['gst'] ?? 'Pending');
                // Schema: company_id, user_id, company_name, owner_name, gst_number, identity_proof, verified_status, created_at
                // Note: contact and address are in users table. identity_proof is null for now.
                $sql_comp = "INSERT INTO companies (user_id, company_name, owner_name, gst_number, verified_status) VALUES (?, ?, ?, ?, 'verified')";
                execute_query($sql_comp, [$user_id, $name, $name, $gst]);
            }

            $conn->commit();
            $success = "User " . htmlspecialchars($name) . " created successfully!";
        } catch (Exception $e) {
            $conn->rollBack();
            $error = "Failed to create user: " . $e->getMessage();
        }
    }
}
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Add New User</h1>
        <a href="manage_users.php" class="btn btn-secondary">&larr; Back to Users</a>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <form action="" method="POST">

            <div class="form-group">
                <label class="form-label">User Role</label>
                <select name="role" class="form-control" required onchange="toggleCompanyFields(this.value)">
                    <option value="client">Client (Buyer)</option>
                    <option value="company">Company (Seller)</option>
                </select>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Full Name / Company Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                <div>
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@example.com">
                </div>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="*****">
                </div>
                <div>
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact" class="form-control" required placeholder="1234567890">
                </div>
            </div>

            <div id="company-fields" style="display: none;">
                <div class="form-group">
                    <label class="form-label">GST Number (Optional)</label>
                    <input type="text" name="gst" class="form-control" placeholder="GST123456789">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="3" required placeholder="123 Main St, City"></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-full">Create User</button>
        </form>
    </div>
</div>

<script>
    function toggleCompanyFields(role) {
        const fields = document.getElementById('company-fields');
        if (role === 'company') {
            fields.style.display = 'block';
        } else {
            fields.style.display = 'none';
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>