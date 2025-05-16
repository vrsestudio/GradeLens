document.addEventListener("DOMContentLoaded", () => {
    const bottomSection = document.getElementById("bottom");
    const loginButton = document.getElementById("loginbutton");
    const signupButton = document.getElementById("signupbutton");

    const loadContent = (url) => {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                bottomSection.innerHTML = html;
                updateButtonStyles(url);
            })
            .catch(error => console.error("Fehler beim Laden:", error));
    };

    const updateButtonStyles = (url) => {
        if (url.includes("login.php")) {
            loginButton.classList.add("active-button");
            signupButton.classList.remove("active-button");
        } else if (url.includes("signup.php")) {
            signupButton.classList.add("active-button");
            loginButton.classList.remove("active-button");
        }
    };

    loadContent("./invoke/login.php");

    loginButton.addEventListener("click", () => loadContent("./invoke/login.php"));
    signupButton.addEventListener("click", () => loadContent("./invoke/signup.php"));
});