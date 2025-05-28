// GradeLens/script/dynamicnavigation.js
document.addEventListener("DOMContentLoaded", () => {
    const homeButton = document.getElementById("homebutton");
    const libraryButton = document.getElementById("librarybutton");
    const createButton = document.getElementById("createbutton");
    const contentPane = document.getElementById("dynamiccontentpane");

    const updateContentAndButton = (button, file) => {

        homeButton.classList.remove("active-button");
        libraryButton.classList.remove("active-button");
        createButton.classList.remove("active-button");

        if (button) {
            button.classList.add("active-button");
        }

        fetch(`/GradeLens/sites/invoke/${file}`)
            .then(response => response.text())
            .then(data => {
                contentPane.innerHTML = data;

                const newUrl = `${window.location.origin}${window.location.pathname}?content=${file}`;
                history.pushState(null, "", newUrl);
            })
            .catch(error => console.error("Fehler beim Laden der Datei:", error));
    };

    const urlParams = new URLSearchParams(window.location.search);
    const contentToLoad = urlParams.get('content');

    if (contentToLoad) {
        let activeButton = null;
        if (contentToLoad === "defaultview.php") {
            activeButton = homeButton;
        } else if (contentToLoad === "library.php") {
            activeButton = libraryButton;
        } else if (contentToLoad === "manage.php") {
            activeButton = createButton;
        }
        updateContentAndButton(activeButton, contentToLoad);
    } else {
        updateContentAndButton(homeButton, "defaultview.php");
    }

    homeButton.addEventListener("click", () => updateContentAndButton(homeButton, "defaultview.php"));
    libraryButton.addEventListener("click", () => updateContentAndButton(libraryButton, "library.php"));
    createButton.addEventListener("click", () => updateContentAndButton(createButton, "manage.php"));
});