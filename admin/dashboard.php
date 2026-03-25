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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="container">
<h1>Admin Dashboard</h1>

<p>Welcome, Admin!</p>

<ul class="dashboard-menu">
    <li><a href="manage_programmes.php">Manage Programmes</a></li>
    <li><a href="manage_modules.php">Manage Modules</a></li>
    <li><a href="interested_students.php">View Interested Students</a></li>
    <li><a href="export_mailing_list.php">Export Mailing List</a></li>
    <li><a href="confirm_logout.php" class="logout">Logout</a></li>
</ul>

</body>
</html>