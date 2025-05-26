<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
include '../../../api/connectdatabase.php';
?>
<link rel="stylesheet" href="/gradelens/style/subject.css">
<link rel="stylesheet" href="/gradelens/style/successmessage.css">
<link rel="stylesheet" href="/gradelens/style/errormessage.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; ?>
    <section id="creationcontainer">
        <p id="title">CREATE A SUBJECT</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">What is the subject called?</p>
                <input type="text" id="select" name="subject_name" placeholder="E.G. Math, Science, etc." required>
            </section>
            <button id="addbutton" type="submit">Create and Assign Subject</button>
        </form>
    </section>
</section>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uID = $_SESSION['uID'] ?? null;
    if (!$uID) {
        include '../../errors/useriderror.html';
        exit();
    }

    $subject_name = trim($_POST['subject_name'] ?? '');

    if (empty($subject_name)) {
        // Angepasste Fehlermeldung, da keine Standard-HTML-Datei dafür vorhanden war.
        echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
        echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>SUBJECT NAME ERROR</p><section id='errorfoot'><p id='errormessage'>Subject name must not be empty.</p></section></section></section>";
        echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>"; // Zurück zur aktuellen Seite
    } else {
        $conn->begin_transaction();
        $sID = null;

        // Prüfen, ob das Fach global bereits existiert
        $check_stmt_global = $conn->prepare("SELECT sID FROM subjects WHERE subject_name = ?");
        $check_stmt_global->bind_param("s", $subject_name);
        $check_stmt_global->execute();
        $check_stmt_global->store_result();

        if ($check_stmt_global->num_rows > 0) {
            $check_stmt_global->bind_result($existing_sID);
            $check_stmt_global->fetch();
            $sID = $existing_sID;
            $check_stmt_global->close();
        } else {
            $check_stmt_global->close();
            $stmt_insert_subject = $conn->prepare("INSERT INTO subjects (subject_name) VALUES (?)");
            $stmt_insert_subject->bind_param("s", $subject_name);
            if ($stmt_insert_subject->execute()) {
                $sID = $conn->insert_id;
            } else {
                echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
                echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>DATABASE ERROR</p><section id='errorfoot'><p id='errormessage'>Error creating subject globally.</p></section></section></section>";
                echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                $conn->rollback();
                $stmt_insert_subject->close();
                include '../../../api/disconnectdatabase.php';
                exit();
            }
            $stmt_insert_subject->close();
        }

        if ($sID) {
            // Prüfen, ob die Zuordnung für diesen User bereits existiert
            $check_usersubject_stmt = $conn->prepare("SELECT COUNT(*) FROM usersubjects WHERE uID = ? AND sID = ?");
            $check_usersubject_stmt->bind_param("ii", $uID, $sID);
            $check_usersubject_stmt->execute();
            $check_usersubject_stmt->bind_result($user_subject_count);
            $check_usersubject_stmt->fetch();
            $check_usersubject_stmt->close();

            if ($user_subject_count > 0) {
                echo "<link rel='stylesheet' href='/GradeLens/style/successmessage.css'>";
                echo "<section id='successbackground'><section id='successcontainer'><p id='successhead'>ALREADY ASSIGNED</p><section id='successfoot'><p id='successmessage'>This subject is already assigned to you.</p></section></section></section>";
                echo "<meta http-equiv='refresh' content='4;url=/GradeLens/sites/overview.php?content=library.php'>";
                $conn->commit();
            } else {
                $stmt_usersubjects = $conn->prepare("INSERT INTO usersubjects (uID, sID) VALUES (?, ?)");
                $stmt_usersubjects->bind_param("ii", $uID, $sID);
                if ($stmt_usersubjects->execute()) {
                    echo "<link rel='stylesheet' href='/GradeLens/style/successmessage.css'>";
                    echo "<section id='successbackground'><section id='successcontainer'><p id='successhead'>SUBJECT ASSIGNED</p><section id='successfoot'><p id='successmessage'>Subject successfully created and assigned.</p></section></section></section>";
                    echo "<meta http-equiv='refresh' content='4;url=/GradeLens/sites/overview.php?content=library.php'>";
                    $conn->commit();
                } else {
                    echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
                    echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>DATABASE ERROR</p><section id='errorfoot'><p id='errormessage'>Error assigning subject to user.</p></section></section></section>";
                    echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
                    $conn->rollback();
                }
                $stmt_usersubjects->close();
            }
        } else {
            $conn->rollback();
            echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
            echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>UNEXPECTED ERROR</p><section id='errorfoot'><p id='errormessage'>An unexpected error occurred while processing the subject.</p></section></section></section>";
            echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
        }
    }
}
include '../../../api/disconnectdatabase.php';
?>
</html>