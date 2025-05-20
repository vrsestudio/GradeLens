<?php
session_start();

echo "LIBRARY VIEW PANEL <br>";

$mysqli = new mysqli("localhost", "root", "", "gradelens");
if ($mysqli->connect_error) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}

$uID = $_SESSION['uID'] ?? null;

if ($uID && is_numeric($uID)) {
    if ($uID == 1) {
        echo '<img src=https://spielwaren-investor.com/wp-content/uploads/2020/02/star-wars-yoda.jpg>';
    } elseif ($uID == 2) {
            echo '<img src=https://upload.wikimedia.org/wikipedia/commons/9/9c/Darth_Vader_-_2007_Disney_Weekends.jpg>';
    } else {
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
    }
} else {
    echo "Nicht eingeloggt oder ungültige uID.";
}

$mysqli->close();
?>