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
<link rel="stylesheet" href="/gradelens/style/assessment.css">
<link rel="stylesheet" href="/gradelens/style/successmessage.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; // Pfad angepasst ?>
    <section id="creationcontainer">
        <p id="title">CREATE AN ASSESSMENT</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">How high is the multiplier?</p>
                <select id="select" title="Subject" name="weight_factor"> <option value="0.5">0.5</option>
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
                <p id="selecttitle">What is the assessment called?</p>
                <input type="text" id="selectname" placeholder="Enter the name of the assessment" name="type_name" required> </section>
            <section id="selectdescriptioncontainer">
                <p id="selectdescriptiontitle">Briefly describe this type of assessment.</p>
                <input type="text" id="selectdescription" placeholder="Enter the description" name="description"> </section>
            <button id="addbutton" type="submit">Create Assessment</button>
        </form>
    </section>
</section>
</body>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uID = $_SESSION['uID'] ?? null;
    if (!$uID) {

        include '../../errors/useriderror.html';
        header("Location: /GradeLens/sites/login.php");
        exit();
    }

    $type_name = trim($_POST['type_name'] ?? '');
    $weight_factor = floatval($_POST['weight_factor'] ?? 1.00);
    $description = trim($_POST['description'] ?? '');

    if (empty($type_name)) {
        include '../../errors/assessment_typeerror.html';
    } else {

        $check_stmt = $conn->prepare("SELECT COUNT(*) FROM assessment_types WHERE uID = ? AND type_name = ?");
        $check_stmt->bind_param("is", $uID, $type_name);
        $check_stmt->execute();
        $check_stmt->bind_result($count);
        $check_stmt->fetch();
        $check_stmt->close();

        if ($count > 0) {
            include '../../errors/assessment_titleerror.html';
        } else {

            $stmt = $conn->prepare("INSERT INTO assessment_types (uID, type_name, weight_factor, description) VALUES (?, ?, ?, ?)");

            $stmt->bind_param("isds", $uID, $type_name, $weight_factor, $description);

            if ($stmt->execute()) {
                include '../../errors/assessment_added.html';
            } else {
                include '../../errors/assessment_databaseerror.html';
            }
            $stmt->close();
        }
    }
}
include '../../../api/disconnectdatabase.php';
?>
</html>