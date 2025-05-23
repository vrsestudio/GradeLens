<script src="/gradelens/script/profile.js" defer></script>
<link rel="stylesheet" href="/gradelens/style/navigation.css">
<section id="naviagtioncontainer">
<section id="navilogo">
    <img id="navilogo-image" src="/gradelens/source/project/logo_gradelens_dark_transparent.webp">
</section>
<section id="navibuttons">
    <button id="homebutton">HOME</button>
    <button id="librarybutton">LIBRARY</button>
    <button id="createbutton">ADD</button>
    <button id="profilebutton"><img id="profilebutton-image" src="/GradeLens/source/buttons/profile_button_dark.webp"></button>
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