<?php
$page_title = 'Welcome';
require_once __DIR__ . '/includes/header.php';

// Fetch featured products (latest 3 active)
$sql = "SELECT p.*, c.company_name 
        FROM products p 
        JOIN companies c ON p.company_id = c.company_id 
        WHERE p.status = 'open' 
        ORDER BY p.created_at DESC 
        LIMIT 3";
$featured_products = fetch_all($sql);
?>

<div style="position: relative; overflow: hidden; background: radial-gradient(circle at 50% 50%, #FFF7ED 0%, #F5F2F2 100%); padding: 6rem 0 4rem;">
    <!-- Abstract Background Elements -->
    <div style="position: absolute; top: -100px; right: -100px; width: 400px; height: 400px; background: rgba(254, 176, 93, 0.1); border-radius: 50%; filter: blur(50px);"></div>
    <div style="position: absolute; bottom: 50px; left: -50px; width: 300px; height: 300px; background: rgba(90, 122, 205, 0.1); border-radius: 50%; filter: blur(50px);"></div>

    <div class="container" style="position: relative; z-index: 1;">
        <div style="display: grid; grid-template-columns: 1fr 1fr; align-items: center; gap: 4rem;">
            <div>
                <span class="badge badge-primary" style="margin-bottom: 1.5rem; display: inline-block;">#1 Auction Platform</span>
                <h1 style="font-size: 3.5rem; line-height: 1.1; margin-bottom: 1.5rem; color: #2B2A2A;">
                    Bid Smart, <br>
                    <span style="color: #FEB05D; position: relative;">
                        Win Big
                        <svg width="120" height="10" viewBox="0 0 120 10" style="position: absolute; bottom: 0; left: 0; width: 100%; z-index: -1;">
                            <path d="M0,5 Q60,10 120,5" stroke="#FEB05D" stroke-width="8" stroke-opacity="0.3" fill="none" />
                        </svg>
                    </span>
                </h1>
                <p style="font-size: 1.25rem; color: var(--text-muted); margin-bottom: 2.5rem; max-width: 500px;">
                    Access exclusive deals on verified used vehicles and machinery. Transparent bidding, verified sellers.
                </p>
                <div style="display: flex; gap: 1rem;">
                    <a href="products.php" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">Start Bidding</a>
                    <?php if (!is_logged_in()): ?>
                        <a href="pages/register_client.php" class="btn btn-secondary" style="padding: 1rem 2rem; font-size: 1.1rem;">Register Now</a>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Placeholder for image/illustration on the right -->
            <div style="text-align: center;">
                <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Office-Club/Regular/png/oc-handing-key.png" alt="Handing Key" style="max-width: 100%; height: auto; max-height: 400px; display: block; margin: 0 auto; transform: scale(1.1);">
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<div style="background: white; padding: 5rem 0;">
    <div class="container">
        <div class="grid-3">
            <div class="card" style="border: none; box-shadow: none; background: transparent; text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: #eff6ff; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Accent-Color/png/ec-analyzing-market-price.png" alt="Icon" style="width: 48px;">
                </div>
                <h3 style="margin-bottom: 0.75rem;">Fair Market Pricing</h3>
                <p style="color: var(--text-muted);">Transparent bidding system ensures you get the true market value for every asset.</p>
            </div>

            <div class="card" style="border: none; box-shadow: none; background: transparent; text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: #fdf2f8; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Accent-Color/png/ec-secured-payment.png" alt="Icon" style="width: 48px;">
                </div>
                <h3 style="margin-bottom: 0.75rem;">Verified Sellers</h3>
                <p style="color: var(--text-muted);">Every seller is verified with GST and Identity proofs to ensure a safe trading environment.</p>
            </div>

            <div class="card" style="border: none; box-shadow: none; background: transparent; text-align: center;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1.5rem; background: #f5f3ff; border-radius: 20px; display: flex; align-items: center; justify-content: center;">
                    <img src="<?php echo APP_URL; ?>/assets/images/Notion-Resources/Ecommerce-Club/Accent-Color/png/ec-easy-shopping.png" alt="Icon" style="width: 48px;">
                </div>
                <h3 style="margin-bottom: 0.75rem;">Easy & Fast</h3>
                <p style="color: var(--text-muted);">Seamless detailed listings, inspection reports, and instant bid updates.</p>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products -->
<div class="container" style="padding: 5rem 1.5rem;">
    <div class="flex-between" style="margin-bottom: 3rem; align-items: flex-end;">
        <div>
            <span class="badge badge-primary" style="background: #e0e7ff; color: #4338ca; margin-bottom: 0.5rem;">Live Auctions</span>
            <h2 style="font-size: 2.25rem;">Featured Assets</h2>
        </div>
        <a href="products.php" class="btn btn-secondary btn-sm" style="border-radius: 50px; padding-left: 1.5rem; padding-right: 1.5rem;">View All Products &rarr;</a>
    </div>

    <?php if (empty($featured_products)): ?>
        <div class="card text-center" style="padding: 4rem; background: #f8fafc; border: 2px dashed #cbd5e1;">
            <div style="font-size: 3rem; margin-bottom: 1rem;">📭</div>
            <h3 style="color: var(--text-muted);">No live auctions right now</h3>
            <p style="color: var(--text-light);">Check back later for new inventory.</p>
        </div>
    <?php else: ?>
        <div class="grid-3">
            <?php foreach ($featured_products as $product): ?>
                <?php
                $is_active = is_bid_active($product['bid_start'], $product['bid_end']);
                $image_url = $product['product_image']
                    ? APP_URL . '/uploads/products/' . $product['product_image']
                    : null;
                ?>
                <div class="card" style="padding: 0; overflow: hidden; display: flex; flex-direction: column; height: 100%;">
                    <!-- Image Wrapper -->
                    <div style="position: relative; height: 220px; overflow: hidden; background: #f1f5f9;">
                        <?php if ($image_url): ?>
                            <img src="<?php echo $image_url; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: var(--text-muted); font-size: 0.85rem;">No image</div>
                        <?php endif; ?>

                        <!-- Status Badge -->
                        <div style="position: absolute; top: 1rem; right: 1rem;">
                            <?php if ($is_active): ?>
                                <span class="badge" style="background: rgba(22, 163, 74, 0.9); color: white; backdrop-filter: blur(4px);">Live</span>
                            <?php else: ?>
                                <span class="badge" style="background: rgba(220, 38, 38, 0.9); color: white; backdrop-filter: blur(4px);">Closed</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Content -->
                    <div style="padding: 1.5rem; display: flex; flex-direction: column; flex: 1;">
                        <span class="badge badge-secondary" style="align-self: start; margin-bottom: 0.75rem; font-size: 0.7rem; letter-spacing: 0.5px;"><?php echo htmlspecialchars($product['category']); ?></span>

                        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem; flex: 1;">
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>" style="text-decoration: none; color: inherit; transition: color 0.2s;">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </a>
                        </h3>

                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1.25rem; color: var(--text-muted); font-size: 0.875rem;">
                            <span>By <?php echo htmlspecialchars($product['company_name']); ?></span>
                        </div>

                        <div style="margin-top: auto; border-top: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding-top: 1rem;">
                            <div>
                                <p style="font-size: 0.75rem; color: var(--text-light); text-transform: uppercase;">Current Value</p>
                                <p style="font-weight: 700; font-size: 1.25rem; color: var(--primary);">
                                    <?php echo format_currency($product['base_price']); ?>
                                </p>
                            </div>
                            <a href="product_details.php?id=<?php echo $product['product_id']; ?>" style="width: 40px; height: 40px; border-radius: 50%; background: var(--bg-body); display: flex; align-items: center; justify-content: center; color: var(--text-main); transition: all 0.2s;" onmouseover="this.style.background='var(--primary)'; this.style.color='white'" onmouseout="this.style.background='var(--bg-body)'; this.style.color='var(--text-main)'">
                                &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- CTA Section -->
<div style="background: #5A7ACD; padding: 5rem 0; color: white; text-align: center;">
    <div class="container">
        <h2 style="font-size: 2.5rem; margin-bottom: 1rem; color: white;">Ready to start bidding?</h2>
        <p style="font-size: 1.25rem; color: rgba(255,255,255,0.9); margin-bottom: 2.5rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Join thousands of satisfied clients finding the best deals on premium used assets today.
        </p>
        <?php if (!is_logged_in()): ?>
            <a href="pages/register_client.php" class="btn" style="background: white; color: var(--primary); padding: 1rem 2.5rem; font-weight: 700; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                Create Free Account
            </a>
        <?php else: ?>
            <a href="products.php" class="btn" style="background: white; color: var(--primary); padding: 1rem 2.5rem; font-weight: 700; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                Place a Bid Now
            </a>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>