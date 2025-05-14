<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Overview</title>
    <link rel="stylesheet" href="../style/overview.css">
    <link rel="stylesheet" href="../style/navigation.css">
    <link rel="stylesheet" href="../style/defaultview.css">
    <script src="../scipt/dynamiccontent.js" defer></script>
</head>

<body id="dynamiccontentpane">
    <section>
            <?php include "./invoke/navigation.php"; ?>
    </section>
    <section>
            <?php include "./invoke/defaultview.php"; ?>
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

</body>
</html>
