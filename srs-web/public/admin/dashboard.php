<?php
session_start();
include(__DIR__ . "/../../includes/db.php");
include(__DIR__ . "/../../includes/auth.php");
// Ensures only logged-in admins can view

// Fetch stats
$totalCustomers = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$totalOrders    = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$totalRevenue   = $conn->query("SELECT IFNULL(SUM(total_amount), 0) AS revenue FROM orders")->fetch_assoc()['revenue'];
$totalProducts  = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../assets/css/styles.css">
  
</head>
<body  class="admin-dashboard">
    <?php include("/../includes/header.php"); ?>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <p>Welcome back, <b><?php echo $_SESSION['admin_name']; ?></b> 👋</p>

        <div class="stats">
            <div class="card">
                <h2><?php echo $totalCustomers; ?></h2>
                <p>Customers</p>
            </div>
            <div class="card">
                <h2><?php echo $totalOrders; ?></h2>
                <p>Orders</p>
            </div>
            <div class="card">
                <h2>$<?php echo number_format($totalRevenue, 2); ?></h2>
                <p>Total Revenue</p>
            </div>
            <div class="card">
                <h2><?php echo $totalProducts; ?></h2>
                <p>Products</p>
            </div>
        </div>

        <nav>
            <a href="manage_products.php">📦 Manage Products</a>
            <a href="manage_orders.php">🧾 Manage Orders</a>
            <a href="manage_customers.php">👥 Manage Customers</a>
            <a href="logout.php">🚪 Logout</a>
        </nav>
    </div>
</body>
</html>
