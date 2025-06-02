<link rel="stylesheet" href="/gradelens/style/navigation.css">
<meta charset="UTF-8">
<section id="naviagtioncontainer">
<section id="navilogo">
    <img id="navilogo-image" src="/gradelens/source/project/logo_gradelens_dark_transparent.webp">
</section>
<section id="navibuttons">
    <button id="homebutton">HOME</button>
    <button id="librarybutton">LIBRARY</button>
    <button id="createbutton">MANAGE</button>
    <a id="profilebutton" href="/GradeLens/sites/login.php"><img id="profilebutton-image" src="/GradeLens/source/buttons/profile_button_dark.webp"></a>
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