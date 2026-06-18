<?php
$pageTitle='Messages'; include 'header.php'; requireLogin();
$uid=(int)$_SESSION['user_id']; $role=$_SESSION['role'];
$productFilter=(int)($_GET['product_id'] ?? 0); $otherFilter=(int)($_GET['with'] ?? 0);
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['reply'])){
    $productId=(int)$_POST['product_id']; $buyerId=(int)$_POST['buyer_id']; $sellerId=(int)$_POST['seller_id']; $text=cleanInput($_POST['message_text']);
    if($text!=='') { $stmt=$woodDb->prepare('INSERT INTO tblMessage(product_id,buyer_id,seller_id,sender_id,message_text) VALUES(?,?,?,?,?)'); $stmt->bind_param('iiiis',$productId,$buyerId,$sellerId,$uid,$text); $stmt->execute(); flash('Reply sent.'); }
    header('Location: messages.php?product_id='.$productId.'&with='.($role==='buyer'?$sellerId:$buyerId)); exit();
}
$where = $role === 'buyer' ? 'm.buyer_id=?' : 'm.seller_id=?';
$stmt=$woodDb->prepare("SELECT m.product_id, m.buyer_id, m.seller_id, p.name AS product_name, p.product_code, p.image, b.name AS buyer_name, s.name AS seller_name, MAX(m.created_at) AS last_time FROM tblMessage m JOIN tblProduct p ON m.product_id=p.product_id JOIN tblUser b ON m.buyer_id=b.user_id JOIN tblUser s ON m.seller_id=s.user_id WHERE $where GROUP BY m.product_id,m.buyer_id,m.seller_id ORDER BY last_time DESC");
$stmt->bind_param('i',$uid); $stmt->execute(); $threads=$stmt->get_result();
$active=null; $msgs=null;
if($productFilter && $otherFilter){
    if($role==='buyer'){ $buyerId=$uid; $sellerId=$otherFilter; } else { $buyerId=$otherFilter; $sellerId=$uid; }
    $stmt=$woodDb->prepare('SELECT m.*, p.name AS product_name, p.product_code, b.name AS buyer_name, s.name AS seller_name FROM tblMessage m JOIN tblProduct p ON m.product_id=p.product_id JOIN tblUser b ON m.buyer_id=b.user_id JOIN tblUser s ON m.seller_id=s.user_id WHERE m.product_id=? AND m.buyer_id=? AND m.seller_id=? ORDER BY m.created_at');
    $stmt->bind_param('iii',$productFilter,$buyerId,$sellerId); $stmt->execute(); $msgs=$stmt->get_result(); $active=['product_id'=>$productFilter,'buyer_id'=>$buyerId,'seller_id'=>$sellerId];
} elseif($productFilter) {
    $stmt=$woodDb->prepare('SELECT m.*, p.name AS product_name, p.product_code, b.name AS buyer_name, s.name AS seller_name FROM tblMessage m JOIN tblProduct p ON m.product_id=p.product_id JOIN tblUser b ON m.buyer_id=b.user_id JOIN tblUser s ON m.seller_id=s.user_id WHERE m.product_id=? AND (m.buyer_id=? OR m.seller_id=?) ORDER BY m.created_at');
    $stmt->bind_param('iii',$productFilter,$uid,$uid); $stmt->execute(); $msgs=$stmt->get_result();
    if($first=$msgs->fetch_assoc()){ $active=['product_id'=>$first['product_id'],'buyer_id'=>$first['buyer_id'],'seller_id'=>$first['seller_id']]; $stmt=$woodDb->prepare('SELECT m.*, p.name AS product_name, p.product_code, b.name AS buyer_name, s.name AS seller_name FROM tblMessage m JOIN tblProduct p ON m.product_id=p.product_id JOIN tblUser b ON m.buyer_id=b.user_id JOIN tblUser s ON m.seller_id=s.user_id WHERE m.product_id=? AND m.buyer_id=? AND m.seller_id=? ORDER BY m.created_at'); $stmt->bind_param('iii',$active['product_id'],$active['buyer_id'],$active['seller_id']); $stmt->execute(); $msgs=$stmt->get_result(); }
}
?>
<section class="section-heading"><span class="eyebrow">Product conversations</span><h1>Messages</h1><p>Each conversation is linked to a product code so buyers and sellers always know what item is being discussed.</p></section>
<div class="messages-layout"><aside class="thread-list"><?php if($threads->num_rows===0): ?><div class="empty-state">No messages yet.</div><?php endif; ?><?php while($t=$threads->fetch_assoc()): $with=$role==='buyer'?$t['seller_id']:$t['buyer_id']; ?><a class="thread-card" href="messages.php?product_id=<?php echo $t['product_id']; ?>&with=<?php echo $with; ?>"><img src="<?php echo e(productImage($t['image'])); ?>"><div><strong><?php echo e($t['product_name']); ?></strong><span><?php echo e($t['product_code']); ?></span><small><?php echo $role==='buyer'?'Seller: '.e($t['seller_name']):'Buyer: '.e($t['buyer_name']); ?></small></div></a><?php endwhile; ?></aside><section class="chat-panel"><?php if($msgs && $active): ?><div class="chat-stream"><?php while($m=$msgs->fetch_assoc()): ?><div class="chat-bubble <?php echo $m['sender_id']==$uid?'mine':'theirs'; ?>"><strong><?php echo $m['sender_id']==$uid?'You':e($m['sender_id']==$m['buyer_id']?$m['buyer_name']:$m['seller_name']); ?></strong><p><?php echo e($m['message_text']); ?></p><small><?php echo e($m['created_at']); ?></small></div><?php endwhile; ?></div><form method="post" class="reply-box"><input type="hidden" name="product_id" value="<?php echo $active['product_id']; ?>"><input type="hidden" name="buyer_id" value="<?php echo $active['buyer_id']; ?>"><input type="hidden" name="seller_id" value="<?php echo $active['seller_id']; ?>"><textarea name="message_text" rows="3" placeholder="Type your reply" required></textarea><button class="btn primary" name="reply">Reply</button></form><?php else: ?><div class="empty-state">Choose a conversation or message a seller from a product page.</div><?php endif; ?></section></div>
<?php include 'footer.php'; ?>
