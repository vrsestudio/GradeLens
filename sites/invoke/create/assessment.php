<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
// Pfad zu connectdatabase.php ist korrekt, wenn assessment.php in sites/invoke/create/ liegt
include '../../../api/connectdatabase.php';
?>
<link rel="stylesheet" href="/gradelens/style/assessment.css">
<link rel="stylesheet" href="/gradelens/style/successmessage.css">
<link rel="stylesheet" href="/gradelens/style/errormessage.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; // Pfad zu navigation.php ?>
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
            <button id="addbutton" type="submit">Create Assessment Type</button>
        </form>
    </section>
</section>
</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uID = $_SESSION['uID'] ?? null;
    if (!$uID) {
        // useriderror.html wird per meta-refresh weiterleiten, daher exit() hier nicht unbedingt nötig,
        // aber header() wurde vorher verwendet, was besser ist, wenn keine HTML-Ausgabe vorher stattfindet.
        include '../../errors/useriderror.html';
        exit();
    }

    $type_name = trim($_POST['type_name'] ?? '');
    $weight_factor = floatval($_POST['weight_factor'] ?? 1.00); // Standardwert, falls nicht gesetzt
    $description = trim($_POST['description'] ?? '');

    if (empty($type_name)) {
        include '../../errors/assessment_typeerror.html'; // Meldet, dass der Titel nicht leer sein darf
    } else {
        // Prüfen, ob der Benutzer bereits einen Bewertungstyp mit diesem Namen hat
        // Korrektur des Tabellennamens von assessmentype zu assessmenttype
        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM assessmenttype WHERE uID = ? AND type_name = ?");
        $check_stmt->bind_param("is", $uID, $type_name);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            // Fehler: Der Benutzer hat bereits einen Bewertungstyp mit diesem Titel
            include '../../errors/assessment_titleerror.html';
        } else {
            // Neuen Bewertungstyp für den Benutzer einfügen
            // Die Tabelle assessmenttype hat jetzt aID (PK, auto-increment) und uID (FK)
            $stmt = $conn->prepare("INSERT INTO assessmenttype (uID, type_name, weight_factor, description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isds", $uID, $type_name, $weight_factor, $description);

            if ($stmt->execute()) {
                include '../../success/assessment_added.html';
            } else {
                include '../../errors/assessment_databaseerror.html';
            }
            $stmt->close();
        }
    }
}
// Pfad zu disconnectdatabase.php ist korrekt
include '../../../api/disconnectdatabase.php';
?>
</html>