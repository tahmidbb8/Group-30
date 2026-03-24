<?php
session_start();
include('../db.php');

if (!isset($_SESSION['staff_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['staff_id'];

$stmt = $conn->prepare("SELECT ModuleName FROM Modules WHERE ModuleLeaderID = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Modules</title>
<link rel="stylesheet" href="style.css">
</head>

<body>

<div class="wrapper">

<!-- Sidebar -->
<div class="sidebar">
    <h2>Staff Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="my_modules.php">My Modules</a>
    <a href="my_programmes.php">My Programmes</a>
    <a href="profile.php">Profile</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main -->
<div class="main">

<div class="card">
    <h2>My Modules</h2>
</div>

<div class="grid">

<?php
while ($row = $result->fetch_assoc()) {
    echo "
    <div class='dashboard-card'>
        <h3>{$row['ModuleName']}</h3>
        <p>Module you are leading</p>
    </div>
    ";
}
?>

</div>

</div>

</div>

</body>
</html>