<?php
session_start();
include "../db.php";

// if admin session does not exist, send user back to login page
if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: manage_programmes.php");
    exit();
}

$id = $_GET["id"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $programme_name = $_POST["programme_name"];
    $level = $_POST["level"];
    $leader = $_POST["leader"];

    $sql = "UPDATE programmes 
            SET ProgrammeName='$programme_name', LevelID='$level', ProgrammeLeaderID='$leader'
            WHERE ProgrammeID='$id'";

    mysqli_query($conn, $sql);

    header("Location: manage_programmes.php");
    exit();
}

$sql = "SELECT * FROM programmes WHERE ProgrammeID = '$id'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
?>

<h1>Edit Programme</h1>

<form method="POST">

<label>Programme Name:</label>
<input type="text" name="programme_name" value="<?php echo $row['ProgrammeName']; ?>"><br><br>

<label>Level ID:</label>
<input type="text" name="level" value="<?php echo $row['LevelID']; ?>"><br><br>

<label>Programme Leader ID:</label>
<input type="text" name="leader" value="<?php echo $row['ProgrammeLeaderID']; ?>"><br><br>

<button type="submit">Update Programme</button>

</form>

<p><a href="manage_programmes.php">Back to Manage Programmes</a></p>