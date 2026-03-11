<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST["username"];
    $password = $_POST["password"];

    if (empty($username)) {
        echo "Username is required<br>";
    }

    if (empty($password)) {
        echo "Password is required<br>";
    }

}

?>

<h1>Admin Login</h1>

<form method="POST">

<input type="text" name="username" placeholder="Enter username"><br>
<input type="password" name="password" placeholder="Enter password"><br>

<button type="submit">Login</button>

</form>