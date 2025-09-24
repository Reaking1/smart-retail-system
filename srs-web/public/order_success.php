<?php
session_start();
include __DIR__ . "/../includes/db.php"; 

// Check if order_id exists
if (!isset($_GET['order_id'])) {
    header("Location: index.php");
    exit;
}

$order_id = intval($_GET['order_id']);

// Fetch order details
$stmt = $conn->prepare("
    SELECT o.order_id, o.total_amount, o.status, o.created_at, c.name, c.email, c.phone, c.address
    FROM orders o
    JOIN customers c ON o.customer_id = c.customer_id
    WHERE o.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<h2>Order not found.</h2>";
    exit;
}

// Fetch ordered items
$stmt = $conn->prepare("
    SELECT oi.quantity, oi.price, p.name
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success</title>
    <link rel="stylesheet"  href="../assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="order-success-container">
    <h1>🎉 Order Placed Successfully!</h1>
    <p>Thank you, <b><?php echo htmlspecialchars($order['name']); ?></b>!</p>
    <p>Your Order ID is <b>#<?php echo $order['order_id']; ?></b></p>
    <p>Order Status: <b><?php echo $order['status']; ?></b></p>
    <p>Total Amount: <b>R <?php echo number_format($order['total_amount'], 2); ?></b></p>
    <p>Placed On: <b><?php echo $order['created_at']; ?></b></p>

    <h3>Delivery Details</h3>
    <p>Email: <?php echo htmlspecialchars($order['email']); ?></p>
    <p>Phone: <?php echo htmlspecialchars($order['phone']); ?></p>
    <p>Address: <?php echo htmlspecialchars($order['address']); ?></p>

    <h3>Order Items</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
        </tr>
        <?php while ($item = $items->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>R <?php echo number_format($item['price'], 2); ?></td>
            <td>R <?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="products.php">🛍️ Continue Shopping</a>
</div>

<?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
