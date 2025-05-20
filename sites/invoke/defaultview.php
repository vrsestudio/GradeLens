<?php
session_start();

echo "DEFAULT VIEW PANEL <br>";

$mysqli = new mysqli("127.0.0.1", "gradelens", ")7a3ogunKqsdM8[q", "gradelens");
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$uID = $_SESSION['uID'] ?? null;

if ($uID && is_numeric($uID)) {
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE uID = ?");
        $stmt->bind_param("i", $uID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "ID: " . $row["uID"] . "<br>";
                echo "EMAIL: " . $row["email"] . "<br>";
                echo "PASSWORD: " . $row["password"] . "<br>";
            }
        } else {
            echo "Keine Einträge gefunden.";
        }
        $stmt->close();
} else {
    echo "Nicht eingeloggt oder ungültige uID.";
}

$mysqli->close();