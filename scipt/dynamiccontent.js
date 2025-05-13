document.addEventListener("DOMContentLoaded", () => {
    const homeButton = document.getElementById("home-button");
    const libraryButton = document.getElementById("library-button");
    const createButton = document.getElementById("create-button");
    const defaultView = document.getElementById("defaultview");

    homeButton.addEventListener("click", () => {
        fetch("./invoke/defaultview.php")
            .then(response => response.text())
            .then(html => {
                defaultView.innerHTML = html;
            })
            .catch(error => console.error("Error loading create.php:", error));
    });

    libraryButton.addEventListener("click", () => {
        fetch("./invoke/library.php")
            .then(response => response.text())
            .then(html => {
                defaultView.innerHTML = html;
            })
            .catch(error => console.error("Error loading create.php:", error));
    });

    createButton.addEventListener("click", () => {
        fetch("./invoke/create.php")
            .then(response => response.text())
            .then(html => {
                defaultView.innerHTML = html;
            })
            .catch(error => console.error("Error loading create.php:", error));
    });
});