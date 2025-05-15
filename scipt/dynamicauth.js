document.addEventListener("DOMContentLoaded", () => {
    const bottomSection = document.getElementById("bottom");
    const loginButton = document.getElementById("loginbutton");
    const signupButton = document.getElementById("signupbutton");

    // Function to set the active button
    const setActiveButton = (activeButton) => {
        document.querySelectorAll("#authnavi button").forEach(button => {
            button.classList.remove("active");
        });
        activeButton.classList.add("active");
    };

    // Function to load content dynamically
    const loadContent = (url, activeButton) => {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                bottomSection.innerHTML = html;
                setActiveButton(activeButton);
            })
            .catch(error => console.error("Fehler beim Laden:", error));
    };

    // Load Login.php by default
    loadContent("./invoke/login.php", loginButton);

    // Event-Listener for buttons
    loginButton.addEventListener("click", () => loadContent("./invoke/login.php", loginButton));
    signupButton.addEventListener("click", () => loadContent("./invoke/signup.php", signupButton));
});