<?php
$pageTitle='Cart'; include 'header.php'; requireRole('buyer'); $uid=(int)$_SESSION['user_id'];
if(isset($_GET['remove'])){ $id=(int)$_GET['remove']; $stmt=$woodDb->prepare('DELETE FROM tblCart WHERE cart_id=? AND user_id=?'); $stmt->bind_param('ii',$id,$uid); $stmt->execute(); flash('Item removed.'); header('Location: cart.php'); exit(); }
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['update_cart'])){ foreach($_POST['quantity'] ?? [] as $cartId=>$qty){ $cartId=(int)$cartId; $qty=max(1,(int)$qty); $stmt=$woodDb->prepare('UPDATE tblCart SET quantity=? WHERE cart_id=? AND user_id=?'); $stmt->bind_param('iii',$qty,$cartId,$uid); $stmt->execute(); } flash('Cart updated.'); header('Location: cart.php'); exit(); }
$items=$woodDb->query("SELECT c.*, p.name, p.price, p.image, p.product_code FROM tblCart c JOIN tblProduct p ON c.product_id=p.product_id WHERE c.user_id=$uid AND p.status='available' AND p.admin_approved=1");
$total=0;
?>
<section class="section-heading"><span class="eyebrow">Buyer cart</span><h1>Your selected items</h1></section>
<form method="post" class="panel"><div class="table-wrap"><table><tr><th>Item</th><th>Code</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr>
<?php if($items->num_rows===0): ?><tr><td colspan="6" class="empty-state">Your cart is empty.</td></tr><?php endif; ?>
<?php while($i=$items->fetch_assoc()): $sub=$i['price']*$i['quantity']; $total+=$sub; ?><tr><td class="item-cell"><img class="table-img" src="<?php echo e(productImage($i['image'])); ?>"><span><?php echo e($i['name']); ?></span></td><td><?php echo e($i['product_code']); ?></td><td><?php echo formatRand($i['price']); ?></td><td><input class="qty" type="number" name="quantity[<?php echo $i['cart_id']; ?>]" value="<?php echo $i['quantity']; ?>" min="1"></td><td><?php echo formatRand($sub); ?></td><td><a class="btn danger small" href="?remove=<?php echo $i['cart_id']; ?>" onclick="return confirmAction('Remove item?')">Remove</a></td></tr><?php endwhile; ?>
</table></div><div class="cart-footer"><strong>Total: <?php echo formatRand($total); ?></strong><div><button class="btn ghost" name="update_cart">Update Cart</button><?php if($total>0): ?><a class="btn primary" href="checkout.php">Checkout</a><?php endif; ?></div></div></form>
<?php include 'footer.php'; ?>
