<?php $pageTitle = 'Home'; include 'header.php';
$featured = $woodDb->query("SELECT p.*, u.name AS seller_name FROM tblProduct p JOIN tblUser u ON p.seller_id=u.user_id WHERE p.admin_approved=1 AND p.status='available' ORDER BY p.created_at DESC LIMIT 4");
?>
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">Second-hand branded clothing</span>
        <h1>Buy clean fits. Sell quality pieces. Keep fashion moving.</h1>
        <p>Pastimes connects buyers and verified sellers through product listings, cart checkout, delivery details, and product-based messaging.</p>
        <div class="hero-actions">
            <a class="btn primary" href="shop.php">Browse Clothing</a>
            <a class="btn ghost" href="register.php">Start Selling</a>
        </div>
    </div>
    <div class="hero-card">
        <img src="assets/Leather Jacket.webp" alt="Featured leather jacket">
        <div class="floating-card"><strong>Verified sellers</strong><span>Admin-approved listings only</span></div>
    </div>
</section>
<section class="section-heading">
    <span class="eyebrow">Featured drops</span>
    <h2>Available items</h2>
</section>
<div class="product-grid">
<?php while ($p = $featured->fetch_assoc()): ?>
    <article class="product-card">
        <a href="product.php?id=<?php echo $p['product_id']; ?>"><img src="<?php echo e(productImage($p['image'])); ?>" alt="<?php echo e($p['name']); ?>"></a>
        <div class="product-info">
            <span class="badge"><?php echo e($p['category']); ?></span>
            <h3><?php echo e($p['name']); ?></h3>
            <p><?php echo e($p['brand']); ?> • <?php echo e($p['item_condition']); ?> • Size <?php echo e($p['size']); ?></p>
            <div class="price-row"><strong><?php echo formatRand($p['price']); ?></strong><a href="product.php?id=<?php echo $p['product_id']; ?>">View</a></div>
        </div>
    </article>
<?php endwhile; ?>
</div>
<section class="info-strip">
    <div><strong>Buyer protection flow</strong><span>Verified accounts and clear delivery capture.</span></div>
    <div><strong>Seller approval</strong><span>Only approved sellers can upload products.</span></div>
    <div><strong>Product messages</strong><span>Chats are attached to the item being discussed.</span></div>
</section>
<?php include 'footer.php'; ?>
