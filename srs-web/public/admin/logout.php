<?php
session_start();

// Clear all session data
$_SESSION = [];
session_destroy();

// Redirect to admin login page (same folder as this file)
header("Location: login.php");
exit;
