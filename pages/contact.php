<?php
$page_title = 'Contact Us';
require_once __DIR__ . '/../includes/header.php';

// Auto-fill user data
$first_name = '';
$last_name = '';
if (is_logged_in()) {
    $user = fetch_one("SELECT name FROM users WHERE user_id = ?", [get_user_id()]);
    if ($user) {
        $parts = explode(' ', $user['name'], 2);
        $first_name = $parts[0];
        $last_name = isset($parts[1]) ? $parts[1] : '';
    }
}

// Handle form submission
$message_sent = false;
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['first_name'] . ' ' . $_POST['last_name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);
    $user_id = is_logged_in() ? get_user_id() : null;

    if ($name && $email && $message) {
        $sql = "INSERT INTO contact_messages (user_id, name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        try {
            execute_query($sql, [$user_id, $name, $email, $subject, $message]);
            $message_sent = true;
        } catch (Exception $e) {
            $error_message = "Something went wrong. Please try again.";
        }
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<div style="background: #FFF7ED; padding: 5rem 0 8rem; color: #2B2A2A; text-align: center; position: relative; overflow: hidden;">
    <div class="container" style="position: relative; z-index: 2;">
        <h1 style="font-size: 3.5rem; margin-bottom: 1rem; color: #2B2A2A;">Get in Touch</h1>
        <p style="color: var(--text-muted); font-size: 1.25rem; max-width: 600px; margin: 0 auto;">Have questions about bidding, selling, or our platform? We're here to help.</p>
    </div>
    <!-- Decorative Image -->
    <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-taking-note.png" alt="" style="position: absolute; right: 10%; bottom: -30px; height: 250px; opacity: 0.8; transform: rotate(-10deg);">
</div>

<div class="container" style="margin-top: -5rem; padding-bottom: 5rem; position: relative; z-index: 3;">
    <div class="card" style="padding: 0; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="display: grid; grid-template-columns: 1fr; @media(min-width: 900px){grid-template-columns: 1fr 1.5fr;}">

            <!-- Contact Info Sidebar -->
            <div style="background: var(--primary); padding: 3rem; color: white;">
                <h3 style="color: white; margin-bottom: 2rem;">Contact Information</h3>

                <div style="margin-bottom: 2rem;">
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Address</p>
                    <p style="font-size: 1.1rem; line-height: 1.6;">
                        Gobi Arts and Science College,<br>
                        Karattadipalayam, Gobichettipalayam,<br>
                        Tamil Nadu 638453<br>
                        India
                    </p>
                </div>

                <div style="margin-bottom: 2rem;">
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Email</p>
                    <p style="font-size: 1.1rem;">support@bidusedproduct.com</p>
                    <p style="font-size: 1.1rem;">principal@gascgobi.ac.in</p>
                </div>

                <div style="margin-bottom: 2rem;">
                    <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.5rem;">Phone</p>
                    <p style="font-size: 1.1rem;">+91 98765 43210</p>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 3rem;">
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">f</a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">t</a>
                    <a href="#" style="width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; color: white;">in</a>
                </div>
            </div>

            <!-- Contact Form -->
            <div style="padding: 3rem; background: white;">
                <?php if ($message_sent): ?>
                    <div style="height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center;">
                        <div style="width: 80px; height: 80px; background: #dcfce7; border-radius: 50%; color: #166534; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin-bottom: 1.5rem;">✓</div>
                        <h3 style="margin-bottom: 0.5rem;">Message Sent!</h3>
                        <p style="color: var(--text-muted);">Thank you for contacting us. We will get back to you shortly.</p>
                        <a href="../index.php" class="btn btn-primary mt-4">Back to Home</a>
                    </div>
                <?php else: ?>
                    <h3 style="margin-bottom: 2rem;">Send us a message</h3>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger" style="margin-bottom: 1.5rem;"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">First Name</label>
                                <input type="text" name="first_name" class="form-control" placeholder="Lakshmana" value="<?php echo htmlspecialchars($first_name); ?>" required>
                            </div>
                            <div>
                                <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Last Name</label>
                                <input type="text" name="last_name" class="form-control" placeholder="Prakash" value="<?php echo htmlspecialchars($last_name); ?>" required>
                            </div>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Email Address</label>
                            <input type="email" name="email" class="form-control" placeholder="lakshmanaprakash@example.com" value="<?php echo is_logged_in() ? htmlspecialchars($_SESSION['email'] ?? '') : ''; ?>" required>
                        </div>

                        <div style="margin-bottom: 1.5rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Subject</label>
                            <select name="subject" class="form-control">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Technical Support">Technical Support</option>
                                <option value="Billing & Payments">Billing & Payments</option>
                                <option value="Seller Verification">Seller Verification</option>
                            </select>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <label style="display: block; margin-bottom: 0.5rem; color: var(--text-main); font-weight: 500;">Message</label>
                            <textarea name="message" class="form-control" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-full" style="padding: 1rem;">Send Message</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>