<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}

include '../../api/connectdatabase.php'; //

$uID = $_SESSION['uID'];
$current_date = date('Y-m-d');
$three_months_ago = date('Y-m-d', strtotime('-3 months'));

function calculateWeightedAverage($grades) {
    $total_weighted_grade = 0;
    $total_weight = 0;
    if (empty($grades)) {
        return null;
    }
    foreach ($grades as $grade) {
        $total_weighted_grade += $grade['grade_value'] * $grade['weight_factor'];
        $total_weight += $grade['weight_factor'];
    }
    return $total_weight > 0 ? round($total_weighted_grade / $total_weight, 2) : null;
}

$all_grades_data = [];
$stmt_all_grades = $conn->prepare("
    SELECT g.sID, s.subject_name, g.grade_value, g.grade_date, at.weight_factor
    FROM grades g
    INNER JOIN assessmenttype at ON g.aID = at.aID
    INNER JOIN subjects s ON g.sID = s.sID
    WHERE g.uID = ?
    ORDER BY g.grade_date ASC
");
if ($stmt_all_grades) {
    $stmt_all_grades->bind_param("i", $uID);
    $stmt_all_grades->execute();
    $result_all_grades = $stmt_all_grades->get_result();
    while ($row = $result_all_grades->fetch_assoc()) {
        $all_grades_data[] = $row;
    }
    $stmt_all_grades->close();
}

$average_since_first_grade = calculateWeightedAverage($all_grades_data);

$grades_last_3_months = array_filter($all_grades_data, function ($grade) use ($three_months_ago, $current_date) {
    return $grade['grade_date'] >= $three_months_ago && $grade['grade_date'] <= $current_date;
});
$average_last_3_months = calculateWeightedAverage($grades_last_3_months);

$current_overall_average = $average_since_first_grade;

$subject_averages = [];
$grades_by_subject = [];

foreach ($all_grades_data as $grade) {
    $grades_by_subject[$grade['sID']]['name'] = $grade['subject_name'];
    $grades_by_subject[$grade['sID']]['grades'][] = $grade;
}

foreach ($grades_by_subject as $sID => $subject_data) {
    $subject_averages[$sID] = [
        'name' => $subject_data['name'],
        'average' => calculateWeightedAverage($subject_data['grades'])
    ];
}

?>
    <link rel="stylesheet" href="/GradeLens/style/defaultview.css">
    <meta charset="UTF-8">
    <section id="defaultbody">
        <section id="graphside">
            <section id="graphcontainer">
            </section>
        </section>
        <section id="quickviewside">
            <section id="quickviewcontainer">
                <section id="quickviewtitle">
                    GRADE POINT AVERAGE
                </section>
                <section id="quickviewcontent">
                    <section id="quickviewscrollcontainer">
                        <div id="quickviewtext">
                            Average (All Time): <?php echo $current_overall_average !== null ? htmlspecialchars($current_overall_average) : 'N/A'; ?>
                        </div>
                        <div id="quickviewseperator"></div>
                        <div id="quickviewtext">
                            Average (Last 3 Months): <?php echo $average_last_3_months !== null ? htmlspecialchars($average_last_3_months) : 'N/A'; ?>
                        </div>
                        <p id="middletext">SUBJECT AVERAGES</p>
                        <?php if (!empty($subject_averages)): ?>
                            <?php foreach ($subject_averages as $subject_info): ?>
                                <div id="quickviewtext">
                                    <?php echo htmlspecialchars($subject_info['name']); ?>: <?php echo $subject_info['average'] !== null ? htmlspecialchars($subject_info['average']) : 'N/A'; ?>
                                </div>
                                <div id="quickviewseperator"></div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div id="quickviewtext">
                                <p>No subject grades available to calculate averages.</p>
                            </div>
                            <div id="quickviewseperator"></div>
                        <?php endif; ?>
                    </section>
                </section>
            </section>
        </section>
    </section>
<?php
include '../../api/disconnectdatabase.php';
?>