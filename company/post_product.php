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

    <div class="card" style="max-width: 1100px; margin: 0 auto; padding: 2.5rem; border-radius: 1.5rem;">
        <form action="post_product_process.php" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 4rem;">
                
                <!-- Left Column: Primary Details -->
                <div>
                    <h3 style="margin-bottom: 2rem; color: var(--secondary); font-size: 1.3rem; border-left: 4px solid var(--primary); padding-left: 1rem;">1. Product Details</h3>
                    
                    <div class="form-group">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="product_name" class="form-control" required placeholder="e.g. 2022 Mahindra Thar">
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label">Category</label>
                            <select name="category" id="categorySelect" class="form-control" style="background-color: #f8fafc;" required>
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

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label">Manufacturing Year</label>
                            <input type="number" name="year" class="form-control" required min="1900" max="<?php echo date('Y'); ?>" placeholder="Year">
                        </div>
                        <div>
                            <label class="form-label" id="runningLabel">Running Duration / Odometer</label>
                            <input type="text" name="running_duration" id="runningInput" class="form-control" required placeholder="e.g. 50,000 km">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
                        <div>
                            <label class="form-label" id="chassisLabel">Chassis Number</label>
                            <input type="text" name="chassis_no" id="chassisInput" class="form-control" required placeholder="Enter Chassis No.">
                        </div>
                        <div>
                            <label class="form-label">Base Price (₹)</label>
                            <input type="number" name="base_price" class="form-control" required min="0" step="0.01" placeholder="Starting Bid">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ownership & History</label>
                        <textarea name="owner_details" class="form-control" rows="2" placeholder="e.g. First Owner, Multi-point inspection cleared..."></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description & Condition</label>
                        <textarea name="description" class="form-control" rows="4" required placeholder="Describe any unique features, modifications, or minor defects..."></textarea>
                    </div>
                </div>

                <!-- Right Column: Visuals & Timeline -->
                <div>
                    <h3 style="margin-bottom: 2rem; color: var(--secondary); font-size: 1.3rem; border-left: 4px solid var(--primary); padding-left: 1rem;">2. Images & Auction</h3>

                    <div class="form-group">
                        <label class="form-label">Product Images (First will be main)</label>
                        <div style="border: 2px dashed #cbd5e1; padding: 3rem 2rem; border-radius: 1.25rem; text-align: center; background: #fdfdfd; cursor: pointer; transition: all 0.3s; position: relative;" 
                             onmouseover="this.style.borderColor='var(--primary)'; this.style.background='#f8fafc'" 
                             onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='#fdfdfd'">
                            <input type="file" name="product_image[]" id="imageInput" class="form-control" accept="image/*" multiple required style="position: absolute; inset: 0; opacity: 0; cursor: pointer;" onchange="updateFilePreview(this)">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary); margin-bottom: 1rem; opacity: 0.8;">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <polyline points="17 8 12 3 7 8" />
                                    <line x1="12" y1="3" x2="12" y2="15" />
                                </svg>
                                <p style="font-weight: 500; font-size: 1.1rem; color: #1e293b;">Drop images here or click to browse</p>
                                <p style="font-size: 0.85rem; color: #64748b; margin-top: 0.5rem;">Select up to 10 photos (Max 5MB each)</p>
                            </div>
                        </div>
                        <div id="file-preview-list" style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 0.75rem;"></div>
                    </div>

                    <div style="background: #f1f5f9; padding: 1.5rem; border-radius: 1rem; margin-top: 2rem; border-left: 5px solid var(--secondary);">
                        <h4 style="margin-bottom: 1.25rem; font-size: 1rem; color: #334155;">Auction Timing</h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 1rem;">
                            <div>
                                <label style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #64748b; display: block; margin-bottom: 0.25rem;">Starts On</label>
                                <input type="datetime-local" name="bid_start" class="form-control" required value="<?php echo date('Y-m-d\TH:i'); ?>">
                            </div>
                            <div>
                                <label style="font-size: 0.75rem; font-weight: 600; text-transform: uppercase; color: #64748b; display: block; margin-bottom: 0.25rem;">Ends On</label>
                                <input type="datetime-local" name="bid_end" class="form-control" required value="<?php echo date('Y-m-d\TH:i', strtotime('+7 days')); ?>">
                            </div>
                        </div>
                    </div>

                    <div style="margin-top: 3.5rem;">
                        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 1.1rem; font-size: 1.2rem; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(254, 176, 93, 0.4); border: none; color: white;">
                             Create Auction Now &rarr;
                        </button>
                        <p style="text-align: center; margin-top: 1rem; font-size: 0.85rem; color: #94a3b8;">By posting, you agree to our platform bidding terms.</p>
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

categorySelect.addEventListener('change', function() {
    if (this.value === 'machinery') {
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
});

function updateFilePreview(input) {
    const list = document.getElementById('file-preview-list');
    list.innerHTML = '';
    if (input.files) {
        Array.from(input.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.cssText = 'aspect-ratio: 1; border-radius: 8px; overflow: hidden; position: relative; border: 1px solid #e2e8f0;';
                div.innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
                if(index === 0) {
                    div.innerHTML += '<span style="position: absolute; bottom: 0; left: 0; right: 0; background: var(--primary); color: white; font-size: 0.65rem; text-align: center; padding: 2px;">MAIN</span>';
                }
                list.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    }
}
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>