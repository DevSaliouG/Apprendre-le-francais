import "bootstrap";
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

import Chart from "chart.js/auto";
window.Chart = Chart;

import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "../css/app.css";

/* function speakText(text) {
    if ("speechSynthesis" in window) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = "fr-FR"; // Langue française
        utterance.rate = 1.0; // Vitesse normale
        utterance.pitch = 1.0; // Hauteur normale

        // Sélectionne une voix féminine si disponible
        const voices = speechSynthesis.getVoices();
        const frenchVoice = voices.find(
            (voice) =>
                voice.lang.includes("fr") && voice.name.includes("female")
        );

        if (frenchVoice) utterance.voice = frenchVoice;

        window.speechSynthesis.speak(utterance);
    } else {
        alert("Votre navigateur ne supporte pas la lecture vocale.");
    }
}

// Utilisation
document.querySelectorAll(".play-question").forEach((button) => {
    button.addEventListener("click", () => {
        const questionText = button.dataset.questionText;
        speakText(questionText);
    });
});

//Gestion de la reconnaissance vocale
function setupSpeechRecognition(questionId) {
    const SpeechRecognition =
        window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert("Reconnaissance vocale non supportée sur ce navigateur.");
        return null;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "fr-FR";
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    return recognition;
}

// Utilisation
document.querySelectorAll(".start-recording").forEach((button) => {
    button.addEventListener("click", (e) => {
        const questionId = e.target.dataset.questionId;
        const recognition = setupSpeechRecognition(questionId);

        recognition.start();
        button.textContent = "Écoute...";

        recognition.onresult = (event) => {
            const transcript = event.results[0][0].transcript;
            document.getElementById(`reponse-${questionId}`).value = transcript;
        };

        recognition.onend = () => {
            button.textContent = "Parler 🎤";
        };
    });
});

// Ajouter un indicateur d'enregistrement
recognition.onstart = () => {
    button.innerHTML = '<span class="pulse">● Enregistrement</span>';
};

recognition.onerror = (event) => {
    console.error("Erreur reconnaissance:", event.error);
    button.textContent = "Erreur - Réessayer";
};
 */