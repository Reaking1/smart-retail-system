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
    $image_url = $_POST['image_url'];

    if (isset($_POST['product_id']) && $_POST['product_id'] != "") {
        // Edit product
        $id = intval($_POST['product_id']);
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, stock_quantity=?, image_url=? WHERE product_id=?");
        $stmt->bind_param("ssdisi", $name, $description, $price, $stock, $image_url, $id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Add new product
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock_quantity, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdis", $name, $description, $price, $stock, $image_url);
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
        body { font-family: Arial, sans-serif; background: #f4f4f9; }
        .container { width: 95%; margin: 30px auto; }
        h1 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border: 1px solid #ddd; text-align: left; vertical-align: middle; }
        th { background: #6f42c1; color: white; }
        tr:nth-child(even) { background: #f9f9f9; }
        form { margin-top: 20px; background: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        label { font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin: 5px 0 15px 0; border: 1px solid #ccc; border-radius: 5px; }
        button { padding: 10px 15px; margin-top: 10px; background: #6f42c1; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #563d7c; }
        a.action { text-decoration: none; padding: 5px 10px; border-radius: 5px; color: white; }
        a.edit { background: #28a745; }
        a.delete { background: #dc3545; }
        img.product-thumb { width: 60px; height: 60px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
<?php include("../includes/header.php"); ?>

<div class="container">
    <h1>Manage Products</h1>

    <h2>Add / Edit Product</h2>
    <form method="POST" action="">
        <input type="hidden" name="product_id" id="product_id">
        
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required>
        
        <label for="description">Description</label>
        <textarea name="description" id="description" required></textarea>
        
        <label for="price">Price</label>
        <input type="number" step="0.01" name="price" id="price" required>
        
        <label for="stock">Stock Quantity</label>
        <input type="number" name="stock" id="stock" required>
        
        <label for="image_url">Image Path:</label>
        <input type="text" name="image_url" id="image_url" 
               placeholder="assets/images/products/sample.png" required>

        <button type="submit">Save Product</button>
    </form>

    <h2>Product List</h2>
    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['product_id']; ?></td>
                    <td>
                        <?php if (!empty($row['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" 
                                 alt="<?php echo htmlspecialchars($row['name']); ?>" 
                                 class="product-thumb">
                        <?php else: ?>
                            <span>No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>R <?php echo number_format($row['price'], 2); ?></td>
                    <td><?php echo $row['stock_quantity']; ?></td>
                    <td>
                        <a href="#" class="action edit"
                           onclick="editProduct(
                               '<?php echo $row['product_id']; ?>',
                               '<?php echo addslashes($row['name']); ?>',
                               '<?php echo addslashes($row['description']); ?>',
                               '<?php echo $row['price']; ?>',
                               '<?php echo $row['stock_quantity']; ?>',
                               '<?php echo addslashes($row['image_url']); ?>'
                           )">Edit</a>
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
function editProduct(id, name, description, price, stock, image_url) {
    document.getElementById('product_id').value = id;
    document.getElementById('name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('price').value = price;
    document.getElementById('stock').value = stock;
    document.getElementById('image_url').value = image_url;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>
</body>
</html>
