<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT InterestedStudents.StudentName, InterestedStudents.Email, Programmes.ProgrammeName
        FROM InterestedStudents
        JOIN Programmes ON InterestedStudents.ProgrammeID = Programmes.ProgrammeID";

$result = mysqli_query($conn, $sql);
?>

<h1>Interested Students</h1>

<?php
if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>";
    echo "<tr>";
    echo "<th>Student Name</th>";
    echo "<th>Email</th>";
    echo "<th>Programme</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["StudentName"] . "</td>";
        echo "<td>" . $row["Email"] . "</td>";
        echo "<td>" . $row["ProgrammeName"] . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No interested students found.</p>";
}
?>

<br>
<a href="dashboard.php">Back to Dashboard</a>