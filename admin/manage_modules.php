<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ModuleName = $_POST["ModuleName"];
    $ModuleLeaderID = $_POST["ModuleLeaderID"];
    $Description = $_POST["Description"];
    $Image = $_POST["Image"];

    $sql = "INSERT INTO Modules (ModuleName, ModuleLeaderID, Description, Image)
            VALUES ('$ModuleName', '$ModuleLeaderID', '$Description', '$Image')";

    mysqli_query($conn, $sql);

    echo "<p style='color:green;'>Module added successfully!</p>";
}
?>

<h1>Manage Modules</h1>

<h2>Add New Module</h2>

<form method="POST">
    <label>Module Name:</label><br>
    <input type="text" name="ModuleName" required><br><br>

    <label>Module Leader ID:</label><br>
    <input type="number" name="ModuleLeaderID"><br><br>

    <label>Description:</label><br>
    <textarea name="Description"></textarea><br><br>

    <label>Image URL:</label><br>
    <input type="text" name="Image"><br><br>

    <button type="submit">Add Module</button>
</form>

<br>

<h2>All Modules</h2>

<?php
$sql = "SELECT * FROM Modules";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table border='1' cellpadding='10'>";
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
        <a href='delete_module.php?id=" . $row["ModuleID"] . "'>Delete</a>
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