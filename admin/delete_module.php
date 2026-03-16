<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id = $_GET["id"];

    // First delete linked rows from ProgrammeModules
    $sql1 = "DELETE FROM ProgrammeModules WHERE ModuleID = $id";
    mysqli_query($conn, $sql1);

    // Then delete the module itself
    $sql2 = "DELETE FROM Modules WHERE ModuleID = $id";
    mysqli_query($conn, $sql2);
}

header("Location: manage_modules.php");
exit();
?>