<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];

    $sql = "DELETE FROM InterestedStudents WHERE InterestID = $id";
    mysqli_query($conn, $sql);
}

header("Location: interested_students.php");
exit();
?>