<?php
if (isset($_GET["email"]) && isset($_GET["password"])) {
    $email = $_GET["email"];
    $password = $_GET["password"];
}?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../style/login.css">
</head>
<body id="body" background="../source/background/default_background_light.webp">
    <section id="signup-container">
        <div id="top">
            <h1 id="signup-text">LOGIN /</h1><h1 type="button" id="registration-button" onclick="window.location.href='./signup.php'">SIGNUP</h1>
            <div id="indicator"></div>
            <a id="logo"><img src="../source/project/logo_gradelens_dark_transparent.webp" alt="Logo" width="50px" height="50px"></a>
        </div>
        <div id="top-seperator"></div>

        <p id="infotext">To use GradeLens™ and all it functions properly you have to sign up or log in.</p>
        <form id="credentials-container" method="post" action="">
            <label for="email-input">Email</label>
            <input type="email" id="email-input" name="email-input" <?php if (isset($email)) echo "value=\"$email\"";?>>

            <label for="password-input">Password</label>
            <input type="password" id="password-input" name="password-input" <?php if (isset($password)) echo "value=\"$password\"";?>>

            <div id="top-seperator"></div>

            <button type="submit" id="submit-button">Login</button>
        </form>
        <section id="bottom">
            </label><label id="tos-text">Please be aware that GradeLens™ will store and collect the information that you put in. It will also process given data to store it quick and efficiently in our database. However we are currently not selling that data to advertisers</label>
        </section>
    </section>

    <?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "gradelens";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: ". $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email-input"];
        $pwd = $_POST["password-input"];

        $sql = "SELECT uID, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($uID, $hashed_password_from_database);

        if ($stmt->fetch() && password_verify($pwd, $hashed_password_from_database)) {
            $_SESSION['uID'] = $uID;
            setcookie('uID', $uID, time() + 3600, '/'); // Cookie Automatically Deletes Itself After 1 Hour

            // Extract IP-Adresse
            $ip = $_SERVER['REMOTE_ADDR'];

            // Save IP-Address in Database
            $updateSql = "UPDATE users SET lipa = ? WHERE uID = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("si", $ip, $uID);
            $updateStmt->execute();
            $updateStmt->close();

            header("Location: overview.php");
            exit();
        } else {
            // Error Handling
        }
        $stmt->close();
    }
    $conn->close();
    ?>
</body>
</html>