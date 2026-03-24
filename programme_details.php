<?php
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $StudentName = $_POST["StudentName"];
    $Email = $_POST["Email"];
    $ProgrammeID = $_GET["id"];

    $sqlInsert = "INSERT INTO InterestedStudents (ProgrammeID, StudentName, Email)
                  VALUES ('$ProgrammeID', '$StudentName', '$Email')";

    if (mysqli_query($conn, $sqlInsert)) {
        $message = "<p class='success-msg'>Interest registered successfully!</p>";
    } else {
        $message = "<p class='error-msg'>Error registering interest.</p>";
    }
}

if (!isset($_GET["id"])) {
    echo "No programme selected.";
    exit();
}

$ProgrammeID = $_GET["id"];

/* Get programme details */
$sql = "SELECT Programmes.*, Levels.LevelName
        FROM Programmes
        JOIN Levels ON Programmes.LevelID = Levels.LevelID
        WHERE Programmes.ProgrammeID = $ProgrammeID";

$result = mysqli_query($conn, $sql);
$programme = mysqli_fetch_assoc($result);

/* Get modules for this programme */
$sqlModules = "SELECT Modules.ModuleName, ProgrammeModules.Year
               FROM ProgrammeModules
               JOIN Modules ON ProgrammeModules.ModuleID = Modules.ModuleID
               WHERE ProgrammeModules.ProgrammeID = $ProgrammeID";

$modulesResult = mysqli_query($conn, $sqlModules);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Programme Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<?php
if ($programme) {
    echo "<h1>" . $programme["ProgrammeName"] . "</h1>";
    echo "<p><strong>Level:</strong> " . $programme["LevelName"] . "</p>";
    echo "<p>" . $programme["Description"] . "</p>";

    echo "<h2>Modules</h2>";

    if ($modulesResult && mysqli_num_rows($modulesResult) > 0) {
        echo "<table>";
        echo "<tr><th>Module Name</th><th>Year</th></tr>";

        while ($row = mysqli_fetch_assoc($modulesResult)) {
            echo "<tr>";
            echo "<td>" . $row["ModuleName"] . "</td>";
            echo "<td>" . $row["Year"] . "</td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No modules assigned to this programme.</p>";
    }
} else {
    echo "<p>Programme not found.</p>";
}
?>

<h2>Register Interest</h2>

<?php echo $message; ?>

<form method="POST">
    <input type="text" name="StudentName" placeholder="Your Name" required>
    <input type="email" name="Email" placeholder="Your Email" required>
    <button type="submit" class="btn">Register</button>
</form>

<br>
<a href="index.php" class="btn">Back</a>

</div>

</body>
</html>