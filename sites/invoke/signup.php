<section id="signupbody">
    <div id="textbox-signup">
        <p id="welcometext-signup">Welcome to</p>
        <p id="titletext-signup">GradeLens</p>
    </div>

    <form id="signup-form" method="post" action="">
        <section id="emailcontainer-signup">
            <input type="email" name="email" id="email-signup" placeholder="example@gradelens.com" required>
        </section>
        <section id="passwordcontainer-signup">
            <input type="password" name="password" id="password-signup" placeholder="Password (8 characters minimum)" required>
        </section>
        <section id="checkcontainer-signup">
            <button type="submit" id="checkbutton-signup" form="signup-form">Sign Up</button>
        </section>
    </form>
</section>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gradelens";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $password = $_POST["password"];

    if ($email === false) {
        echo "<script>alert('Invalid email format');</script>";
    } elseif (strlen($password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful');</script>";
            // Optionally, redirect the user to the login page
            // header("Location: login.php");
            // exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}
$conn->close();
?>