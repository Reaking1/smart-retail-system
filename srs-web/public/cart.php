<?php
session_start();
include("includes/db.php");

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add product to cart
if (isset($_GET['add'])) {
    $product_id = intval($_GET['add']);
    if (!isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] = 1; // first time adding
    } else {
        $_SESSION['cart'][$product_id]++; // increase qty
    }
    header("Location: cart.php");
    exit;
}

// Remove product
if (isset($_GET['remove'])) {
    $product_id = intval($_GET['remove']);
    unset($_SESSION['cart'][$product_id]);
    header("Location: cart.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include("includes/header.php"); ?>

<div class="container">
    <h1>Your Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty. <a href="products.php">Go shopping</a></p>
    <?php else: ?>
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
            <?php
            $total = 0;
            foreach ($_SESSION['cart'] as $id => $qty) {
                $sql = "SELECT * FROM products WHERE product_id = $id";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $sub_total = $row['price'] * $qty;
                    $total += $sub_total;

                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>R {$row['price']}</td>";
                    echo "<td>$qty</td>";
                    echo "<td>R $sub_total</td>";
                    echo "<td><a href='cart.php?remove=$id'>Remove</a></td>";
                    echo "</tr>";
                }
            }
            ?>
            <tr>
                <td colspan="3"><strong>Grand Total</strong></td>
                <td colspan="2"><strong>R <?php echo $total; ?></strong></td>
            </tr>
        </table>

        <br>
        <a href="checkout.php"><button>Proceed to Checkout</button></a>
    <?php endif; ?>
</div>
</body>
</html>
