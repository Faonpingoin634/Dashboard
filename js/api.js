
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
        if (!response.ok) throw new Error("Erreur écriture");
        return await response.json();
    } catch (error) {
        console.error("Erreur Save:", error);
        throw error;
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