<?php
session_start();

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* If user confirms logout */
if (isset($_POST["confirm_logout"])) {
    session_unset();
    session_destroy();
    header("Location: login.php?logged_out=1");
    exit();
}

/* If user cancels */
if (isset($_POST["cancel"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Logout</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="logout-box">
    <h1>Confirm Logout</h1>
    <p>Are you sure you want to log out?</p>

    <form method="POST" action="">
        <button type="submit" name="confirm_logout" class="delete-btn">Yes, Logout</button>
        <button type="submit" name="cancel" class="back-btn">Cancel</button>
    </form>
</div>

</body>
</html>