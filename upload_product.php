<?php
$pageTitle='Upload Product'; include 'header.php'; requireRole('seller'); $uid=(int)$_SESSION['user_id']; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $name=cleanInput($_POST['name']); $brand=cleanInput($_POST['brand']); $category=cleanInput($_POST['category']); $size=cleanInput($_POST['size']); $condition=cleanInput($_POST['condition']); $description=cleanInput($_POST['description']); $price=(float)$_POST['price'];
    if(!$name||!$brand||!$category||!$size||!$condition||!$description||$price<=0){ $error='All product fields are required.'; }
    elseif(!isset($_FILES['image']) || $_FILES['image']['error']!==UPLOAD_ERR_OK){ $error='Please choose a product image.'; }
    else{
        $allowed=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp']; $mime=mime_content_type($_FILES['image']['tmp_name']);
        if(!isset($allowed[$mime])){ $error='Only JPG, PNG and WEBP images are allowed.'; }
        else{ $code='PT-'.date('His').rand(10,99); $file='uploads/'.$code.'.'.$allowed[$mime]; move_uploaded_file($_FILES['image']['tmp_name'], __DIR__.'/'.$file); $stmt=$woodDb->prepare('INSERT INTO tblProduct(seller_id,product_code,name,brand,category,size,item_condition,description,price,image,admin_approved) VALUES(?,?,?,?,?,?,?,?,?,?,0)'); $stmt->bind_param('isssssssds',$uid,$code,$name,$brand,$category,$size,$condition,$description,$price,$file); $stmt->execute(); flash('Product uploaded. It will appear in the shop after admin approval.'); header('Location: seller_dashboard.php'); exit(); }
    }
}
?>
<section class="auth-shell"><div class="auth-copy"><span class="eyebrow">Seller upload</span><h1>Add a clothing item</h1><p>Upload a clear image. CSS will crop and fit the image neatly across the site.</p><img id="preview" class="preview-img" style="display:none" alt="Preview"></div><form method="post" enctype="multipart/form-data" class="form-card"><h2>Product details</h2><?php if($error): ?><div class="alert alert-error"><?php echo e($error); ?></div><?php endif; ?><label>Image</label><input type="file" name="image" accept="image/*" onchange="previewImage(this,'preview')" required><label>Name</label><input name="name" required><label>Brand</label><input name="brand" required><label>Category</label><input name="category" placeholder="Hoodies, Shoes, Jeans" required><label>Size</label><input name="size" required><label>Condition</label><select name="condition"><option>Excellent</option><option>Very Good</option><option>Good</option><option>Fair</option></select><label>Description</label><textarea name="description" rows="4" required></textarea><label>Price</label><input type="number" step="0.01" min="1" name="price" required><button class="btn primary full">Submit for Approval</button></form></section>
<?php include 'footer.php'; ?>
