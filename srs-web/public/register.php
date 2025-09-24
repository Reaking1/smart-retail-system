<?php
session_start();
include __DIR__ . "/../includes/db.php";
include __DIR__ . "/../includes/config.php"; // BASE_URL

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone    = trim($_POST['phone']);
    $address  = trim($_POST['address']);

    // Server-side validation
    if (empty($name) || empty($email) || empty($password) || empty($phone) || empty($address)) {
        $error = "All fields are required.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered.";
        } else {
            // Hash the password before saving
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, address) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashedPassword, $phone, $address);

            if ($stmt->execute()) {
                $success = "Registration successful! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }

            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer Registration</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/styles.css">
</head>
<body>
    <?php include __DIR__ . "/../includes/header.php"; ?>

    <div class="register-container">
        <h1>Register</h1>

        <?php if ($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form id="registerForm" method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="text" name="address" placeholder="Address" required>
            <button type="submit">Register</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('registerForm');

        // Client-side validation
        form.addEventListener('submit', function(e) {
            let errors = [];
            const name = form.name.value.trim();
            const email = form.email.value.trim();
            const password = form.password.value;
            const phone = form.phone.value.trim();
            const address = form.address.value.trim();

            if(name.length < 2) errors.push("Name must be at least 2 characters");
            if(!email.includes("@")) errors.push("Email is invalid");
            if(password.length < 6) errors.push("Password must be at least 6 characters");
            if(!/^\d{10}$/.test(phone)) errors.push("Phone must be 10 digits");
            if(address.length === 0) errors.push("Address is required");

            if(errors.length > 0) {
                e.preventDefault();
                alert(errors.join("\n"));
            }
        });

        // Check email availability dynamically
        form.email.addEventListener('blur', function() {
            const email = this.value.trim();
            if(email.length > 0) {
                fetch('<?php echo BASE_URL; ?>api/check_email.php?email=' + encodeURIComponent(email))
                    .then(res => res.json())
                    .then(data => {
                        if(data.exists) {
                            alert("Email already registered!");
                        }
                    });
            }
        });
    </script>

</body>
</html>
