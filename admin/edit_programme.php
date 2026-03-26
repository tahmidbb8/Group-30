<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_programmes.php");
    exit();
}

$id = (int) $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $programme_name = trim($_POST["programme_name"]);
    $level          = trim($_POST["level"]);
    $leader         = trim($_POST["leader"]);
    $description    = trim($_POST["description"]);

    $stmt = mysqli_prepare($conn,
        "UPDATE programmes 
         SET ProgrammeName = ?, LevelID = ?, ProgrammeLeaderID = ?, Description = ?
         WHERE ProgrammeID = ?"
    );
    mysqli_stmt_bind_param($stmt, "siisi", $programme_name, $level, $leader, $description, $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: manage_programmes.php");
    exit();
}

$stmt = mysqli_prepare($conn, "SELECT * FROM programmes WHERE ProgrammeID = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row    = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    header("Location: manage_programmes.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Programme</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="form-page">
    <div class="form-box">
        <h1>Edit Programme</h1>

        <form method="POST">
            <label for="programme_name">Programme Name:</label>
            <input
                type="text"
                id="programme_name"
                name="programme_name"
                value="<?php echo htmlspecialchars($row['ProgrammeName']); ?>"
                required
            >

            <label for="level">Level ID:</label>
            <input
                type="number"
                id="level"
                name="level"
                value="<?php echo htmlspecialchars($row['LevelID']); ?>"
                required
            >

            <label for="leader">Programme Leader ID:</label>
            <input
                type="number"
                id="leader"
                name="leader"
                value="<?php echo htmlspecialchars($row['ProgrammeLeaderID']); ?>"
                required
            >

            <label for="description">Description:</label>
            <textarea
                id="description"
                name="description"
                rows="4"
            ><?php echo htmlspecialchars($row['Description']); ?></textarea>

            <button type="submit" class="btn update-btn">Update Programme</button>
        </form>

        <p class="back-link">
            <a href="manage_programmes.php">Back to Manage Programmes</a>
        </p>
    </div>
</div>

</body>
</html>