<?php
session_start();
include "../db.php";

if (!isset($_SESSION["admin"])) {
    header("Location: login.php");
    exit();
}

$id = (int) $_GET["id"];

$sql = "SELECT * FROM Modules WHERE ModuleID = $id";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ModuleName = trim($_POST["ModuleName"] ?? "");
    $ModuleLeaderID = trim($_POST["ModuleLeaderID"] ?? "");
    $Description = trim($_POST["Description"] ?? "");
    $Image = trim($_POST["Image"] ?? "");

    $update = "UPDATE Modules 
               SET ModuleName = '$ModuleName',
                   ModuleLeaderID = '$ModuleLeaderID',
                   Description = '$Description',
                   Image = '$Image'
               WHERE ModuleID = $id";

    mysqli_query($conn, $update);

    header("Location: manage_modules.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Module</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>

<div class="container">
    <h1>Edit Module</h1>

    <form method="POST">

        <label>Module Name</label>
        <input type="text" name="ModuleName" value="<?php echo $row['ModuleName']; ?>" required>

        <label>Module Leader</label>
        <select name="ModuleLeaderID" required>
            <option value="">Select Module Leader</option>

            <?php
            $staffQuery = "SELECT * FROM Staff";
            $staffResult = mysqli_query($conn, $staffQuery);

            if ($staffResult && mysqli_num_rows($staffResult) > 0) {
                while ($staff = mysqli_fetch_assoc($staffResult)) {
                    $selected = ($staff["StaffID"] == $row["ModuleLeaderID"]) ? "selected" : "";
                    echo "<option value='" . $staff["StaffID"] . "' $selected>" . $staff["Name"] . "</option>";
                }
            }
            ?>
        </select>

        <label>Description</label>
        <textarea name="Description"><?php echo $row['Description']; ?></textarea>

        <label>Image URL</label>
        <input type="text" name="Image" value="<?php echo $row['Image']; ?>">

        <button type="submit">Update Module</button>

    </form>

    <br>
    <a href="manage_modules.php">Back to Manage Modules</a>
</div>

</body>
</html>