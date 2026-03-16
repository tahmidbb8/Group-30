<?php
session_start();
include "../db.php"; // include database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") { // check if form was submitted

    $username = $_POST["username"]; // get username from form
    $password = $_POST["password"]; // get password from form

    if (empty($username)) { // check if username field is empty
        echo "Username is required<br>";
    }

    if (empty($password)) { // check if password field is empty
        echo "Password is required<br>";
    }

    $sql = "SELECT * FROM AdminUsers WHERE username='$username'"; // search admin table
    $result = mysqli_query($conn, $sql); // run query

    if (mysqli_num_rows($result) > 0) { // username exists

        $row = mysqli_fetch_assoc($result); // fetch admin record

        if ($password == $row["password"]) { // compare passwords
            $_SESSION["admin"] = $username; // store admin username in session
            header("Location: dashboard.php"); // redirect to dashboard
            exit();
        } else {
            echo "Wrong password<br>";
        }

    } else {
        echo "Admin not found<br>";
    }

}
?>

<h1>Admin Login</h1>

<form method="POST">
    <input type="text" name="username" placeholder="Enter username"><br>
    <input type="password" name="password" placeholder="Enter password"><br>
    <button type="submit">Login</button>
</form>