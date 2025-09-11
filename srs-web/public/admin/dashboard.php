<?php
session_start();
include("../includes/db.php");
include("../includes/auth.php"); // Ensures only logged-in admins can view

// Fetch stats
$totalCustomers = $conn->query("SELECT COUNT(*) AS total FROM customers")->fetch_assoc()['total'];
$totalOrders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$totalRevenue = $conn->query("SELECT IFNULL(SUM(total_amount), 0) AS revenue FROM orders")->fetch_assoc()['revenue'];
$totalProducts = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .container { width: 90%; margin: 30px auto; }
        h1 { color: #333; }
        .stats { display: flex; gap: 20px; margin-top: 20px; }
        .card { background: white; padding: 20px; border-radius: 10px; flex: 1; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.2); }
        .card h2 { margin: 0; font-size: 2em; color: #007bff; }
        .card p { margin: 5px 0 0; color: #555; }
        nav { margin-top: 30px; }
        nav a { display: inline-block; margin: 10px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        nav a:hover { background: #0056b3; }
    </style>
</head>
<body>
    <?php include("../includes/header.php"); ?>

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
