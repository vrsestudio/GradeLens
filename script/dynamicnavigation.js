document.addEventListener("DOMContentLoaded", () => {
    const homeButton = document.getElementById("homebutton");
    const libraryButton = document.getElementById("librarybutton");
    const createButton = document.getElementById("createbutton");
    const contentPane = document.getElementById("dynamiccontentpane");

    const updateContentAndButton = (button, file) => {
        // Alle Buttons zurücksetzen
        homeButton.classList.remove("active-button");
        libraryButton.classList.remove("active-button");
        createButton.classList.remove("active-button");

        // Set active button
        button.classList.add("active-button");

        // Invoke Content Pane
        fetch(`./invoke/${file}`)
            .then(response => response.text())
            .then(data => {
                contentPane.innerHTML = data;
            })
            .catch(error => console.error("Fehler beim Laden der Datei:", error));
    };

    // Load Home by default
    updateContentAndButton(homeButton, "defaultview.php");

    // Event-Listener for buttons
    homeButton.addEventListener("click", () => updateContentAndButton(homeButton, "defaultview.php"));
    libraryButton.addEventListener("click", () => updateContentAndButton(libraryButton, "library.php"));
    createButton.addEventListener("click", () => updateContentAndButton(createButton, "create.php"));
});