<nav id="navi">
    <a id="navi-logo"><img src="../source/project/logo_gradelens_dark_transparent.webp" alt="Logo" width="50px" height="50px"></a>
    <div id="button-container">
        <button id="home-button">
            Home
            <span class="arrow">&gt;</span>
        </button>
        <button id="library-button">
            Library
            <span class="arrow">&gt;</span>
        </button>
        <button id="create-button">
            Create
            <span class="arrow">&gt;</span>
        </button>
    </div>
</nav>

<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['uID'])) {
    header("Location: /GradeLens/sites/login.php");
    exit();
}
?>