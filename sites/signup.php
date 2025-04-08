<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet" href="../../style/w/signup.css">
</head>
<body id="body" background="../../source/signup_white_background.webp">
    <section id="signup-container">
        <div id="top">
            <h1 type="button" id="login-button" onclick="window.location.href='./login.php'">LOGIN</h1><h1 id="signup-text">/ SIGNUP</h1>
            <div id="indicator"></div>
            <a href="../d/signup.php" id="logo"><img src="../../source/GradeLens_LOGO_transparent-black-var2.webp" alt="Logo" width="50px" height="50px"></a>
        </div>
        <div id="top-seperator"></div>

        <p id="infotext">To use GradeLens™ and all it functions properly you have to sign up or log in.</p>
        <form id="credentials-container" method="post" action="">
            <label for="email-input">Email</label>
            <input type="email" id="email-input" name="email-input">

            <label for="password-input">Password</label>
            <input type="password" id="password-input" name="password-input">

            <div id="top-seperator"></div>

            <button type="submit" id="submit-button">Create account</button>
        </form>
        <section id="bottom">
            <label id="tos-text">Please be aware that GradeLens™ will store and collect the information that you put in. It will also process given data to store it quick and efficiently in our database. However we are currently not selling that data to advertisers.</label>
        </section>
    </section>
    
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradelens";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email-input"];
    $pwd = $_POST["password-input"];

    // Überprüfen, ob die E-Mail-Adresse gültig ist
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div id='message'>Ungültige E-Mail-Adresse.<br>Bitte überprüfe deine Eingabe.</div>";
        echo "<meta http-equiv='refresh' content='3;url=signup.php'>";
        exit();
    }

    // Überprüfen, ob die E-Mail-Adresse mindestens 2 Zeichen vor dem @ und 5 Zeichen + Domain danach hat
    $emailParts = explode("@", $email);
    if (strlen($emailParts[0]) < 2 || strlen(explode(".", $emailParts[1])[0]) < 5) {
        echo "<div id='message'>Die E-Mail-Adresse muss mindestens 2 Zeichen vor dem @ und 5 Zeichen + Domain danach haben.<br>Bitte überprüfe deine Eingabe.</div>";
        echo "<meta http-equiv='refresh' content='3;url=signup.php'>";
        exit();
    }

    $hashed_password = password_hash($pwd, PASSWORD_BCRYPT);

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if (($result->num_rows > 0) && ($email != '') && ($pwd != '')) {
        echo "<div id='message'>Diese E-Mail-Adresse ist bereits registriert.<br>Du wirst nun weiter geleitet.</div>";
        echo "<meta http-equiv='refresh' content='3;url=login.php'>";
        exit();
    } else {
        if (($email != '') && ($pwd != '')) {
            $sql = "INSERT INTO users (email, password) VALUES ('$email', '$hashed_password')"; 
        }
        if ($conn->query($sql) === TRUE) {
            header("Location: login.php?email=" . urlencode($email) . "&password=" . urlencode($pwd));
            exit();
        } else {
            exit();
        }
    }
}

$conn->close();
?>
</body>
</html>