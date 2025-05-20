<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="/GradeLens/style/overview.css">
</head>
<body id="overviewbackground">
<section id="overviewmaincontainer">
    <?php include './invoke/navigation.php'; ?>
    <section id="dynamiccontentpane">
    </section>
</section>
<script src="/GradeLens/script/dynamicnavigation.js"></script> </body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
?>
</html>