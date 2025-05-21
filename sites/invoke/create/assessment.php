<link rel="stylesheet" href="/gradelens/style/assessment.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; ?>
    <section id="creationcontainer">
        <p id="title">CREATE AN ASSESSMENT</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">How high is the multiplier?</p>
                <select id="select" title="Subject">
                    <option id="option" value="">0.5</option>
                    <option id="option" value="">1</option>
                    <option id="option" value="">1.5</option>
                    <option id="option" value="">2</option>
                    <option id="option" value="">2.5</option>
                    <option id="option" value="">3</option>
                </select>
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">What is the assessment called?</p>
                <input type="text" id="select" placeholder="Enter the name of the assessment">
            </section>
            <section id="selectdiscriptioncontainer">
                <p id="selectdiscriptiontitle">Briefly describe this type of assessment.</p>
                <input type="text" id="selectdiscription" placeholder="Enter the description">
            </section>
            <button id="addbutton" type="submit">Create Assessment</button>
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