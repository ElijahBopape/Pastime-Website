<?php
$pageTitle='Seller Dashboard'; include 'header.php'; requireRole('seller'); $uid=(int)$_SESSION['user_id']; $user=currentUser($woodDb);
$approved=$woodDb->query("SELECT COUNT(*) c FROM tblProduct WHERE seller_id=$uid AND admin_approved=1")->fetch_assoc()['c'];
$pending=$woodDb->query("SELECT COUNT(*) c FROM tblProduct WHERE seller_id=$uid AND admin_approved=0")->fetch_assoc()['c'];
$sold=$woodDb->query("SELECT COUNT(*) c FROM tblProduct WHERE seller_id=$uid AND status='sold'")->fetch_assoc()['c'];
$products=$woodDb->query("SELECT * FROM tblProduct WHERE seller_id=$uid ORDER BY created_at DESC");
?>
<section class="dashboard-hero seller"><div><span class="eyebrow">Seller Dashboard</span><h1>Hello, <?php echo e($user['name']); ?></h1><p>Upload clothing, monitor approval status, and reply to buyer messages.</p></div><a class="btn primary" href="upload_product.php">Upload Product</a></section>
<div class="grid three"><div class="stat-card"><strong><?php echo $approved; ?></strong><span>Approved listings</span></div><div class="stat-card"><strong><?php echo $pending; ?></strong><span>Pending approvals</span></div><div class="stat-card"><strong><?php echo $sold; ?></strong><span>Sold items</span></div></div>
<section class="panel"><div class="panel-head"><h2>Your products</h2><a class="btn ghost" href="messages.php">Messages</a></div><div class="product-grid compact-grid"><?php while($p=$products->fetch_assoc()): ?><article class="product-card"><img src="<?php echo e(productImage($p['image'])); ?>"><div class="product-info"><span class="badge <?php echo $p['admin_approved']?'ok':'warn'; ?>"><?php echo $p['admin_approved']?'Approved':'Pending'; ?></span><h3><?php echo e($p['name']); ?></h3><p><?php echo e($p['product_code']); ?> • <?php echo e($p['status']); ?></p><strong><?php echo formatRand($p['price']); ?></strong></div></article><?php endwhile; ?></div></section>
<?php include 'footer.php'; ?>
