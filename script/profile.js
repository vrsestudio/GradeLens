document.addEventListener("DOMContentLoaded", () => {
    const profileButton = document.getElementById("profilebutton");

    profileButton.addEventListener("click", () => {
        fetch("../api/endsession.php", { credentials: "include" })
            .then(() => window.location.replace("../api/loadlogin.php"));
    });
});