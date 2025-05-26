<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
include '../../../api/connectdatabase.php'; //
?>
<link rel="stylesheet" href="/gradelens/style/assessment.css">
<link rel="stylesheet" href="/gradelens/style/successmessage.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; // Pfad angepasst ?>
    <section id="creationcontainer">
        <p id="title">CREATE AN ASSESSMENT TYPE</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">How high is the multiplier (weight factor)?</p>
                <select id="select" title="Weight Factor" name="weight_factor">
                    <option value="0.5">0.5</option>
                    <option value="1.0" selected>1.0</option>
                    <option value="1.5">1.5</option>
                    <option value="2.0">2.0</option>
                    <option value="2.5">2.5</option>
                    <option value="3.0">3.0</option>
                    <option value="3.5">3.5</option>
                    <option value="4.0">4.0</option>
                    <option value="4.5">4.5</option>
                    <option value="5.0">5.0</option>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What is the assessment type called?</p>
                <input type="text" id="selectname" placeholder="Enter the name of the assessment type" name="type_name" required>
            </section>
            <section id="selectdescriptioncontainer">
                <p id="selectdescriptiontitle">Briefly describe this type of assessment.</p>
                <input type="text" id="selectdescription" placeholder="Enter the description" name="description">
            </section>
            <button id="addbutton" type="submit">Create and Assign Assessment Type</button>
        </form>
    </section>
</section>
</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uID = $_SESSION['uID'] ?? null;
    if (!$uID) {
        // Fehler: Benutzer nicht identifiziert
        include '../../errors/useriderror.html'; //
        // header("Location: /GradeLens/sites/login.php"); //
        exit();
    }

    $type_name = trim($_POST['type_name'] ?? '');
    $weight_factor = floatval($_POST['weight_factor'] ?? 1.00);
    $description = trim($_POST['description'] ?? '');

    if (empty($type_name)) {
        include '../../errors/assessment_typeerror.html'; //
    } else {
        $conn->begin_transaction();
        $atID = null;

        // Prüfen, ob der Bewertungstyp global bereits existiert
        $check_stmt_global = $conn->prepare("SELECT atID FROM assessmenttype WHERE type_name = ?");
        $check_stmt_global->bind_param("s", $type_name);
        $check_stmt_global->execute();
        $check_stmt_global->store_result();

        if ($check_stmt_global->num_rows > 0) {
            // Typ existiert global, atID holen
            $check_stmt_global->bind_result($existing_atID);
            $check_stmt_global->fetch();
            $atID = $existing_atID;
            $check_stmt_global->close();
        } else {
            // Typ existiert global nicht, also neu anlegen
            $check_stmt_global->close();
            $stmt_insert_assessmenttype = $conn->prepare("INSERT INTO assessmenttype (type_name, weight_factor, description) VALUES (?, ?, ?)");
            $stmt_insert_assessmenttype->bind_param("sds", $type_name, $weight_factor, $description);
            if ($stmt_insert_assessmenttype->execute()) {
                $atID = $conn->insert_id;
            } else {
                include '../../errors/assessment_databaseerror.html'; //
                $conn->rollback();
                $stmt_insert_assessmenttype->close();
                include '../../../api/disconnectdatabase.php'; //
                exit();
            }
            $stmt_insert_assessmenttype->close();
        }

        if ($atID) {
            // Prüfen, ob die Zuordnung für diesen User bereits existiert
            $check_userassessment_stmt = $conn->prepare("SELECT COUNT(*) FROM userassessments WHERE uID = ? AND atID = ?");
            $check_userassessment_stmt->bind_param("ii", $uID, $atID);
            $check_userassessment_stmt->execute();
            $check_userassessment_stmt->bind_result($user_assessment_count);
            $check_userassessment_stmt->fetch();
            $check_userassessment_stmt->close();

            if ($user_assessment_count > 0) {
                // Benutzer hat diesen Bewertungstyp bereits zugewiesen
                // Hier könnte man eine Meldung ausgeben, dass der Typ bereits zugewiesen ist.
                // Für dieses Beispiel nehmen wir an, dass es okay ist und zeigen Erfolg.
                include '../../success/assessment_added.html'; // // Oder eine spezifischere "schon zugewiesen" Meldung
                $conn->commit(); // Kein Fehler, also commit
            } else {
                // Zuordnung in userassessments einfügen
                $stmt_userassessments = $conn->prepare("INSERT INTO userassessments (uID, atID) VALUES (?, ?)");
                $stmt_userassessments->bind_param("ii", $uID, $atID);
                if ($stmt_userassessments->execute()) {
                    include '../../success/assessment_added.html'; //
                    $conn->commit();
                } else {
                    include '../../errors/assessment_databaseerror.html'; // // Fehler beim Zuordnen
                    $conn->rollback();
                }
                $stmt_userassessments->close();
            }
        } else {
            // Fallback, sollte nicht erreicht werden, wenn Logik oben korrekt ist
            include '../../errors/assessment_databaseerror.html'; //
            $conn->rollback();
        }
    }
}
include '../../../api/disconnectdatabase.php'; //
?>
</html>