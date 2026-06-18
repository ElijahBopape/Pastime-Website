<?php
$pageTitle = 'Login'; include 'header.php';
$error = ''; $identifier = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = cleanInput($_POST['identifier'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $woodDb->prepare('SELECT * FROM tblUser WHERE username=? OR email=? LIMIT 1');
    $stmt->bind_param('ss', $identifier, $identifier);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    if (!$user || !password_verify($password, $user['password'])) {
        $error = 'Incorrect username/email or password.';
    } elseif ((int)$user['is_verified'] !== 1) {
        $error = 'Your account is pending admin verification.';
    } elseif ($user['role'] === 'seller' && $user['seller_status'] !== 'approved') {
        $error = 'Your seller account is waiting for admin approval.';
    } else {
        $_SESSION['user_id'] = (int)$user['user_id']; $_SESSION['name'] = $user['name']; $_SESSION['role'] = $user['role'];
        flash('Welcome back, ' . $user['name']); header('Location: dashboard.php'); exit();
    }
}
?>
<section class="auth-shell">
    <div class="auth-copy"><span class="eyebrow">Welcome back</span><h1>Login to Pastimes</h1><p>Use your username or email address. New accounts must be approved by the admin first.</p></div>
    <form method="post" class="form-card">
        <h2>User Login</h2><?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>
        <label>Username or email</label><input type="text" name="identifier" value="<?php echo e($identifier); ?>" required>
        <label>Password</label><input type="password" name="password" required>
        <button class="btn primary full" type="submit">Login</button>
        <p class="muted">Need an account? <a href="register.php">Register</a>.</p>
    </form>
</section>
<?php include 'footer.php'; ?>
