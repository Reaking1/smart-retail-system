<?php
session_start();
include("includes/db.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products - Smart Retail</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h1>Available Products</h1>

    <div class="products">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<div class='product'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<p><strong>R " . $row['price'] . "</strong></p>";
                echo "<a href='cart.php?add=" . $row['product_id'] . "'><button>Add to Cart</button></a>";
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
