<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } require_once 'DBConn.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? e($pageTitle) . ' - Pastimes' : 'Pastimes'; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<nav class="topbar">
    <a class="brand" href="index.php"><span class="brand-mark">P</span><span>Pastimes</span></a>
    <button class="menu-toggle" onclick="document.querySelector('.nav-links').classList.toggle('show')">☰</button>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="shop.php">Shop</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="messages.php">Messages</a>
            <?php if (($_SESSION['role'] ?? '') === 'buyer'): ?><a href="cart.php">Cart</a><?php endif; ?>
            <a class="nav-pill" href="logout.php">Logout</a>
        <?php elseif (isset($_SESSION['admin_logged_in'])): ?>
            <a href="admin_dashboard.php">Admin</a>
            <a class="nav-pill" href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
            <a class="nav-pill" href="admin_login.php">Admin</a>
        <?php endif; ?>
    </div>
</nav>
<main class="page">
<?php showFlash(); ?>
