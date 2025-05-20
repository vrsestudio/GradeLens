<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "gradelens";

$conn = new mysqli($server, $username, $password, $database);

if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}
?>