<div id="libraryview">
    Library Content Pane
</div>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/authentication.php");
    exit();
}
?>