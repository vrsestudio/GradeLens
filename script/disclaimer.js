document.addEventListener("DOMContentLoaded", () => {
    const disclaimerButton = document.getElementById("authdisclaimer");

    disclaimerButton.addEventListener("click", () => {
        fetch("../disclaimer.html")
            .then((response) => {
                if (!response.ok) {
                    throw new Error("Fehler beim Laden des Disclaimers");
                }
                return response.text();
            })
            .then((html) => {
                const disclaimerContainer = document.createElement("div");
                disclaimerContainer.innerHTML = html;
                document.body.appendChild(disclaimerContainer);

                const disclaimer = document.getElementById("disclaimer");
                disclaimer.style.display = "block";

                const closeButton = document.getElementById("close-disclaimer");
                closeButton.addEventListener("click", () => {
                    disclaimer.style.display = "none";
                    disclaimerContainer.remove();
                });
            })
            .catch((error) => console.error("Fehler:", error));
    });
});