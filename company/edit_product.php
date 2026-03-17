<?php
$page_title = 'Edit Product';
require_once __DIR__ . '/../includes/header.php';
require_login('company');

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$company_id = get_company_id();

// Fetch product
$product = fetch_one("SELECT * FROM products WHERE product_id = ? AND company_id = ?", [$product_id, $company_id]);

if (!$product) {
    echo '<div class="container"><div class="alert alert-error">Product not found.</div></div>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = sanitize_input($_POST['product_name']);
    $category = sanitize_input($_POST['category']);
    $model = sanitize_input($_POST['model']);
    $year = sanitize_input($_POST['year']);
    $base_price = sanitize_input($_POST['base_price']);
    $running_duration = sanitize_input($_POST['running_duration']);
    $chassis_no = sanitize_input($_POST['chassis_no']);
    $owner_details = sanitize_input($_POST['owner_details']);
    $description = sanitize_input($_POST['description']);
    $status = sanitize_input($_POST['status']);

    // Convert 'T' format back to mysql format
    $bid_end = date('Y-m-d H:i:s', strtotime($_POST['bid_end']));

    // Handle Image Upload
    $image_query_part = "";
    $params = [$product_name, $category, $model, $year, $base_price, $running_duration, $chassis_no, $owner_details, $description, $status, $bid_end];

    if (isset($_FILES['product_image']['tmp_name']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK && is_uploaded_file($_FILES['product_image']['tmp_name'])) {
        $upload = upload_file($_FILES['product_image'], UPLOAD_DIR . 'products/');
        if ($upload[0]) {
            $image_query_part = ", product_image = ?";
            $params[] = $upload[1];
        } else {
            $error = $upload[1];
        }
    }

    if (!$error) {
        $sql = "UPDATE products SET 
                product_name = ?, category = ?, model = ?, year = ?, base_price = ?, 
                running_duration = ?, chassis_no = ?, owner_details = ?, description = ?, status = ?, bid_end = ? 
                $image_query_part
                WHERE product_id = ? AND company_id = ?";

        $params[] = $product_id;
        $params[] = $company_id;

        $stmt = execute_query($sql, $params);

        if ($stmt) {
            $success = "Product updated successfully!";

            // Handle additional gallery images
            if (isset($_FILES['gallery_images']) && !empty($_FILES['gallery_images']['name'][0])) {
                $files = $_FILES['gallery_images'];
                $upload_dir = UPLOAD_DIR . 'products/';
                for ($i = 0; $i < count($files['name']); $i++) {
                    if ($files['error'][$i] === UPLOAD_ERR_OK) {
                        $file_array = [
                            'name' => $files['name'][$i],
                            'type' => $files['type'][$i],
                            'tmp_name' => $files['tmp_name'][$i],
                            'error' => $files['error'][$i],
                            'size' => $files['size'][$i]
                        ];
                        list($upload_ok, $filename_or_error) = upload_file($file_array, $upload_dir);
                        if ($upload_ok) {
                            execute_query("INSERT INTO product_gallery (product_id, image_path) VALUES (?, ?)", [$product_id, $filename_or_error]);
                        }
                    }
                }
            }

            // Refresh data
            $product = fetch_one("SELECT * FROM products WHERE product_id = ?", [$product_id]);
        } else {
            $error = "Failed to update product.";
        }
    }
}

// Load existing gallery
$existing_gallery = fetch_all("SELECT image_path FROM product_gallery WHERE product_id = ? ORDER BY gallery_id ASC", [$product_id]);
$bid_end_val = date('Y-m-d\TH:i', strtotime($product['bid_end']));
?>

<div class="container" style="padding: 2rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.75rem;">Edit Product</h1>
        <a href="my_products.php" class="btn btn-secondary">&larr; Back to My Products</a>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success" style="margin-bottom: 2rem;"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-error" style="margin-bottom: 2rem;"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="card" style="max-width: 1100px; margin: 0 auto; padding: 2.5rem; border-radius: 1.5rem;">
        <form action="" method="POST" enctype="multipart/form-data" id="editForm">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 4rem;">
                <!-- Left Column: Primary Details -->
                <div>
                    <h3 style="margin-bottom: 2rem; color: var(--secondary); font-size: 1.3rem; border-left: 4px solid var(--primary); padding-left: 1rem;">1. Product Details</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" required value="<?php echo htmlspecialchars($product['product_name']); ?>">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label">Category</label>
                            <select name="category" id="categorySelect" class="form-control" style="background-color: #f8fafc;" required>
                                <option value="2-wheeler" <?php echo $product['category'] === '2-wheeler' ? 'selected' : ''; ?>>2-Wheeler</option>
                                <option value="4-wheeler" <?php echo $product['category'] === '4-wheeler' ? 'selected' : ''; ?>>4-Wheeler</option>
                                <option value="machinery" <?php echo $product['category'] === 'machinery' ? 'selected' : ''; ?>>Machinery</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Model Name/Number</label>
                            <input type="text" name="model" class="form-control" required value="<?php echo htmlspecialchars($product['model']); ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label">Manufacturing Year</label>
                            <input type="number" name="year" class="form-control" required value="<?php echo $product['year']; ?>">
                        </div>
                        <div>
                            <label class="form-label" id="runningLabel">Running Duration / Odometer</label>
                            <input type="text" name="running_duration" id="runningInput" class="form-control" required value="<?php echo htmlspecialchars($product['running_duration']); ?>">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label" id="chassisLabel">Chassis Number</label>
                            <input type="text" name="chassis_no" id="chassisInput" class="form-control" required value="<?php echo htmlspecialchars($product['chassis_no']); ?>">
                        </div>
                        <div>
                            <label class="form-label">Base Price (₹)</label>
                            <input type="number" name="base_price" class="form-control" required step="0.01" value="<?php echo $product['base_price']; ?>">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ownership & History</label>
                        <textarea name="owner_details" class="form-control" rows="2"><?php echo htmlspecialchars($product['owner_details']); ?></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description & Condition</label>
                        <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($product['description']); ?></textarea>
                    </div>
                </div>

                <!-- Right Column: Visuals & Status -->
                <div>
                    <h3 style="margin-bottom: 2rem; color: var(--secondary); font-size: 1.3rem; border-left: 4px solid var(--primary); padding-left: 1rem;">2. Images & Status</h3>

                    <!-- Main Image Preview -->
                    <div class="form-group">
                        <label class="form-label">Main Product Image</label>
                        <div style="position: relative; border-radius: 1.25rem; overflow: hidden; height: 280px; background: #f1f5f9; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.05);">
                            <?php if ($product['product_image']): ?>
                                <img src="<?php echo APP_URL . '/uploads/products/' . $product['product_image']; ?>"
                                    alt="Product" style="width: 100%; height: 100%; object-fit: cover;" id="imagePreview">
                            <?php else: ?>
                                <span style="color: var(--text-muted); font-size: 0.9rem;">No image uploaded</span>
                                <img src="" alt="" id="imagePreview" style="display: none;">
                            <?php endif; ?>
                            <label style="position: absolute; bottom: 15px; right: 15px; background: rgba(0,0,0,0.7); color: white; padding: 8px 18px; border-radius: 30px; font-size: 0.85rem; cursor: pointer; backdrop-filter: blur(8px); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 5px; vertical-align: middle;"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>
                                Change Photo
                                <input type="file" name="product_image" style="display: none;" onchange="previewImage(this)" accept="image/*">
                            </label>
                        </div>
                    </div>

                    <!-- Gallery section -->
                    <div class="form-group">
                        <label class="form-label">Gallery Images</label>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(75px, 1fr)); gap: 0.75rem; margin-bottom: 1rem;">
                            <?php foreach ($existing_gallery as $g): ?>
                                <div style="aspect-ratio: 1; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
                                    <img src="<?php echo APP_URL; ?>/uploads/products/<?php echo htmlspecialchars($g['image_path']); ?>" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                                </div>
                            <?php endforeach; ?>
                            <label style="aspect-ratio: 1; border: 2px dashed #cbd5e1; border-radius: 8px; display: flex; flex-direction: column; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; background: #fdfdfd;" onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#f8fafc'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='#fdfdfd'">
                                <span style="font-size: 1.5rem; color: #94a3b8; font-weight: 300;">+</span>
                                <span style="font-size: 0.65rem; color: #94a3b8; font-weight: 600;">ADD</span>
                                <input type="file" name="gallery_images[]" style="display: none;" accept="image/*" multiple onchange="updateGalleryTip(this)">
                            </label>
                        </div>
                        <p id="gallery-tip" style="font-size: 0.8rem; color: var(--primary); font-weight: 500;"></p>
                    </div>

                    <!-- Auction Status Area -->
                    <div style="background: #fdf2f2; padding: 1.5rem; border-radius: 1rem; margin: 2rem 0; border-left: 5px solid #ef4444;">
                        <h4 style="margin-bottom: 1.25rem; font-size: 1rem; color: #991b1b;">Auction & Sale Status</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem;">
                            <div>
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">Status</label>
                                <select name="status" class="form-control" style="border-color: #fee2e2;">
                                    <option value="open" <?php echo $product['status'] === 'open' ? 'selected' : ''; ?>>Open for Bids</option>
                                    <option value="closed" <?php echo $product['status'] === 'closed' ? 'selected' : ''; ?>>Closed / Ended</option>
                                    <option value="sold" <?php echo $product['status'] === 'sold' ? 'selected' : ''; ?>>Mark as Sold</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label" style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase;">End Date</label>
                                <input type="datetime-local" name="bid_end" class="form-control" required value="<?php echo $bid_end_val; ?>" style="border-color: #fee2e2;">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 3.5rem;">
                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 1.1rem; font-size: 1.2rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(34, 197, 94, 0.4); border: none; color: white;">
                             Update Product Details
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const categorySelect = document.getElementById('categorySelect');
    const runningLabel = document.getElementById('runningLabel');
    const runningInput = document.getElementById('runningInput');
    const chassisLabel = document.getElementById('chassisLabel');
    const chassisInput = document.getElementById('chassisInput');

    function updateLabels() {
        if (categorySelect.value === 'machinery') {
            runningLabel.textContent = 'Running Duration / Hours';
            runningInput.placeholder = 'e.g. 500 hours';
            chassisLabel.textContent = 'Serial Number / Machine ID';
            chassisInput.placeholder = 'Enter Serial No.';
        } else {
            runningLabel.textContent = 'Running Duration / Odometer';
            runningInput.placeholder = 'e.g. 50,000 km';
            chassisLabel.textContent = 'Chassis Number';
            chassisInput.placeholder = 'Enter Chassis No.';
        }
    }

    function updateGalleryTip(input) {
        if(input.files.length > 0) {
            document.getElementById('gallery-tip').textContent = '✓ ' + input.files.length + ' new gallery images ready to upload.';
        }
    }

    categorySelect.addEventListener('change', updateLabels);
    window.addEventListener('load', updateLabels);

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var preview = document.getElementById('imagePreview');
            var container = preview.closest('div');
            var noImg = container.querySelector('span');
            if (noImg) noImg.style.display = 'none';
            preview.style.display = 'block';
            
            var reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>