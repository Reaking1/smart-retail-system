<?php 
include __DIR__ . "/config.php"; 
// Get cart count
$cart_count = isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0;
?>

   <link rel="stylesheet" href="../assets/css/styles.css">

<div class="navbar">
    <a href="<?php echo BASE_URL; ?>index.php">Home</a>
    <a href="<?php echo BASE_URL; ?>products.php">Products</a>
    <a href="<?php echo BASE_URL; ?>cart.php">
        Cart
        <?php if($cart_count > 0): ?>
            <span class="cart-count"><?php echo $cart_count; ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo BASE_URL; ?>admin/login.php">Login</a>
    <a href="<?php echo BASE_URL; ?>register.php">Register</a>
</div>
