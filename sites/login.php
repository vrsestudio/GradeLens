<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenticate</title>
    <link rel="stylesheet" href="../style/login.css">
    <script src="../script/dynamicauth.js" defer></script>
    <script src="../script/disclaimer.js" defer></script>
</head>
<body id="authbody">

    <button id="authdisclaimer">i</button>
    <section id="authcontainer">

        <section id="authtop">
            <section id="authnavi">
                <button id="loginbutton">LOGIN</button>
                <p id="buttonseperator">/</p>
                <button id="signupbutton">SIGNUP</button>
            </section>
            <section id="authlogocontainer">
                <img src="../source/project/logo_gradelens_dark_transparent.webp" alt="logo" id="authlogo">
            </section>
        </section>

        <section id="bottom">
            <section id="loginbody">
                <div id="textbox-login"><p id="welcometext-login">Welcome back to</p><p id="titletext-login">GradeLens</p></div>

                <form id="login-form" method="post" action="">
                    <section id="emailcontainer-login">
                        <input type="email" name="email" id="email-login" placeholder="example@gradelens.com">
                    </section>
                    <section id="passwordcontainer-login">
                        <input type="password" name="password" id="password-login" placeholder="Password">
                    </section>
                    <section id="checkcontainer-login">
                        <button type="submit" id="checkbutton-login">Log In</button>
                    </section>
                </form>
            </section
        </section>
    </section>
</body>
</html>

<?php
include '../api/endsession.php';
$hostname = "localhost";
$username = "root";
$password = "";
$database = "gradelens";
// Verbindung zur Datenbank herstellen
$conn = new mysqli($hostname, $username, $password, $database);
// Verbindung prüfen
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // E-Mail hashen
    $hashed_email = hash('sha256', $email);

    // Überprüfen, ob E-Mail und Passwort übereinstimmen
    $stmt = $conn->prepare("SELECT uID, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $hashed_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uID, $stored_password);
        $stmt->fetch();

        // Passwort überprüfen
        if (password_verify($password, $stored_password)) {
            // Session starten und uID speichern
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['uID'] = $uID;

            // Weiterleitung zur overview.php
            header("Location: overview.php");
            exit();
        } else {
            // invalid password
        }
    } else {
        //email not found
    }

    $stmt->close();
}

// Verbindung schließen
$conn->close();
?>
