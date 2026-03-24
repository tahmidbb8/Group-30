<?php
session_start();
include('../db.php');

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['staff_id'];

/* ---------------- USERNAME UPDATE ---------------- */
if (isset($_POST['update_username'])) {

    $newUsername = $_POST['username'];
    $oldPassword = $_POST['old_password'];

    $stmt = $conn->prepare("SELECT Password FROM Staff WHERE StaffID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!password_verify($oldPassword, $data['Password'])) {
        $error = "Old password is incorrect!";
    } else {
        $stmt = $conn->prepare("UPDATE Staff SET Username = ? WHERE StaffID = ?");
        $stmt->bind_param("si", $newUsername, $id);

        if ($stmt->execute()) {
            $success = "Username updated!";
        } else {
            $error = "Update failed!";
        }
    }
}

/* ---------------- PASSWORD UPDATE ---------------- */
if (isset($_POST['update_password'])) {

    $oldPassword = $_POST['old_password_pw'];
    $newPassword = $_POST['new_password'];

    $stmt = $conn->prepare("SELECT Password FROM Staff WHERE StaffID = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if (!password_verify($oldPassword, $data['Password'])) {
        $error = "Old password is incorrect!";
    } else {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE Staff SET Password = ? WHERE StaffID = ?");
        $stmt->bind_param("si", $hashedPassword, $id);

        if ($stmt->execute()) {
            $success = "Password updated!";
        } else {
            $error = "Update failed!";
        }
    }
}

/* Get username */
$stmt = $conn->prepare("SELECT Username FROM Staff WHERE StaffID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
<title>Profile</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="wrapper">

<div class="sidebar">
    <h2>Staff Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="my_modules.php">My Modules</a>
    <a href="my_programmes.php">My Programmes</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">

<div class="card">
<h2>Profile Settings</h2>
</div>

<?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
<?php if (isset($success)) echo "<div class='success'>$success</div>"; ?>

<!-- USERNAME -->
<div class="card">
<h3>Change Username</h3>

<form method="POST">
<input type="text" name="username" placeholder="Enter new username" required>

<input type="password" name="old_password" placeholder="Enter password" required>

<button type="submit" name="update_username" class="btn">Update Username</button>
</form>
</div>

<!-- PASSWORD -->
<div class="card">
<h3>Change Password</h3>

<form method="POST">
<input type="password" name="old_password_pw" placeholder="Old Password" required>

<input type="password" name="new_password" placeholder="New Password" required>

<button type="submit" name="update_password" class="btn">Update Password</button>
</form>
</div>

</div>

</div>

</body>
</html>