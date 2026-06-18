<?php
session_start();
require_once 'DBConn.php';

$woodDb->query('SET FOREIGN_KEY_CHECKS=0');
$tables = ['tblMessage','tblOrderItem','tblOrder','tblCart','tblProduct','tblUser'];
foreach ($tables as $table) { $woodDb->query("DROP TABLE IF EXISTS `$table`"); }
$woodDb->query('SET FOREIGN_KEY_CHECKS=1');

$woodDb->query("CREATE TABLE tblUser (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    username VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
    is_verified TINYINT(1) NOT NULL DEFAULT 0,
    seller_status ENUM('none','pending','approved','rejected') NOT NULL DEFAULT 'none',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB");

$woodDb->query("CREATE TABLE tblProduct (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT NOT NULL,
    product_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(120) NOT NULL,
    brand VARCHAR(80) NOT NULL,
    category VARCHAR(60) NOT NULL,
    size VARCHAR(20) NOT NULL,
    item_condition VARCHAR(40) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    status ENUM('available','sold') NOT NULL DEFAULT 'available',
    admin_approved TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
) ENGINE=InnoDB");

$woodDb->query("CREATE TABLE tblCart (
    cart_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tblProduct(product_id) ON DELETE CASCADE,
    UNIQUE KEY unique_cart_item (user_id, product_id)
) ENGINE=InnoDB");

$woodDb->query("CREATE TABLE tblOrder (
    order_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    address VARCHAR(255) NOT NULL,
    city VARCHAR(80) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    phone VARCHAR(30) NOT NULL,
    total DECIMAL(10,2) NOT NULL DEFAULT 0,
    status ENUM('received','processing','delivered') NOT NULL DEFAULT 'received',
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
) ENGINE=InnoDB");

$woodDb->query("CREATE TABLE tblOrderItem (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id) REFERENCES tblOrder(order_id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES tblProduct(product_id) ON DELETE CASCADE
) ENGINE=InnoDB");

$woodDb->query("CREATE TABLE tblMessage (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    buyer_id INT NOT NULL,
    seller_id INT NOT NULL,
    sender_id INT NOT NULL,
    message_text TEXT NOT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES tblProduct(product_id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (seller_id) REFERENCES tblUser(user_id) ON DELETE CASCADE,
    FOREIGN KEY (sender_id) REFERENCES tblUser(user_id) ON DELETE CASCADE
) ENGINE=InnoDB");

function addUser(mysqli $db, string $name, string $email, string $username, string $password, string $role, int $verified, string $sellerStatus): int {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare('INSERT INTO tblUser (name,email,username,password,role,is_verified,seller_status) VALUES (?,?,?,?,?,?,?)');
    $stmt->bind_param('sssssis', $name, $email, $username, $hash, $role, $verified, $sellerStatus);
    $stmt->execute();
    return $db->insert_id;
}

$adminId = addUser($woodDb, 'Pastimes Admin', 'admin@pastimes.co.za', 'admin', 'admin1234', 'admin', 1, 'none');
$buyerId = addUser($woodDb, 'John Buyer', 'buyer@pastimes.co.za', 'buyer', 'buyer123', 'buyer', 1, 'none');
$sellerId = addUser($woodDb, 'Sarah Seller', 'seller@pastimes.co.za', 'seller', 'seller123', 'seller', 1, 'approved');
$pendingSeller = addUser($woodDb, 'Pending Seller', 'pending@pastimes.co.za', 'pendingseller', 'seller123', 'seller', 0, 'pending');

$products = [
    [$sellerId, 'PT-1001', 'Black Hoodie', 'Nike', 'Hoodies', 'M', 'Good', 'Warm black hoodie in good condition with clean stitching and no major damage.', 250.00, 'Black Hoodie.jpg', 1],
    [$sellerId, 'PT-1002', 'Blue Jeans', 'Levi\'s', 'Jeans', '32', 'Very Good', 'Classic blue jeans with a straight fit. Perfect for casual outfits.', 320.00, 'Blue Jeans.webp', 1],
    [$sellerId, 'PT-1003', 'Leather Jacket', 'Zara', 'Jackets', 'L', 'Excellent', 'Premium second-hand leather jacket with a clean finish and strong zip.', 650.00, 'Leather Jacket.webp', 1],
    [$sellerId, 'PT-1004', 'White T-Shirt', 'Adidas', 'T-Shirts', 'M', 'Good', 'Simple white branded t-shirt suitable for everyday wear.', 160.00, 'White T-Shirt.webp', 1],
    [$sellerId, 'PT-1005', 'Street Cap', 'Puma', 'Accessories', 'One Size', 'Good', 'Clean cap with adjustable strap and minimal signs of use.', 120.00, 'Cap.jpg', 1],
    [$pendingSeller, 'PT-2001', 'Pending Denim Shirt', 'Diesel', 'Shirts', 'M', 'Good', 'This item demonstrates admin product approval because it starts hidden.', 210.00, 'Blue Jeans.webp', 0]
];
$stmt = $woodDb->prepare('INSERT INTO tblProduct (seller_id,product_code,name,brand,category,size,item_condition,description,price,image,admin_approved) VALUES (?,?,?,?,?,?,?,?,?,?,?)');
foreach ($products as $p) {
    $stmt->bind_param('isssssssdsi', $p[0], $p[1], $p[2], $p[3], $p[4], $p[5], $p[6], $p[7], $p[8], $p[9], $p[10]);
    $stmt->execute();
}

file_put_contents(__DIR__ . '/userData.txt', "admin@pastimes.co.za admin admin1234\nbuyer@pastimes.co.za buyer buyer123\nseller@pastimes.co.za seller seller123\n");

$sql = '';
$res = $woodDb->query('SHOW TABLES');
while ($row = $res->fetch_array()) {
    $table = $row[0];
    $create = $woodDb->query("SHOW CREATE TABLE `$table`")->fetch_assoc();
    $sql .= "DROP TABLE IF EXISTS `$table`;\n" . $create['Create Table'] . ";\n\n";
}
file_put_contents(__DIR__ . '/myClothingStore.sql', $sql);
file_put_contents(__DIR__ . '/database/myClothingStore.sql', $sql);

$pageTitle = 'Database Loaded';
include 'header.php';
?>
<section class="hero compact">
    <div>
        <span class="eyebrow">Setup complete</span>
        <h1>ClothingStore database is ready.</h1>
        <p>Tables, sample users, approved products, and a pending product were created successfully.</p>
        <div class="hero-actions">
            <a class="btn primary" href="index.php">Open Website</a>
            <a class="btn ghost" href="admin_login.php">Admin Login</a>
        </div>
    </div>
</section>
<section class="panel">
    <h2>Demo accounts</h2>
    <div class="grid three">
        <div class="stat-card"><strong>Admin</strong><span>admin / admin1234</span></div>
        <div class="stat-card"><strong>Buyer</strong><span>buyer / buyer123</span></div>
        <div class="stat-card"><strong>Seller</strong><span>seller / seller123</span></div>
    </div>
</section>
<?php include 'footer.php'; ?>
