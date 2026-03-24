<?php
require_once __DIR__ . '/../functions.php';
require_admin();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="mailing_list.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['Programme', 'Student Name', 'Email', 'Registered At']);

$sql = 'SELECT p.ProgrammeName, i.StudentName, i.Email, i.RegisteredAt
        FROM InterestedStudents i
        JOIN Programmes p ON i.ProgrammeID = p.ProgrammeID
        WHERE i.IsActive = 1
        ORDER BY p.ProgrammeName, i.StudentName';
$result = get_db()->query($sql);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
