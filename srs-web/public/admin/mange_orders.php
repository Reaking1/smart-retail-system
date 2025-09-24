<?php
session_start();
include("../includes/db.php");
include("../includes/auth.php"); // Protect page

// Update order status
if (isset($_GET['update']) && isset($_GET['status'])) {
    $order_id = intval($_GET['update']);
    $status = $_GET['status'];
    $allowed_status = ['Pending', 'Shipped', 'Completed', 'Cancelled'];
    if (in_array($status, $allowed_status)) {
        $stmt = $conn->prepare("UPDATE orders SET status=? WHERE order_id=?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_orders.php");
    exit;
}

// Fetch all orders
$sql = "SELECT o.*, c.name AS customer_name FROM orders o JOIN customers c ON o.customer_id = c.customer_id ORDER BY o.order_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
     <link rel="stylesheet" href="../../assets/css/styles.css">
    
</head>
<body class="manage-orders">
<?php include("../includes/header.php"); ?>

<div class="container">
    <h1>Manage Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Placed On</th>
                <th>Actions</th>
            </tr>
            <?php while($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                    <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><?php echo $order['status']; ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                    <td>
                        <a href="manage_orders.php?update=<?php echo $order['order_id']; ?>&status=Pending" class="status pending">Pending</a>
                        <a href="manage_orders.php?update=<?php echo $order['order_id']; ?>&status=Shipped" class="status shipped">Shipped</a>
                        <a href="manage_orders.php?update=<?php echo $order['order_id']; ?>&status=Completed" class="status completed">Completed</a>
                        <a href="manage_orders.php?update=<?php echo $order['order_id']; ?>&status=Cancelled" class="status cancelled">Cancel</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No orders found.</p>
    <?php endif; ?>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>
