<?php
session_start();

echo "DEFAULT VIEW PANEL <br>";

include '../../api/connectdatabase.php';

$uID = $_SESSION['uID'] ?? null;

if ($uID && is_numeric($uID)) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE uID = ?");
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

include '../../api/disconnectdatabase.php';