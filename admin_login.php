<?php
$pageTitle='Admin Login'; include 'header.php'; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $identifier=cleanInput($_POST['identifier'] ?? ''); $password=$_POST['password'] ?? '';
    $stmt=$woodDb->prepare("SELECT * FROM tblUser WHERE (username=? OR email=?) AND role='admin' LIMIT 1"); $stmt->bind_param('ss',$identifier,$identifier); $stmt->execute(); $admin=$stmt->get_result()->fetch_assoc();
    if($admin && password_verify($password,$admin['password'])){ $_SESSION['admin_logged_in']=true; $_SESSION['admin_id']=$admin['user_id']; $_SESSION['admin_name']=$admin['name']; flash('Admin logged in.'); header('Location: admin_dashboard.php'); exit(); } else { $error='Invalid admin login.'; }
}
?>
<section class="auth-shell"><div class="auth-copy"><span class="eyebrow">Admin Area</span><h1>Platform control</h1><p>Verify buyers, approve sellers, approve product uploads, and monitor delivery orders.</p></div><form method="post" class="form-card"><h2>Admin Login</h2><?php if($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?><label>Username or email</label><input name="identifier" required><label>Password</label><input type="password" name="password" required><button class="btn primary full">Login</button><p class="muted">Demo: admin / admin1234</p></form></section>
<?php include 'footer.php'; ?>
