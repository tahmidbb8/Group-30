<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ModuleName = $_POST["ModuleName"];
    $ModuleLeaderID = $_POST["ModuleLeaderID"];
    $Description = $_POST["Description"];
    $Image = $_POST["Image"];

    $sql = "INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Image)
            VALUES ('$ModuleName', '$ModuleLeaderID', '$Description', '$Image')";

    if (mysqli_query($conn, $sql)) {
        $message = "<p style='color:green;'>Module added successfully!</p>";
    } else {
        $message = "<p style='color:red;'>Error adding module.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Modules</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="container">

<h1>Manage Modules</h1>

<?php echo $message; ?>

<h2>Add New Module</h2>

<form method="POST">
    <label>Module Name:</label>
    <input type="text" name="ModuleName" required>

    <label>Module Leader ID:</label>
    <input type="number" name="ModuleLeaderID">

    <label>Description:</label>
    <textarea name="Description"></textarea>

    <label>Image URL:</label>
    <input type="text" name="Image">

    <button type="submit">Add Module</button>
</form>

<h2>All Modules</h2>

<?php
$sql = "SELECT * FROM Modules";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>ModuleID</th>";
    echo "<th>ModuleName</th>";
    echo "<th>ModuleLeaderID</th>";
    echo "<th>Description</th>";
    echo "<th>Image</th>";
    echo "<th>Actions</th>";
    echo "</tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["ModuleID"] . "</td>";
        echo "<td>" . $row["ModuleName"] . "</td>";
        echo "<td>" . $row["ModuleLeaderID"] . "</td>";
        echo "<td>" . $row["Description"] . "</td>";
        echo "<td>" . $row["Image"] . "</td>";
        echo "<td>
                <a href='edit_module.php?id=" . $row["ModuleID"] . "'>Edit</a> |
                <a href='delete_module.php?id=" . $row["ModuleID"] . "' onclick=\"return confirm('Delete this module?');\">Delete</a>
              </td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p>No modules found.</p>";
}
?>

<br>
<a href="dashboard.php">Back to Dashboard</a>

</div>

</body>
</html>