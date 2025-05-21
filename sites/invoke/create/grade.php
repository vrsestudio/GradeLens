<link rel="stylesheet" href="/gradelens/style/grade.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; ?>
    <section id="creationcontainer">
        <p id="title">ADD A GRADE</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">In which subject did you achieve the grade?</p>
                <select id="select" title="Subject">
                    <option id="option" value="">English</option>
                    <option id="option" value="">Math</option>
                    <option id="option" value="">German</option>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What weighting does the grade have?</p>
                <select id="select" title="Subject">
                    <option id="option" value="">1</option>
                    <option id="option" value="">2</option>
                    <option id="option" value="">3</option>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What was the topic of the assessment?</p>
                <input type="text" id="select" placeholder="Enter the topic">
            </section>
            <section id="selectdiscriptioncontainer">
                <p id="selectdiscriptiontitle">Briefly describe the assessment.</p>
                <input type="text" id="selectdiscription" placeholder="Enter the description">
            </section>
            <button id="addbutton" type="submit">Add Grade</button>
        </form>
    </section>
</section>
</body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
?>