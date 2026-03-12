<?php
session_start();
include "../db.php";

// if admin session does not exist, send user back to login page
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    $sql = "DELETE FROM programmes WHERE ProgrammeID = '$id'";
    mysqli_query($conn, $sql);
}

header("Location: manage_programmes.php");
exit();
?>