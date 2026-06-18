<?php
$pageTitle='Checkout'; include 'header.php'; requireRole('buyer'); $uid=(int)$_SESSION['user_id'];
$items=$woodDb->query("SELECT c.*, p.product_id, p.name, p.price FROM tblCart c JOIN tblProduct p ON c.product_id=p.product_id WHERE c.user_id=$uid AND p.status='available' AND p.admin_approved=1");
if($items->num_rows===0){ flash('Your cart is empty.','error'); header('Location: cart.php'); exit(); }
$cart=[]; $total=0; while($i=$items->fetch_assoc()){ $cart[]=$i; $total += $i['price']*$i['quantity']; }
if($_SERVER['REQUEST_METHOD']==='POST'){
    $full=cleanInput($_POST['full_name']); $addr=cleanInput($_POST['address']); $city=cleanInput($_POST['city']); $postal=cleanInput($_POST['postal_code']); $phone=cleanInput($_POST['phone']);
    if($full && $addr && $city && $postal && $phone){
        $stmt=$woodDb->prepare('INSERT INTO tblOrder(user_id,full_name,address,city,postal_code,phone,total) VALUES(?,?,?,?,?,?,?)'); $stmt->bind_param('isssssd',$uid,$full,$addr,$city,$postal,$phone,$total); $stmt->execute(); $orderId=$woodDb->insert_id;
        foreach($cart as $i){ $stmt=$woodDb->prepare('INSERT INTO tblOrderItem(order_id,product_id,price,quantity) VALUES(?,?,?,?)'); $stmt->bind_param('iidi',$orderId,$i['product_id'],$i['price'],$i['quantity']); $stmt->execute(); $woodDb->query('UPDATE tblProduct SET status="sold" WHERE product_id='.(int)$i['product_id']); }
        $woodDb->query("DELETE FROM tblCart WHERE user_id=$uid"); flash('Order placed. Delivery details saved for admin follow-up.'); header('Location: dashboard.php'); exit();
    }
}
?>
<section class="auth-shell"><div class="auth-copy"><span class="eyebrow">Checkout</span><h1>Delivery details</h1><p>Your order total is <strong><?php echo formatRand($total); ?></strong>. Admin can follow up on delivery from the dashboard.</p></div><form method="post" class="form-card"><h2>Courier address</h2><label>Full name</label><input name="full_name" required><label>Residential/work address</label><input name="address" required><label>City</label><input name="city" required><label>Postal code</label><input name="postal_code" required><label>Phone</label><input name="phone" required><button class="btn primary full">Place Order</button></form></section>
<?php include 'footer.php'; ?>
