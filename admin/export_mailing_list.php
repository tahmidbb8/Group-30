<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* -------- Export CSV -------- */
if (isset($_GET["export"])) {

    header("Content-Type: text/csv");
    header("Content-Disposition: attachment; filename=mailing_list.csv");

    $output = fopen("php://output", "w");

    // Column headers
    fputcsv($output, ["Student Name", "Email", "Programme"]);

    $sql = "SELECT InterestedStudents.StudentName, InterestedStudents.Email, Programmes.ProgrammeName
            FROM InterestedStudents
            JOIN Programmes ON InterestedStudents.ProgrammeID = Programmes.ProgrammeID";

    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Export Mailing List</title>
    <link rel="stylesheet" href="admin-style.css">
</head>

<body>

<div class="export-box">

    <h1>Export Mailing List</h1>

    <p class="export-text">
        Download all students who registered interest as a CSV file.
    </p>

    <a href="export_mailing_list.php?export=true" class="export-btn">Download CSV</a>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>

</div>

</body>
</html>