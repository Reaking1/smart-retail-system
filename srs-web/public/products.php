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
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<?php include __DIR__ . "/../includes/header.php"; ?>

<div class="container">
    <h1 class="page-title"><i class="fa-solid fa-store"></i> Available Products</h1>

    <div class="products-grid">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $img = !empty($row['image_url']) ? "../" . htmlspecialchars($row['image_url']) : "../assets/images/placeholder.png";

                echo "<div class='product-card'>";
                echo "    <img src='{$img}' alt='" . htmlspecialchars($row['name']) . "' class='product-img'>";
                echo "    <div class='product-info'>";
                echo "        <h3>" . htmlspecialchars($row['name']) . "</h3>";
                echo "        <p class='desc'>" . htmlspecialchars($row['description']) . "</p>";
                echo "        <p class='price'><i class='fa-solid fa-tag'></i> R " . htmlspecialchars($row['price']) . "</p>";
                echo "        <a href='cart.php?add=" . urlencode($row['product_id']) . "' class='btn'><i class='fa-solid fa-cart-plus'></i> Add to Cart</a>";
                echo "    </div>"; // product-info
                echo "</div>"; // product-card
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>
