document.addEventListener("DOMContentLoaded", () => {
    const loginButton = document.getElementById("loginbutton");
    const signupButton = document.getElementById("signupbutton");

    loginButton.addEventListener("click", () => window.location.replace("login.php"));
    signupButton.addEventListener("click", () => window.location.replace("signup.php"));
});