<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
include '../../../api/connectdatabase.php';
$uID = $_SESSION['uID'];

// Fächer des Benutzers laden (JOIN mit usersubjects)
$subjects = [];
$stmt_subjects = $conn->prepare(
    "SELECT s.sID, s.subject_name 
     FROM subjects s 
     JOIN usersubjects us ON s.sID = us.sID 
     WHERE us.uID = ? 
     ORDER BY s.subject_name"
);
if ($stmt_subjects) {
    $stmt_subjects->bind_param("i", $uID);
    $stmt_subjects->execute();
    $result_subjects = $stmt_subjects->get_result();
    while ($row = $result_subjects->fetch_assoc()) {
        $subjects[] = $row;
    }
    $stmt_subjects->close();
}

// Benutzerspezifische Bewertungsarten laden (aus assessmenttype, gefiltert nach uID)
$assessment_types = [];
// Beachten Sie, dass assessmenttype.aID der Primärschlüssel ist
$stmt_assessment_types = $conn->prepare(
    "SELECT at.aID, at.type_name, at.weight_factor 
     FROM assessmenttype at 
     WHERE at.uID = ? 
     ORDER BY at.type_name"
);
if ($stmt_assessment_types) {
    $stmt_assessment_types->bind_param("i", $uID);
    $stmt_assessment_types->execute();
    $result_assessment_types = $stmt_assessment_types->get_result();
    while ($row = $result_assessment_types->fetch_assoc()) {
        $assessment_types[] = $row;
    }
    $stmt_assessment_types->close();
}

?>
<link rel="stylesheet" href="/gradelens/style/grade.css">
<link rel="stylesheet" href="/gradelens/style/successmessage.css">
<link rel="stylesheet" href="/gradelens/style/errormessage.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; ?>
    <section id="creationcontainer">
        <p id="title">ADD A GRADE</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">In which subject did you achieve the grade?</p>
                <select id="select" title="Subject" name="sID" required>
                    <option value="">-- Select Subject --</option>
                    <?php foreach ($subjects as $subject): ?>
                        <option value="<?php echo htmlspecialchars($subject['sID']); ?>">
                            <?php echo htmlspecialchars($subject['subject_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What type of assessment was it?</p>
                <select id="select" title="Assessment Type" name="aID" required> {/* Name geändert zu aID */}
                    <option value="">-- Select Assessment Type --</option>
                    <?php foreach ($assessment_types as $assessment_type): ?>
                        {/* Wert ist assessmenttype.aID */}
                        <option value="<?php echo htmlspecialchars($assessment_type['aID']); ?>">
                            <?php echo htmlspecialchars($assessment_type['type_name']); ?> (Weight: <?php echo htmlspecialchars($assessment_type['weight_factor']); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What grade did you achieve?</p>
                <input type="number" step="0.1" id="select" name="grade_value" placeholder="Enter the grade (e.g. 1.0, 2.5)" required>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">When was the assessment?</p>
                <input type="date" id="select" name="grade_date" required>
            </section>
            <section id="selectdescriptioncontainer"> {/* Korrigierter ID-Name */}
                <p id="selectdescriptiontitle">Briefly describe the assessment (optional).</p> {/* Korrigierter ID-Name */}
                <input type="text" id="selectdescription" name="description" placeholder="Enter the description"> {/* Korrigierter ID-Name */}
            </section>
            <button id="addbutton" type="submit">Add Grade</button>
        </form>
    </section>
</section>
</body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // uID wurde bereits oben initialisiert
    if (!$uID) {
        include '../../errors/useriderror.html';
        exit();
    }

    $sID = filter_input(INPUT_POST, 'sID', FILTER_VALIDATE_INT);
    $aID = filter_input(INPUT_POST, 'aID', FILTER_VALIDATE_INT); // Geändert von atID zu aID
    $grade_value_str = $_POST['grade_value'] ?? '';
    $grade_date_str = $_POST['grade_date'] ?? '';
    $description = trim($_POST['description'] ?? '');

    $grade_value_str_db = str_replace(',', '.', $grade_value_str);
    if (!is_numeric($grade_value_str_db) || !preg_match('/^-?\d{1,2}(\.\d{1})?$/', $grade_value_str_db)) {
        echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
        echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>GRADE VALUE ERROR</p><section id='errorfoot'><p id='errormessage'>Invalid grade value. Please use format X.X (e.g. 1.0, 2.5).</p></section></section></section>";
        echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
        include '../../../api/disconnectdatabase.php';
        exit();
    }
    $grade_value = floatval($grade_value_str_db);

    if (!$sID || !$aID || empty($grade_value_str_db) || empty($grade_date_str)) {
        echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
        echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>INPUT ERROR</p><section id='errorfoot'><p id='errormessage'>Please fill all required fields (Subject, Assessment Type, Grade, Date).</p></section></section></section>";
        echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
    } else {
        $grade_date_obj = date_create($grade_date_str);
        if (!$grade_date_obj) {
            echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
            echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>DATE ERROR</p><section id='errorfoot'><p id='errormessage'>Invalid date format.</p></section></section></section>";
            echo "<meta http-equiv='refresh' content='4;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
        } else {
            $grade_date = date_format($grade_date_obj, 'Y-m-d');

            // INSERT mit aID für assessmenttype
            $stmt_insert_grade = $conn->prepare("INSERT INTO grades (uID, sID, aID, grade_value, grade_date, description) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert_grade->bind_param("iiidss", $uID, $sID, $aID, $grade_value, $grade_date, $description);

            if ($stmt_insert_grade->execute()) {
                echo "<link rel='stylesheet' href='/GradeLens/style/successmessage.css'>";
                echo "<section id='successbackground'><section id='successcontainer'><p id='successhead'>GRADE ADDED</p><section id='successfoot'><p id='successmessage'>Grade successfully added.</p></section></section></section>";
                echo "<meta http-equiv='refresh' content='4;url=/GradeLens/sites/overview.php?content=library.php'>";
            } else {
                echo "<link rel='stylesheet' href='/GradeLens/style/errormessage.css'>";
                echo "<section id='errorbackground'><section id='errorcontainer'><p id='errorhead'>DATABASE ERROR</p><section id='errorfoot'><p id='errormessage'>Error adding grade to database: " . htmlspecialchars($stmt_insert_grade->error) . "</p></section></section></section>";
                echo "<meta http-equiv='refresh' content='6;url=" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>";
            }
            $stmt_insert_grade->close();
        }
    }
}
include '../../../api/disconnectdatabase.php';
?>
</html>