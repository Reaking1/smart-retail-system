<?php
session_start();
include __DIR__ . "/../includes/db.php";
header('Content-Type: application/json');

// Initialize cart
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

// Get POST data
$action = $_POST['action'] ?? '';
$id     = intval($_POST['id'] ?? 0);

$response = ['success' => false, 'cart_total' => 0, 'grand_total' => 0];

if ($id > 0) {
    if ($action === 'add') {
        if (!isset($_SESSION['cart'][$id])) $_SESSION['cart'][$id] = 1;
        else $_SESSION['cart'][$id]++;
    } elseif ($action === 'remove') {
        unset($_SESSION['cart'][$id]);
    } elseif ($action === 'update') {
        $qty = max(1, intval($_POST['qty'] ?? 1));
        $_SESSION['cart'][$id] = $qty;
    }

    // Calculate totals
    $grand_total = 0;
    $cart_total = 0;
    foreach ($_SESSION['cart'] as $pid => $qty) {
        $result = $conn->query("SELECT price FROM products WHERE product_id = $pid");
        if ($result && $row = $result->fetch_assoc()) {
            $grand_total += $row['price'] * $qty;
            $cart_total += $qty;
        }
    }

    $response = [
        'success' => true,
        'cart_total' => $cart_total,
        'grand_total' => number_format($grand_total, 2)
    ];
}

echo json_encode($response);
