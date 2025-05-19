<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="../style/overview.css">
</head>
<body id="overviewbackground">
    <section id="overviewmaincontainer">
        <section id="naviagtioncontainer">
            <?php include './invoke/navigation.php'; ?>
        </section>
        <section id="dynamiccontentpane">
            <script src="../script/dynamicnavigation.js"></script>
        </section>
    </section>
</body>
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: ./login.php");
    exit();
}
?>