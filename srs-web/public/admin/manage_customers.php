<?php
session_start();
include("../includes/db.php");
include("../includes/auth.php"); // Protect page

// Fetch all customers
$sql = "SELECT * FROM customers ORDER BY customer_id DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Customers</title>
   <link rel="stylesheet" href="../../assets/css/styles.css">
</head>
<body class="manage-customers">
<?php include("../includes/header.php"); ?>

<div class="container">
    <h1>Manage Customers</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Registered At</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['customer_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No customers found.</p>
    <?php endif; ?>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>
</div>
</body>
</html>
