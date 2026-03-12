<?php
session_start();

// if admin session does not exist, send user back to login page
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}
?>

<h1>Manage Programmes</h1>
<p>This page will be used to manage programmes.</p>

<p><a href="dashboard.php">Back to Dashboard</a></p>