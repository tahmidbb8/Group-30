<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="mailing_list.csv"');

$output = fopen("php://output", "w");

fputcsv($output, array("Student Name", "Email", "Programme"));

$sql = "SELECT InterestedStudents.StudentName, InterestedStudents.Email, Programmes.ProgrammeName
        FROM InterestedStudents
        JOIN Programmes ON InterestedStudents.ProgrammeID = Programmes.ProgrammeID";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>