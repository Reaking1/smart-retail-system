<?php
session_start();
include __DIR__ . "/../includes/db.php"; 
include __DIR__ . "/../includes/header.php"; 

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: products.php");
    exit;
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $address = $_POST['address'];

    // Insert customer (for now, auto-register if not logged in)
    $stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
    $default_pass = password_hash("123456", PASSWORD_BCRYPT); // default password
    $stmt->bind_param("sssss", $name, $email, $default_pass, $phone, $address);
    $stmt->execute();
    $customer_id = $stmt->insert_id;
    $stmt->close();

    // Insert order
    $total_amount = 0;
    foreach ($_SESSION['cart'] as $id => $qty) {
        $sql = "SELECT * FROM products WHERE product_id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $total_amount += $row['price'] * $qty;
    }

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, status, total_amount) VALUES (?, 'Pending', ?)");
    $stmt->bind_param("id", $customer_id, $total_amount);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert order items + reduce stock
    foreach ($_SESSION['cart'] as $id => $qty) {
        $sql = "SELECT * FROM products WHERE product_id = $id";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        $price = $row['price'];

        $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiid", $order_id, $id, $qty, $price);
        $stmt->execute();
        $stmt->close();

        $new_stock = $row['stock_quantity'] - $qty;
        $conn->query("UPDATE products SET stock_quantity = $new_stock WHERE product_id = $id");
    }

    // Insert payment (simulated COD / pending)
    $stmt = $conn->prepare("INSERT INTO payments (order_id, amount, status, transaction_ref) VALUES (?, ?, 'Pending', ?)");
    $txn_ref = uniqid("TXN");
    $stmt->bind_param("ids", $order_id, $total_amount, $txn_ref);
    $stmt->execute();
    $stmt->close();

    // Clear cart
    $_SESSION['cart'] = [];

    $success = "Order placed successfully! Your Order ID is #$order_id";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

<div class="checkout-container">
    <h1>Checkout</h1>
    <?php if (isset($success)): ?>
        <p class="success-msg"><?php echo $success; ?></p>
        <a href="products.php" class="continue-btn">Continue Shopping</a>
    <?php else: ?>
        <h3>Enter Your Details</h3>
        <form method="POST" action="checkout.php">
            <label>Full Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Phone</label>
            <input type="text" name="phone" required>
            <label>Address</label>
            <textarea name="address" required></textarea>
            <button type="submit">Place Order</button>
        </form>
    <?php endif; ?>
</div>


<?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
