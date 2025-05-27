<?php
session_start();
include '../../api/connectdatabase.php';

$uID = $_SESSION['uID'] ?? null;
?>

    <link rel="stylesheet" href="/GradeLens/style/library.css">

    <section id="librarybody">
        <section id="gradeside">
            <section id="leftcontentcontainer">
                <section id="gradetop">
                    GRADES
                </section>
                <section id="gradecontainer">
                    <?php
                    if ($uID && is_numeric($uID)) {
                        $stmt_grades = $conn->prepare("
                        SELECT g.grade_value, g.grade_date, g.description, s.subject_name
                        FROM grades g
                        INNER JOIN subjects s ON g.sID = s.sID
                        WHERE g.uID = ?
                        ORDER BY g.grade_date DESC
                    ");
                        $stmt_grades->bind_param("i", $uID);
                        $stmt_grades->execute();
                        $result_grades = $stmt_grades->get_result();

                        if ($result_grades->num_rows > 0) {
                            while ($row = $result_grades->fetch_assoc()) {
                                echo "<section id='content'>";
                                echo "<div>Fach: " . htmlspecialchars($row['subject_name']) . "</div>";
                                echo "<div>Note: " . htmlspecialchars($row['grade_value']) . "</div>";
                                echo "<div>Datum: " . htmlspecialchars($row['grade_date']) . "</div>";
                                echo "<div>Beschreibung: " . htmlspecialchars($row['description']) . "</div>";
                                echo "</section>";
                                echo "<div id='gradecontentdivider'></div>";
                            }
                        } else {
                            echo "Keine Noten gefunden.";
                        }
                        $stmt_grades->close();
                    } else {
                        echo "Nicht eingeloggt oder ungültige uID.";
                    }
                    ?>
                </section>
            </section>
        </section>

        <section id="rightside">
            <section id="rightcontentcontainer">
                <section id="subjecttop">
                    SUBJECTS
                </section>
                <section id="subjectcontainer">
                    <?php
                    if ($uID && is_numeric($uID)) {
                        $stmt_subjects = $conn->prepare("
                        SELECT s.subject_name
                        FROM subjects s
                        INNER JOIN grades g ON s.sID = g.sID
                        WHERE g.uID = ?
                        GROUP BY s.subject_name
                    ");
                        $stmt_subjects->bind_param("i", $uID);
                        $stmt_subjects->execute();
                        $result_subjects = $stmt_subjects->get_result();

                        if ($result_subjects->num_rows > 0) {
                            while ($row = $result_subjects->fetch_assoc()) {
                                echo "<section id='content'>";
                                echo "<div>Fach: " . htmlspecialchars($row['subject_name']) . "</div>";
                                echo "</section>";
                                echo "<div id='contentdivider'></div>";
                            }
                        } else {
                            echo "Keine Fächer gefunden.";
                        }
                        $stmt_subjects->close();
                    }
                    ?>
                </section>
            </section>
            <div id="placeholder"></div>
            <section id="rightcontentcontainer">
                <section id="assessmenttop">
                    ASSESSMENTTYPES
                </section>
                <section id="assessmentcontainer">
                    <?php
                    $stmt_assessments = $conn->prepare("
                    SELECT type_name, description
                    FROM assessmenttype
                ");
                    $stmt_assessments->execute();
                    $result_assessments = $stmt_assessments->get_result();

                    if ($result_assessments->num_rows > 0) {
                        while ($row = $result_assessments->fetch_assoc()) {
                            echo "<section id='content'>";
                            echo "<div>Typ: " . htmlspecialchars($row['type_name']) . "</div>";
                            echo "<div id='textcontent'>Beschreibung: " . htmlspecialchars($row['description']) . "</div>";
                            echo "</section>";
                            echo "<div id='contentdivider'></div>";
                        }
                    } else {
                        echo "Keine Assessment-Typen gefunden.";
                    }
                    $stmt_assessments->close();
                    ?>
                </section>
            </section>
        </section>
    </section>

<?php
include '../../api/disconnectdatabase.php';
?>