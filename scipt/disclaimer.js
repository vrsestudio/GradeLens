document.addEventListener("DOMContentLoaded", () => {
    const disclaimerButton = document.getElementById("authdisclaimer");

    disclaimerButton.addEventListener("click", () => {
        fetch("../disclaimer.html")
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.text();
            })
            .then(html => {
                // Disclaimer-Overlay erstellen
                const overlay = document.createElement("div");
                overlay.id = "disclaimerOverlay";
                overlay.innerHTML = html;

                // Schließen-Button hinzufügen
                const closeButton = document.createElement("button");
                closeButton.id = "closeDisclaimer";
                closeButton.textContent = "Close";
                closeButton.addEventListener("click", () => {
                    document.body.removeChild(overlay);
                });

                overlay.appendChild(closeButton);
                document.body.appendChild(overlay);
            })
            .catch(error => console.error("Fehler beim Laden von disclaimer.html:", error));
    });
});