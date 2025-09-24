<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__ . "/../includes/db.php";
include __DIR__ . "/../includes/config.php"; // BASE_URL
include __DIR__ . "/../includes/header.php";
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

<div class="container">
    <h1 class="page-title"><i class="fa-solid fa-store"></i> Available Products</h1>

    <div class="products-grid">
        <?php
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
                $img = !empty($row['image_url']) ? BASE_URL . htmlspecialchars($row['image_url']) : BASE_URL . "assets/images/placeholder.png";
                $productId = urlencode($row['product_id']);
                $name = htmlspecialchars($row['name']);
                $desc = htmlspecialchars($row['description']);
                $price = htmlspecialchars($row['price']);
        ?>
        <div class="product-card">
            <img src="<?php echo $img; ?>" alt="<?php echo $name; ?>" class="product-img">
            <div class="product-info">
                <h3><?php echo $name; ?></h3>
                <p class="desc"><?php echo $desc; ?></p>
                <p class="price"><i class="fa-solid fa-tag"></i> R <?php echo $price; ?></p>
                <button class="btn add-to-cart" data-id="<?php echo $productId; ?>">
                    <i class="fa-solid fa-cart-plus"></i> Add to Cart
                </button>
            </div>
        </div>
        <?php
            endwhile;
        else:
            echo "<p>No products available.</p>";
        endif;
        ?>
    </div>
</div>

<script>
document.querySelectorAll('.add-to-cart').forEach(btn => {
    btn.addEventListener('click', function() {
        const productId = this.dataset.id;

        fetch('<?php echo BASE_URL; ?>cart_action.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
    body: 'action=add&id=' + encodeURIComponent(productId)
})
.then(res => res.text())  // 👈 first get raw text
.then(text => {
    console.log('Raw response:', text); // see if it’s HTML or JSON
    return JSON.parse(text); // try to parse manually
})
.then(data => {
    if (data.success) {
        alert('Product added to cart! Total items in cart: ' + data.cart_total);
    } else {
        alert('Failed to add product to cart.');
    }
})
.catch(err => {
    console.error(err);
    alert('Error adding to cart.');
});

    });
});
</script>

<?php include __DIR__ . "/../includes/footer.php"; ?>
</body>
</html>
