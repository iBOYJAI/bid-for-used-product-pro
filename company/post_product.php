<?php
$page_title = 'Post New Product';
require_once __DIR__ . '/../includes/header.php';
require_login('company');
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Post New Product</h1>
        <a href="dashboard.php" class="btn btn-secondary">&larr; Dashboard</a>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <?php
            switch ($_GET['error']) {
                case 'validation':
                    echo "Please check your inputs.";
                    break;
                case 'file_upload_failed':
                    echo "Failed to upload images.";
                    break;
                case 'database':
                    echo "Database error occurred.";
                    break;
                default:
                    echo "An error occurred.";
            }
            ?>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="post_product_process.php" method="POST" enctype="multipart/form-data">

            <div class="form-group">
                <label class="form-label">Product Name</label>
                <input type="text" name="product_name" class="form-control" required placeholder="e.g. 2020 Honda City">
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Category</label>
                    <select name="category" class="form-control" required>
                        <option value="">Select Category</option>
                        <option value="2-wheeler">2-Wheeler</option>
                        <option value="4-wheeler">4-Wheeler</option>
                        <option value="machinery">Machinery</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Model Name/Number</label>
                    <input type="text" name="model" class="form-control" required placeholder="Model">
                </div>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 1.5rem;">
                <div>
                    <label class="form-label">Manufacturing Year</label>
                    <input type="number" name="year" class="form-control" required min="1900" max="<?php echo date('Y'); ?>" placeholder="Year">
                </div>
                <div>
                    <label class="form-label">Running Duration / Odometer</label>
                    <input type="text" name="running_duration" class="form-control" required placeholder="e.g. 50,000 km or 500 hours">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Chassis Number</label>
                <input type="text" name="chassis_no" class="form-control" required placeholder="Enter Chassis No.">
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label class="form-label">Ownership & History Details</label>
                <textarea name="owner_details" class="form-control" rows="2" placeholder="e.g. First Owner, Insurance Valid upto..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Base Price (₹)</label>
                <input type="number" name="base_price" class="form-control" required min="0" step="0.01" placeholder="Starting Bid Amount">
            </div>

            <div class="form-group">
                <label class="form-label">Description & Condition</label>
                <textarea name="description" class="form-control" rows="4" required placeholder="Describe the product condition, features, and any defects..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Product Images (Max 10)</label>
                <input type="file" name="product_image[]" class="form-control" accept="image/*" multiple required>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 0.25rem;">Supported formats: JPG, PNG. Max size: 5MB per image. Select multiple files.</p>
            </div>

            <div class="alert alert-info" style="margin-top: 2rem;">
                <strong>Auction Duration</strong>
            </div>

            <div class="grid-3" style="grid-template-columns: 1fr 1fr; margin-bottom: 2rem;">
                <div>
                    <label class="form-label">Bidding Starts</label>
                    <input type="datetime-local" name="bid_start" class="form-control" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                </div>
                <div>
                    <label class="form-label">Bidding Ends</label>
                    <input type="datetime-local" name="bid_end" class="form-control" required value="<?php echo date('Y-m-d\TH:i', strtotime('+7 days')); ?>">
                </div>
            </div>

            <div class="flex-between">
                <button type="reset" class="btn btn-secondary">Reset</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1.1rem;">Post Product</button>
            </div>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>