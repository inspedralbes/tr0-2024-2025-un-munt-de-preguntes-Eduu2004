<?php

$servername = "localhost:8083";
$username = "a20edurenlop_bd"; // Your database username
$password = "Ausias123";     // Your database password
$dbname = "a20edurenlop_peli"; // Your database name

//$servername = "localhost";
//$username = "phpmyadmin"; // Your database username
//$password = "usuario";     // Your database password
//$dbname = "peli"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>