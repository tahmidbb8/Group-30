<?php
include "db.php";

$sql = "SELECT Programmes.*, Levels.LevelName
        FROM Programmes
        JOIN Levels ON Programmes.LevelID = Levels.LevelID
        WHERE Programmes.is_published = 1";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">
    <h1>Student Course Hub</h1>
    <p>Browse our available degree programmes below.</p>

    <?php
    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>Programme Name</th>";
        echo "<th>Level</th>";
        echo "<th>Description</th>";
        echo "<th>Action</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["ProgrammeName"] . "</td>";
            echo "<td>" . $row["LevelName"] . "</td>";
            echo "<td>" . $row["Description"] . "</td>";
            echo "<td><a class='btn' href='programme_details.php?id=" . $row["ProgrammeID"] . "'>View Details</a></td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No published programmes found.</p>";
    }
    ?>
</div>

</body>
</html>