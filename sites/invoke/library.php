<?php
session_start();
include '../../api/connectdatabase.php';

$uID = $_SESSION['uID'] ?? null;
?>

    <link rel="stylesheet" href="/GradeLens/style/library.css">
    <meta charset="UTF-8">
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
                                echo "<div>Subject: " . htmlspecialchars($row['subject_name']) . "</div>";
                                echo "<div>Grade: " . htmlspecialchars($row['grade_value']) . "</div>";
                                echo "<div>Date: " . htmlspecialchars($row['grade_date']) . "</div>";
                                echo "<div>Description: " . htmlspecialchars($row['description']) . "</div>";
                                echo "</section>";
                                echo "<div id='gradecontentdivider'></div>";
                            }
                        } else {
                            echo "No grades found.";
                        }
                        $stmt_grades->close();
                    } else {
                        echo "Invalid uID.";
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
                        INNER JOIN usersubjects us ON s.sID = us.sID
                        WHERE us.uID = ?
                        GROUP BY s.subject_name
                    ");
                        $stmt_subjects->bind_param("i", $uID);
                        $stmt_subjects->execute();
                        $result_subjects = $stmt_subjects->get_result();

                        if ($result_subjects->num_rows > 0) {
                            while ($row = $result_subjects->fetch_assoc()) {
                                echo "<section id='content'>";
                                echo "<div>Subject: " . htmlspecialchars($row['subject_name']) . "</div>";
                                echo "</section>";
                                echo "<div id='contentdivider'></div>";
                            }
                        } else {
                            echo "No subjects found.";
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
                    WHERE uID = ? 
                "); // Added WHERE clause to filter by uID
                    if ($uID && is_numeric($uID) && $stmt_assessments) {
                        $stmt_assessments->bind_param("i", $uID);
                        $stmt_assessments->execute();
                        $result_assessments = $stmt_assessments->get_result();

                        if ($result_assessments->num_rows > 0) {
                            while ($row = $result_assessments->fetch_assoc()) {
                                echo "<section id='content'>";
                                echo "<div>Typ: " . htmlspecialchars($row['type_name']) . "</div>";
                                echo "<div>Description: " . htmlspecialchars($row['description']) . "</div>";
                                echo "</section>";
                                echo "<div id='contentdivider'></div>";
                            }
                        } else {
                            echo "No assessment types found for this user.";
                        }
                        $stmt_assessments->close();
                    } else {
                        echo "Could not retrieve assessment types. User not logged in or database error.";
                        if (!$stmt_assessments && $conn->error) {
                        }
                    }
                    ?>
                </section>
            </section>
        </section>
    </section>

<?php
include '../../api/disconnectdatabase.php';
?>