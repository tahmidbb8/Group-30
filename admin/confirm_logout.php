<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Confirm Logout</h1>
    <p class="confirm-text">Are you sure you want to logout?</p>

    <div class="button-group">
        <a href="logout.php" class="btn logout-btn">Yes, Logout</a>
        <a href="dashboard.php" class="btn cancel-btn">Cancel</a>
    </div>
</div>

</body>
</html>