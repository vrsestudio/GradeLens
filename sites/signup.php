<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="/GradeLens/style/signup.css">
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
        <section id="signupbody">
            <div id="textbox-signup">
                <p id="welcometext-signup">Welcome to</p>
                <p id="titletext-signup">GradeLens</p>
            </div>

            <form id="signup-form" method="post">
                <section id="emailcontainer-signup">
                    <input type="email" name="email" id="email-signup" placeholder="example@gradelens.com" required>
                </section>
                <section id="passwordcontainer-signup">
                    <input type="password" name="password" id="password-signup" placeholder="Password (8 characters minimum)" required pattern=".{8,}" title="Password must be at least 8 characters long.">
                </section>
                <section id="checkcontainer-signup">
                    <button type="submit" id="checkbutton-signup">Sign Up</button>
                </section>
            </form>
        </section>

        <?php
        include '../api/connectdatabase.php';
        ?>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email_input = $_POST['email'] ?? '';
            $password_input = $_POST['password'] ?? '';

            if (strlen($password_input) < 8) {
                include './errors/signup_user.html';
                if (isset($conn) && $conn instanceof mysqli) {
                    $conn->close();
                }
                exit();
            }

            $user_ip_address = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';
            $hashed_email = hash('sha256', $email_input);

            $salt = bin2hex(random_bytes(8));

            $password_with_salt = $password_input . $salt;
            $hashed_password = password_hash($password_with_salt, PASSWORD_BCRYPT);

            $conn->begin_transaction();

            try {
                $check_stmt = $conn->prepare("SELECT uID FROM users WHERE email = ?");
                $check_stmt->bind_param("s", $hashed_email);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    include './errors/signup_email.html';
                    $check_stmt->close();
                    $conn->rollback();
                } else {
                    $check_stmt->close();

                    $stmt_user = $conn->prepare("INSERT INTO users (email, password, fkipa, lkipa, SALT) VALUES (?, ?, ?, ?, ?)");
                    $stmt_user->bind_param("sssss", $hashed_email, $hashed_password, $user_ip_address, $user_ip_address, $salt);

                    if ($stmt_user->execute()) {
                        $uID = $conn->insert_id;
                        $conn->commit();

                        if (session_status() === PHP_SESSION_NONE) {
                            session_start();
                        }
                        $_SESSION['uID'] = $uID;
                        include './success/signup_completed.html';
                        $stmt_user->close();
                        include '../api/disconnectdatabase.php';
                        exit();
                    } else {
                        include './errors/signup_user.html';
                        $conn->rollback();
                    }
                    $stmt_user->close();
                }
            } catch (mysqli_sql_exception $e) {
                include './errors/signup_database.html';
                $conn->rollback();
            }
        }
        if (isset($conn) && $conn instanceof mysqli && $conn->thread_id) {
            include '../api/disconnectdatabase.php';
        }
        ?>
    </section>
</section>
</body>
</html>