<?php
$pageTitle = 'Register';
include 'header.php';
$error = '';
$name = $email = $username = '';
$role = 'buyer';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = cleanInput($_POST['name'] ?? '');
    $email = cleanInput($_POST['email'] ?? '');
    $username = cleanInput($_POST['username'] ?? '');
    $role = in_array($_POST['role'] ?? 'buyer', ['buyer','seller'], true) ? $_POST['role'] : 'buyer';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if ($name === '' || $email === '' || $username === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (strlen($password) !== 8) {
        $error = 'Password must be exactly 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $check = $woodDb->prepare('SELECT user_id FROM tblUser WHERE email=? OR username=?');
        $check->bind_param('ss', $email, $username);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = 'Email or username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sellerStatus = $role === 'seller' ? 'pending' : 'none';
            $stmt = $woodDb->prepare('INSERT INTO tblUser (name,email,username,password,role,is_verified,seller_status) VALUES (?,?,?,?,?,0,?)');
            $stmt->bind_param('ssssss', $name, $email, $username, $hash, $role, $sellerStatus);
            $stmt->execute();
            flash($role === 'seller' ? 'Seller account created. Admin must verify your account and approve seller status.' : 'Buyer account created. Admin must verify your account before login.');
            header('Location: login.php'); exit();
        }
    }
}
?>
<section class="auth-shell">
    <div class="auth-copy"><span class="eyebrow">Join Pastimes</span><h1>Create your account</h1><p>Buyers can shop after admin verification. Sellers need seller approval before product uploads.</p></div>
    <form method="post" class="form-card">
        <h2>Register</h2><?php if ($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?>
        <label>Full name</label><input type="text" name="name" value="<?php echo e($name); ?>" required>
        <label>Email</label><input type="email" name="email" value="<?php echo e($email); ?>" required>
        <label>Username</label><input type="text" name="username" value="<?php echo e($username); ?>" required>
        <label>Password <small>exactly 8 characters</small></label><input type="password" name="password" minlength="8" maxlength="8" required>
        <label>Confirm password</label><input type="password" name="confirm_password" minlength="8" maxlength="8" required>
        <label>Account type</label><select name="role"><option value="buyer" <?php if($role==='buyer') echo 'selected'; ?>>Buyer</option><option value="seller" <?php if($role==='seller') echo 'selected'; ?>>Seller</option></select>
        <button class="btn primary full" type="submit">Create Account</button><p class="muted">Already registered? <a href="login.php">Login here</a>.</p>
    </form>
</section>
<?php include 'footer.php'; ?>
