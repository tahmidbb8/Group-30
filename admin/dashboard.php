<?php
session_start();

// if admin session does not exist, send user back to login page
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}
?>

<h1>Admin Dashboard</h1>

<ul>
    <li><a href="manage_programmes.php">Manage Programmes</a></li>
    <li><a href="manage_modules.php">Manage Modules</a></li>
    <li><a href="interested_students.php">View Interested Students</a></li>
    <li><a href="export_mailing_list.php">Export Mailing List</a></li>
    </ul><a href="interested_students.php">View Interested Students</a><br><br>
    <a href="export_mailing_list.php">Export Mailing List</a><br><br>