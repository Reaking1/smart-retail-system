<?php
session_start();
include __DIR__ . "/../includes/db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Smart Retail</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="container">
    <h1>Available Products</h1>

    <div class="products">
        <?php
        if (!isset($conn)) {
            die("Database connection not initialized.");
        }

        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "<p>" . htmlspecialchars($row['description']) . "</p>";
                echo "<p><strong>R " . htmlspecialchars($row['price']) . "</strong></p>";
                echo "<a href='cart.php?add=" . urlencode($row['product_id']) . "'><button>Add to Cart</button></a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
