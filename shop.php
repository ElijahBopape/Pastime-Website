<?php
$pageTitle = 'Shop'; include 'header.php';
$search = cleanInput($_GET['search'] ?? ''); $category = cleanInput($_GET['category'] ?? '');
$where = "WHERE p.admin_approved=1 AND p.status='available'"; $types=''; $params=[];
if ($search !== '') { $where .= " AND (p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)"; $like = "%$search%"; $params = [$like,$like,$like]; $types .= 'sss'; }
if ($category !== '') { $where .= " AND p.category=?"; $params[]=$category; $types.='s'; }
$sql = "SELECT p.*, u.name AS seller_name FROM tblProduct p JOIN tblUser u ON p.seller_id=u.user_id $where ORDER BY p.created_at DESC";
$stmt = $woodDb->prepare($sql); if ($params) { $stmt->bind_param($types, ...$params); } $stmt->execute(); $products=$stmt->get_result();
$cats=$woodDb->query("SELECT DISTINCT category FROM tblProduct WHERE admin_approved=1 ORDER BY category");
?>
<section class="section-heading"><span class="eyebrow">Marketplace</span><h1>Shop approved clothing</h1><p>Only admin-approved available items appear here.</p></section>
<form class="filter-bar" method="get"><input type="text" name="search" placeholder="Search brand, item or description" value="<?php echo e($search); ?>"><select name="category"><option value="">All categories</option><?php while($c=$cats->fetch_assoc()): ?><option value="<?php echo e($c['category']); ?>" <?php if($category===$c['category']) echo 'selected'; ?>><?php echo e($c['category']); ?></option><?php endwhile; ?></select><button class="btn primary">Filter</button><a class="btn ghost" href="shop.php">Reset</a></form>
<div class="product-grid">
<?php if ($products->num_rows === 0): ?><div class="empty-state">No approved products found.</div><?php endif; ?>
<?php while($p=$products->fetch_assoc()): ?><article class="product-card"><a href="product.php?id=<?php echo $p['product_id']; ?>"><img src="<?php echo e(productImage($p['image'])); ?>" alt="<?php echo e($p['name']); ?>"></a><div class="product-info"><span class="badge"><?php echo e($p['product_code']); ?></span><h3><?php echo e($p['name']); ?></h3><p><?php echo e($p['brand']); ?> • <?php echo e($p['category']); ?> • Size <?php echo e($p['size']); ?></p><div class="price-row"><strong><?php echo formatRand($p['price']); ?></strong><a href="product.php?id=<?php echo $p['product_id']; ?>">Details</a></div></div></article><?php endwhile; ?>
</div>
<?php include 'footer.php'; ?>
