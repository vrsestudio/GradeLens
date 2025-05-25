<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authenticate</title>
    <link rel="stylesheet" href="/GradeLens/style/login.css">
    <script src="/GradeLens/script/dynamicauth.js" defer></script>
    <script src="/GradeLens/script/disclaimer.js" defer></script>
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
            <img src="/GradeLens/source/project/logo_gradelens_dark_transparent.webp" alt="logo" id="authlogo">
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
        </section>
    </section>
</section>
</body>
</html>

<?php
include '../api/endsession.php';
include '../api/connectdatabase.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $hashed_email = hash('sha256', $email);

    $user_ip_address = $_SERVER['REMOTE_ADDR'] ?? null;

    $stmt = $conn->prepare("SELECT uID, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $hashed_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uID, $stored_password);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($password, $stored_password)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['uID'] = $uID;

            $stmt_get_aID = $conn->prepare("SELECT aID FROM userauthentication WHERE uID = ?");
            $stmt_get_aID->bind_param("i", $uID);
            $stmt_get_aID->execute();
            $stmt_get_aID->bind_result($aID);
            $stmt_get_aID->fetch();
            $stmt_get_aID->close();

            if ($aID) {
                $stmt_update_lkipa = $conn->prepare("UPDATE authentication SET lkipa = ? WHERE aID = ?");
                $stmt_update_lkipa->bind_param("si", $user_ip_address, $aID);
                $stmt_update_lkipa->execute();
                $stmt_update_lkipa->close();
            }

            header("Location: /GradeLens/sites/overview.php"); // Absolute URL verwenden
            exit();
        } else {
            include './errors/login_password.html';
        }
    } else {
        include './errors/login_email.html';
    }
}

include '../api/disconnectdatabase.php';
?>