<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "user_auth";
$port = "port_number";// replace with it your port number if not default 3306

$conn = new mysqli($servername, $username, $password, $database, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
