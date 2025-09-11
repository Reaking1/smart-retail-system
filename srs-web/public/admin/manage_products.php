<?php
session_start();
include("../includes/db.php");
include("../includes/auth.php"); // Protect page

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM products WHERE product_id = $id");
    header("Location: manage_products.php");
    exit;
}

// Handle add/edit form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);

    if (isset($_POST['product_id']) && $_POST['product_id'] != "") {
        // Edit product
        $id = intval($_POST['product_id']);
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=? WHERE product_id=?");
        $stmt->bind_param("ssdii", $name, $description, $price, $stock, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Add new product
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $stock);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_products.php");
    exit;
}

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY product_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .container { width: 95%; margin: 30px auto; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        tr:nth-child(even) { background: #f2f2f2; }
        form { margin-top: 20px; background: #f9f9f9; padding: 20px; border-radius: 10px; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0; }
        button { padding: 10px 15px; margin-top: 10px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #0056b3; }
        a.action { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        a.edit { background: #28a745; }
        a.delete { background: #dc3545; }
    </style>
</head>
<body>
<?php include("../includes/header.php"); ?>

<div class="container">
    <h1>Manage Products</h1>

    <h2>Add / Edit Product</h2>
    <form method="POST" action="">
        <input type="hidden" name="product_id" id="product_id">
        <label>Name</label>
        <input type="text" name="name" id="name" required>
        <label>Description</label>
        <textarea name="description" id="description" required></textarea>
        <label>Price</label>
        <input type="number" step="0.01" name="price" id="price" required>
        <label>Stock Quantity</label>
        <input type="number" name="stock" id="stock" required>
        <button type="submit">Save Product</button>
    </form>

    <h2>Product List</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>$<?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td>
                        <a href="#" class="action edit" onclick="editProduct('<?php echo $row['product_id']; ?>', '<?php echo addslashes($row['name']); ?>', '<?php echo addslashes($row['description']); ?>', '<?php echo $row['price']; ?>', '<?php echo $row['stock_quantity']; ?>')">Edit</a>
                        <a href="manage_products.php?delete=<?php echo $row['product_id']; ?>" class="action delete" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>

<script>
function editProduct(id, name, description, price, stock) {
    document.getElementById('product_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('price').value = price;
    document.getElementById('stock').value = stock;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body>
</html>
