document.addEventListener("DOMContentLoaded", () => {
    const homeButton = document.getElementById("homebutton");
    const libraryButton = document.getElementById("librarybutton");
    const createButton = document.getElementById("createbutton");

    const redirectToOverview = (file) => {

        window.location.href = `/GradeLens/sites/overview.php?content=${file}`;
    };

    homeButton.addEventListener("click", () => redirectToOverview("defaultview.php"));
    libraryButton.addEventListener("click", () => redirectToOverview("library.php"));
    createButton.addEventListener("click", () => redirectToOverview("manage.php"));
});