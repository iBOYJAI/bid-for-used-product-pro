<?php
$page_title = 'Terms of Service';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background: #f8fafc; padding: 3rem 0;">
    <div class="container" style="max-width: 900px;">
        <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 3rem;">
            <div style="flex: 1;">
                <h1 style="font-size: 3rem; margin-bottom: 1rem; color: #1e293b; line-height: 1.2;">Terms of <br><span style="color: var(--primary);">Service</span></h1>
                <p style="font-size: 1.1rem; color: #64748b;">Please read these terms carefully before using our platform to ensure a smooth trading experience.</p>
            </div>
            <div style="flex: 1; text-align: right; display: none; @media(min-width: 768px){display: block;}">
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Payment-and-Shopping-Visuals/png/pv-04.png" alt="Terms" style="max-width: 250px;">
            </div>
        </div>

        <div class="card" style="padding: 3rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="line-height: 1.8; color: var(--text-main);">
                <p>Last updated: <?php echo date('F d, Y'); ?></p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">1. Acceptance of Terms</h3>
                <p>By accessing and using <strong><?php echo APP_NAME; ?></strong>, you accept and agree to be bound by the terms and provision of this agreement.</p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">2. User Responsibilities</h3>
                <p>Users are responsible for maintaining the confidentiality of their account and password. You agree to accept responsibility for all activities that occur under your account.</p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">3. Bidding Rules</h3>
                <ul style="list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>All bids are binding contracts.</li>
                    <li>You must not bid on your own items.</li>
                    <li>Winning bidders must complete the purchase transaction within 48 hours.</li>
                </ul>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">4. Seller Verification</h3>
                <p>All sellers on our platform are required to undergo a verification process, including GST and Identity proof submission. However, we do not guarantee the quality or safety of items listed.</p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">5. Limitation of Liability</h3>
                <p>In no event shall <?php echo APP_NAME; ?> be liable for any direct, indirect, incidental, special, exemplary, or consequential damages arising out of or in any way connected with the use of this website.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>