<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="../style/signup.css">
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
                    <input type="password" name="password" id="password-signup" placeholder="Password (8 characters minimum)" required>
                </section>
                <section id="checkcontainer-signup">
                    <button type="submit" id="checkbutton-signup">Sign Up</button>
                </section>
            </form>
        </section>

        <?php
        $hostname = "localhost";
        $username = "root";
        $password = "";
        $database = "gradelens";
        $conn = new mysqli($hostname, $username, $password, $database);
        // Check database connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Hash email and password
            $hashed_email = hash('sha256', $email);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Check if email already exists
            $check_stmt = $conn->prepare("SELECT uID FROM users WHERE email = ?");
            $check_stmt->bind_param("s", $hashed_email);
            $check_stmt->execute();
            $check_stmt->store_result();

            if ($check_stmt->num_rows > 0) {
                // Output if email already exists
            } else {
                // Prepare SQL statement to insert new user
                $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $hashed_email, $hashed_password);

                if ($stmt->execute()) {
                    // Potential success handling
                } else {
                    // Potential error handling
                }

                $stmt->close();
            }

            $check_stmt->close();
        }

        // Close connection
        $conn->close();
        ?>
    </section>
</section>
</body>
</html>