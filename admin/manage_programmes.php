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

    $stmt = mysqli_prepare($conn,
        "UPDATE programmes 
         SET is_published = CASE WHEN is_published = 1 THEN 0 ELSE 1 END 
         WHERE ProgrammeID = ?"
    );
    mysqli_stmt_bind_param($stmt, "i", $ProgrammeID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: manage_programmes.php");
    exit();
}

/* -------- Add New Programme -------- */
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ProgrammeName        = trim($_POST["ProgrammeName"] ?? "");
    $LevelID              = trim($_POST["LevelID"] ?? "");
    $ProgrammeLeaderID    = trim($_POST["ProgrammeLeaderID"] ?? "");
    $Description          = trim($_POST["Description"] ?? "");
    $Image                = trim($_POST["Image"] ?? "");

    $stmt = mysqli_prepare($conn,
        "INSERT INTO programmes 
         (ProgrammeName, LevelID, ProgrammeLeaderID, Description, Image, is_published)
         VALUES (?, ?, ?, ?, ?, 1)"
    );
    mysqli_stmt_bind_param($stmt, "siiss", $ProgrammeName, $LevelID, $ProgrammeLeaderID, $Description, $Image);

    if (mysqli_stmt_execute($stmt)) {
        $message = "<p style='color:green;'>Programme added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error adding programme: " . mysqli_stmt_error($stmt) . "</p>";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Programmes</title>
    <link rel="stylesheet" href="admin-style.css">
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
    $result = mysqli_query($conn, "SELECT * FROM programmes");

    if ($result && mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>
                <th>ProgrammeID</th>
                <th>ProgrammeName</th>
                <th>LevelID</th>
                <th>ProgrammeLeaderID</th>
                <th>Description</th>
                <th>Image</th>
                <th>Status</th>
                <th>Toggle</th>
                <th>Actions</th>
              </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
            $isPublished = $row["is_published"] ?? 0;
            $id          = (int) $row["ProgrammeID"];

            $status      = $isPublished
                ? "<span class='status-published'>Published</span>"
                : "<span class='status-hidden'>Hidden</span>";

            $toggleText  = $isPublished ? "Unpublish" : "Publish";

            echo "<tr>
                    <td>" . htmlspecialchars($row["ProgrammeID"])        . "</td>
                    <td>" . htmlspecialchars($row["ProgrammeName"])       . "</td>
                    <td>" . htmlspecialchars($row["LevelID"])             . "</td>
                    <td>" . htmlspecialchars($row["ProgrammeLeaderID"])   . "</td>
                    <td>" . htmlspecialchars($row["Description"])         . "</td>
                    <td>" . htmlspecialchars($row["Image"])               . "</td>
                    <td>" . $status                                       . "</td>
                    <td>
                        <a href='manage_programmes.php?toggle={$id}'>{$toggleText}</a>
                    </td>
                    <td>
                        <a href='edit_programme.php?id={$id}'>Edit</a> |
                        <a href='delete_programme.php?id={$id}'
                           onclick=\"return confirm('Are you sure you want to delete this programme?');\">
                           Delete
                        </a>
                    </td>
                  </tr>";
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