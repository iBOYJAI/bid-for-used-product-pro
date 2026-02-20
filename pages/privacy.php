<?php
$page_title = 'Privacy Policy';
require_once __DIR__ . '/../includes/header.php';
?>

<div style="background: #f8fafc; padding: 3rem 0;">
    <div class="container" style="max-width: 900px;">
        <div style="display: flex; gap: 2rem; align-items: center; margin-bottom: 3rem;">
            <div style="flex: 1;">
                <h1 style="font-size: 3rem; margin-bottom: 1rem; color: #1e293b; line-height: 1.2;">Your Privacy <br><span style="color: var(--primary);">Matters</span></h1>
                <p style="font-size: 1.1rem; color: #64748b;">We are committed to protecting your personal data and ensuring a secure bidding experience.</p>
            </div>
            <div style="flex: 1; text-align: right; display: none; @media(min-width: 768px){display: block;}">
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Payment-and-Shopping-Visuals/png/pv-07.png" alt="Privacy Shield" style="max-width: 250px;">
            </div>
        </div>

        <div class="card" style="padding: 3rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            <div style="line-height: 1.8; color: var(--text-main);">
                <p>Last updated: <?php echo date('F d, Y'); ?></p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">1. Introduction</h3>
                <p>Welcome to <strong><?php echo APP_NAME; ?></strong>. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you as to how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">2. Data We Collect</h3>
                <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together follows:</p>
                <ul style="list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li><strong>Identity Data</strong> includes first name, maiden name, last name, username or similar identifier.</li>
                    <li><strong>Contact Data</strong> includes billing address, delivery address, email address and telephone numbers.</li>
                    <li><strong>Transaction Data</strong> includes details about payments to and from you and other details of products you have purchased from us.</li>
                </ul>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">3. How We Use Your Data</h3>
                <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
                <ul style="list-style-type: disc; margin-left: 1.5rem; margin-bottom: 1rem;">
                    <li>To register you as a new customer.</li>
                    <li>To process and deliver your orders/bids.</li>
                    <li>To manage our relationship with you.</li>
                </ul>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">4. Data Security</h3>
                <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed.</p>

                <h3 style="margin-top: 2rem; margin-bottom: 1rem; color: var(--primary);">5. Contact Us</h3>
                <p>If you have any questions about this privacy policy or our privacy practices, please contact us at privacy@bidusedproduct.com.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>