<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

/* -------- Toggle Published / Hidden -------- */
if (isset($_GET["toggle"])) {
    $ProgrammeID = (int) $_GET["toggle"];

    $sql = "UPDATE programmes
            SET is_published = NOT is_published
            WHERE ProgrammeID = $ProgrammeID";

    mysqli_query($conn, $sql);

    header("Location: manage_programmes.php");
    exit();
}

/* -------- Add New Programme -------- */
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ProgrammeName = trim($_POST["ProgrammeName"] ?? "");
    $LevelID = trim($_POST["LevelID"] ?? "");
    $ProgrammeLeaderID = trim($_POST["ProgrammeLeaderID"] ?? "");
    $Description = trim($_POST["Description"] ?? "");
    $Image = trim($_POST["Image"] ?? "");

    $sql = "INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, is_published)
            VALUES ('$ProgrammeName', '$LevelID', '$ProgrammeLeaderID', '$Description', '$Image', 1)";

    if (mysqli_query($conn, $sql)) {
        $message = "<p style='color:green;'>Programme added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error adding programme.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programmes</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Manage Programmes</h1>

    <?php echo $message; ?>

    <h2>Add New Programme</h2>

    <form method="POST">
        <label>Programme Name:</label>
        <input type="text" name="ProgrammeName" required>

        <label>Level ID:</label>
        <input type="number" name="LevelID" required>

        <label>Programme Leader ID:</label>
        <input type="number" name="ProgrammeLeaderID" required>

        <label>Description:</label>
        <textarea name="Description"></textarea>

        <label>Image URL:</label>
        <input type="text" name="Image">

        <button type="submit">Add Programme</button>
    </form>

    <h2>All Programmes</h2>

    <?php
    $sql = "SELECT * FROM programmes";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>";
        echo "<th>ProgrammeID</th>";
        echo "<th>ProgrammeName</th>";
        echo "<th>LevelID</th>";
        echo "<th>ProgrammeLeaderID</th>";
        echo "<th>Description</th>";
        echo "<th>Image</th>";
        echo "<th>Status</th>";
        echo "<th>Toggle</th>";
        echo "<th>Actions</th>";
        echo "</tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            $isPublished = $row["is_published"] ?? 0;
            $status = $isPublished ? "<span class='status-published'>Published</span>" : "<span class='status-hidden'>Hidden</span>";
            $toggleText = $isPublished ? "Unpublish" : "Publish";

            echo "<tr>";
            echo "<td>" . $row["ProgrammeID"] . "</td>";
            echo "<td>" . $row["ProgrammeName"] . "</td>";
            echo "<td>" . $row["LevelID"] . "</td>";
            echo "<td>" . $row["ProgrammeLeaderID"] . "</td>";
            echo "<td>" . $row["Description"] . "</td>";
            echo "<td>" . $row["Image"] . "</td>";
            echo "<td>" . $status . "</td>";
            echo "<td><a href='manage_programmes.php?toggle=" . $row["ProgrammeID"] . "'>" . $toggleText . "</a></td>";
            echo "<td>
                    <a href='edit_programme.php?id=" . $row["ProgrammeID"] . "'>Edit</a> |
                    <a href='delete_programme.php?id=" . $row["ProgrammeID"] . "' onclick=\"return confirm('Are you sure you want to delete this programme?');\">Delete</a>
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No programmes found.</p>";
    }
    ?>

    <br>
    <a href="dashboard.php">Back to Dashboard</a>
</div>

</body>
</html>