<?php
$pageTitle = 'Dashboard'; include 'header.php'; requireLogin();
$user = currentUser($woodDb);
if (($user['role'] ?? '') === 'seller') { header('Location: seller_dashboard.php'); exit(); }
$cartCount = $woodDb->query('SELECT COUNT(*) AS c FROM tblCart WHERE user_id=' . (int)$_SESSION['user_id'])->fetch_assoc()['c'];
$msgCount = $woodDb->query('SELECT COUNT(*) AS c FROM tblMessage WHERE buyer_id=' . (int)$_SESSION['user_id'])->fetch_assoc()['c'];
$orderCount = $woodDb->query('SELECT COUNT(*) AS c FROM tblOrder WHERE user_id=' . (int)$_SESSION['user_id'])->fetch_assoc()['c'];
?>
<section class="dashboard-hero"><div><span class="eyebrow">Buyer Dashboard</span><h1>Hello, <?php echo e($user['name']); ?></h1><p>Browse approved listings, contact sellers, manage your cart, and track orders.</p></div><a class="btn primary" href="shop.php">Shop Now</a></section>
<div class="grid three"><div class="stat-card"><strong><?php echo $cartCount; ?></strong><span>Cart items</span></div><div class="stat-card"><strong><?php echo $msgCount; ?></strong><span>Product messages</span></div><div class="stat-card"><strong><?php echo $orderCount; ?></strong><span>Orders placed</span></div></div>
<section class="quick-actions"><a href="shop.php">Browse products</a><a href="cart.php">View cart</a><a href="messages.php">Messages</a></section>
<?php include 'footer.php'; ?>
