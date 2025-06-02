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
                    <input type="email" name="email" id="email-login" placeholder="example@gradelens.com" required>
                </section>
                <section id="passwordcontainer-login">
                    <input type="password" name="password" id="password-login" placeholder="Password" required>
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
    $email_input = $_POST['email'] ?? '';
    $password_input = $_POST['password'] ?? '';

    if (empty($email_input) || empty($password_input)) {
        include './errors/login_email.html';
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        exit();
    }

    $hashed_email = hash('sha256', $email_input);
    $current_user_ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    $stmt = $conn->prepare("SELECT uID, password, SALT FROM users WHERE email = ?");
    $stmt->bind_param("s", $hashed_email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($uID, $stored_password_hash, $stored_salt);
        $stmt->fetch();
        $stmt->close();

        $password_to_verify = $password_input . $stored_salt;

        if (password_verify($password_to_verify, $stored_password_hash)) {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $_SESSION['uID'] = $uID;

            $update_stmt = $conn->prepare("UPDATE users SET lkipa = ?, timestamp = CURRENT_TIMESTAMP WHERE uID = ?");
            $update_stmt->bind_param("si", $current_user_ip_address, $uID);
            $update_stmt->execute();
            $update_stmt->close();

            include '../api/disconnectdatabase.php';
            header("Location: /GradeLens/sites/overview.php");
            exit();
        } else {
            include './errors/login_password.html';
        }
    } else {
        $stmt->close();
        include './errors/login_email.html';
    }
}

if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
    include '../api/disconnectdatabase.php';
}
?>