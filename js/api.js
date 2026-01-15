
const API_URL = "api.php";
const MAIL_URL = "send_mail.php";

export async function fetchAllData() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) throw new Error("Erreur réseau");
        return await response.json();
    } catch (error) {
        console.error("Erreur API:", error);
        throw error;
    }
}

export async function saveDataToServer(projects, tasks) {
    try {
        const response = await fetch(API_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ projects, tasks }),
        });

        // Cas 1 : Le serveur a répondu, mais avec une erreur (ex: 500 ou 400)
        if (!response.ok) {
            try {
                const errorData = await response.json();
                throw new Error(errorData.message || `Erreur serveur (${response.status})`);
            } catch (jsonError) {
                throw new Error(`Erreur HTTP: ${response.status}`);
            }
        }

        return await response.json();

    } catch (error) {
        // Cas 2 : Problème technique (Réseau coupé, DNS)
        if (error.message.includes("Erreur serveur") || error.message.includes("Erreur HTTP")) {
            console.error("Erreur API:", error);
            throw error; 
        }

        console.error("Erreur Réseau:", error);
        throw new Error("Connexion perdue. Vérifiez votre internet.");
    }
}

export async function sendSupportEmail(message) {
    const response = await fetch(MAIL_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ message }),
    });
    return await response.json();
}