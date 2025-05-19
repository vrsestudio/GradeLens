document.addEventListener("DOMContentLoaded", () => {
    const profileButton = document.getElementById("profilebutton");

    profileButton.addEventListener("click", () => window.location.replace("../api/endsession.php"));
});