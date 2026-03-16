<?php
session_start();

include "../db.php";

// if admin session does not exist, send user back to login page
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

// check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

   $programme_name = $_POST["programme_name"];
$level_id = $_POST["level"];
$leader_id = $_POST["leader"];

$sql = "INSERT INTO programmes (ProgrammeName, LevelID, ProgrammeLeaderID)
        VALUES ('$programme_name', '$level_id', '$leader_id')";

    mysqli_query($conn, $sql);

    echo "Programme added successfully!";
}
?>

<h1>Manage Programmes</h1>

<h2>Add New Programme</h2>

<form method="POST">

<label>Programme Name:</label>
<input type="text" name="programme_name"><br><br>

<label>Level:</label>
<input type="text" name="level"><br><br>

<label>Programme Leader:</label>
<input type="text" name="leader"><br><br>

<button type="submit">Add Programme</button>

</form>

<hr>

<h2>All Programmes</h2>

<table border="1" cellpadding="10">

<tr>
<th>ID</th>
<th>Name</th>
<th>Level</th>
<th>Leader</th>
<th>Description</th>
<th>Image</th>
<th>Actions</th>
</tr>

<?php
$sql = "SELECT * FROM programmes";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {

echo "<tr>";
echo "<td>".$row["ProgrammeID"]."</td>";
echo "<td>".$row["ProgrammeName"]."</td>";
echo "<td>".$row["LevelID"]."</td>";
echo "<td>".$row["ProgrammeLeaderID"]."</td>";
echo "<td>".$row["Description"]."</td>";
echo "<td>".$row["Image"]."</td>";
echo "<td><a href='edit_programme.php?id=".$row["ProgrammeID"]."'>Edit</a> | <a href='delete_programme.php?id=".$row["ProgrammeID"]."'>Delete</a></td>";
echo "</tr>";

}
?>

</table>

<br>

<p><a href="dashboard.php">Back to Dashboard</a></p>