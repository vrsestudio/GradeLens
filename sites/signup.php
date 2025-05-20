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
                    <input type="password" name="password" id="password-signup" placeholder="Password (8 characters minimum)" required>
                </section>
                <section id="checkcontainer-signup">
                    <button type="submit" id="checkbutton-signup">Sign Up</button>
                </section>
            </form>
        </section>

        <?php include '../api/connectdatabase.php'; ?>
        <?php

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // IP-Adresse des Benutzers abrufen
            $user_ip_address = $_SERVER['REMOTE_ADDR'] ?? null;

            // E-Mail hashen
            $hashed_email = hash('sha256', $email);
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Transaktion starten, um Konsistenz zu gewährleisten
            $conn->begin_transaction();
            $registration_successful = false;

            try {
                // 1. Überprüfen, ob E-Mail bereits existiert
                $check_stmt = $conn->prepare("SELECT uID FROM users WHERE email = ?");
                $check_stmt->bind_param("s", $hashed_email);
                $check_stmt->execute();
                $check_stmt->store_result();

                if ($check_stmt->num_rows > 0) {
                    // E-Mail existiert bereits - Fehlerbehandlung hier (z.B. Nachricht anzeigen)
                    echo "<p style='color: red; text-align: center;'>Diese E-Mail ist bereits registriert.</p>";
                    $check_stmt->close();
                    $conn->rollback();
                } else {
                    $check_stmt->close();

                    // 2. Neuen Benutzer einfügen
                    $stmt_user = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
                    $stmt_user->bind_param("ss", $hashed_email, $hashed_password);

                    if ($stmt_user->execute()) {
                        $uID = $conn->insert_id; // Die automatisch generierte uID des neuen Benutzers

                        // 3. IP-Adresse in die authentication-Tabelle einfügen
                        $stmt_auth = $conn->prepare("INSERT INTO authentication (fkipa, lkipa) VALUES (?, ?)");
                        $stmt_auth->bind_param("ss", $user_ip_address, $user_ip_address); // fkipa und lkipa sind zunächst gleich

                        if ($stmt_auth->execute()) {
                            $aID = $conn->insert_id; // Die automatisch generierte aID des neuen Auth-Eintrags

                            // 4. Einträge in userauthentication-Tabelle verknüpfen
                            $stmt_user_auth = $conn->prepare("INSERT INTO userauthentication (uID, aID) VALUES (?, ?)");
                            $stmt_user_auth->bind_param("ii", $uID, $aID);

                            if ($stmt_user_auth->execute()) {
                                $conn->commit(); // Alle Operationen erfolgreich, Transaktion abschließen
                                $registration_successful = true;
                                // Session starten und uID speichern, dann weiterleiten
                                if (session_status() === PHP_SESSION_NONE) {
                                    session_start();
                                }
                                $_SESSION['uID'] = $uID;
                                header("Location: /GradeLens/sites/overview.php");
                                exit();
                            } else {
                                echo "<p style='color: red; text-align: center;'>Fehler beim Verknüpfen der Authentifizierungsdaten.</p>";
                                $conn->rollback();
                            }
                            $stmt_user_auth->close();
                        } else {
                            echo "<p style='color: red; text-align: center;'>Fehler beim Speichern der IP-Adresse.</p>";
                            $conn->rollback();
                        }
                        $stmt_auth->close();
                    } else {
                        echo "<p style='color: red; text-align: center;'>Fehler bei der Benutzerregistrierung.</p>";
                        $conn->rollback();
                    }
                    $stmt_user->close();
                }
            } catch (mysqli_sql_exception $e) {
                // Allgemeine Fehlerbehandlung für Datenbankfehler
                echo "<p style='color: red; text-align: center;'>Ein Datenbankfehler ist aufgetreten: " . $e->getMessage() . "</p>";
                $conn->rollback();
            }
        }
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
        ?>
    </section>
</section>
</body>
</html>