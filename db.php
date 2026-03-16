<?php

$host = "localhost"; // database server
$username = "root"; // database username
$password = ""; // database password
$database = "student_course_hub"; // database name

$conn = mysqli_connect($host, $username, $password, $database); // create database connection

if (!$conn) { // check if connection failed
    die("Connection failed: " . mysqli_connect_error()); // show error and stop execution
}

?>