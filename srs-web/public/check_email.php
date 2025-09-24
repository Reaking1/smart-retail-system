<?php
session_start();
include __DIR__ . "/../includes/db.php";
include __DIR__ . "/../includes/config.php"; // BASE_URL if needed

header('Content-Type: application/json');

if (isset($_GET['email'])) {
    $email = trim($_GET['email']);

    // Prepare and execute query
    $stmt = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(['exists' => true]);
    } else {
        echo json_encode(['exists' => false]);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'No email provided']);
}
?>
