<link rel="stylesheet" href="/gradelens/style/subject.css">
<script src="/gradelens/script/staticnavigation.js" defer></script>
<body id="background">
<section id="maincontainer">
    <?php include '../navigation.php'; ?>
    <section id="creationcontainer">
        <p id="title">CREATE A SUBJECT</p>
        <form id="interactivecontainer" method="post">
            <section id="selectcontainer">
                <p id="selecttitle">What is the subject called?</p>
                <input type="text" id="select" placeholder="E.G. Math, Science, etc.">
            </section>
            <section id="selectcontainer">
                <p id="selecttitle">Is there an abbreviation for it?</p>
                <input type="text" id="select" placeholder="E.G. MATH, SCI, etc.">
            </section>
            <section id="selectdiscriptioncontainer">
                <p id="selectdiscriptiontitle">Briefly describe this subject.</p>
                <input type="text" id="selectdiscription" placeholder="Enter the description">
            </section>
            <button id="addbutton" type="submit">Create Subject</button>
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