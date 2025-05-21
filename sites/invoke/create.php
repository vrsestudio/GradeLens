<link rel="stylesheet" href="/GradeLens/style/create.css">
<section id="createcontainer">
    <div id="buffer"></div>
    <section id="createoptioncontainer">
        <a id="createoptionmain" href="/GradeLens/sites/invoke/create/grade.php">
            <div id="createoptionbody">
                <p id="createoptiontext">Add a grade you received in a subject</p>
            </div>
            <div id="createoptionbutton">
                ADD
            </div>
        </a>
    </section>
    <section id="createoptioncontainer">
        <a id="createoptionmain" href="/GradeLens/sites/invoke/create/subject.php">
            <div id="createoptionbody">
                <p id="createoptiontext">Create a new subject that you have at school</p>
            </div>
            <div id="createoptionbutton">
                CREATE
            </div>
        </a>
    </section>
    <section id="createoptioncontainer">
        <a id="createoptionmain" href="/GradeLens/sites/invoke/create/assessment.php">
            <div id="createoptionbody">
                <p id="createoptiontext">Create a new assessment type</p>
            </div>
            <div id="createoptionbutton">
                CREATE
            </div>
        </a>
    </section>
</section>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
?>