<?php
session_start();
include "../db.php";

$error = "";
$success = "";

if (isset($_GET["logged_out"])) {
    $success = "Logout successful.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    if (empty($username)) {
        $error = "Username is required";
    } elseif (empty($password)) {
        $error = "Password is required";
    } else {
        $sql = "SELECT * FROM admins WHERE Username = '$username'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);

            if ($password == $row["PasswordHash"]) {
                $_SESSION["admin"] = $row["Username"];
                $_SESSION["admin_id"] = $row["AdminID"];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Wrong password";
            }
        } else {
            $error = "Admin not found";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin-style.css">
</head>
<body>

<div class="login-box">
    <h1>Admin Login</h1>

    <?php if (!empty($success)) { ?>
        <p style="color: green;"><?php echo $success; ?></p>
    <?php } ?>

    <?php if (!empty($error)) { ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php } ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Enter username" required>
        <br><br>
        <input type="password" name="password" placeholder="Enter password" required>
        <br><br>
        <button type="submit">Login</button>
    </form>
</div>

</body>
</html>