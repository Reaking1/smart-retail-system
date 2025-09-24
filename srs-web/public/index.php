<?php
include __DIR__ . "/../includes/db.php";
include __DIR__ . "/../includes/header.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Smart Retail System</title>
   <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Welcome to Smart Retail System</h1>
        <p>Your one-stop shop for all products.</p>

        <h2>Featured Products</h2>
        <div class="products">
            <?php
            $sql = "SELECT * FROM products LIMIT 4";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='product'>";
                    echo "<h3>" . $row['name'] . "</h3>";
                    echo "<p>" . $row['description'] . "</p>";
                    echo "<p><strong>R " . $row['price'] . "</strong></p>";
                    echo "<a href='products.php'>View More</a>";
                    echo "</div>";
                }
            } else {
                echo "<p>No products available.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
