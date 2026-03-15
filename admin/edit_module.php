<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$id = $_GET["id"];

$sql = "SELECT * FROM Modules WHERE ModuleID = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ModuleName = $_POST["ModuleName"];
    $ModuleLeaderID = $_POST["ModuleLeaderID"];
    $Description = $_POST["Description"];
    $Image = $_POST["Image"];

    $update = "UPDATE Modules 
               SET ModuleName='$ModuleName',
                   ModuleLeaderID='$ModuleLeaderID',
                   Description='$Description',
                   Image='$Image'
               WHERE ModuleID=$id";

    mysqli_query($conn, $update);

    header("Location: manage_modules.php");
    exit();
}
?>

<h1>Edit Module</h1>

<form method="POST">

<label>Module Name</label><br>
<input type="text" name="ModuleName" value="<?php echo $row['ModuleName']; ?>"><br><br>

<label>Module Leader ID</label><br>
<input type="number" name="ModuleLeaderID" value="<?php echo $row['ModuleLeaderID']; ?>"><br><br>

<label>Description</label><br>
<textarea name="Description"><?php echo $row['Description']; ?></textarea><br><br>

<label>Image URL</label><br>
<input type="text" name="Image" value="<?php echo $row['Image']; ?>"><br><br>

<button type="submit">Update Module</button>

</form>

<br>
<a href="manage_modules.php">Back</a>