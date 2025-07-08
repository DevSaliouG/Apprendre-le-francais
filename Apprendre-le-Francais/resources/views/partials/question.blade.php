@php
    $currentIndex = $currentIndex ?? 0;
    $totalQuestions = $totalQuestions ?? 0;
    $questionNumber = $currentIndex + 1;
@endphp


<div class="question-container" data-question-id="{{ $question->id }}">
    <div class="d-flex align-items-center mb-4">
        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-circle d-flex align-items-center justify-content-center"
            style="width: 50px; height: 50px; font-size: 1.25rem; font-weight: bold;">
            {{ $questionNumber }}
        </div>
        <div class="ms-3">
            <h5 class="mb-0">Question {{ $questionNumber }} sur {{ $totalQuestions }}</h5>
            <p class="mb-0 text-muted">{{ $exercise->difficulty }} • {{ $question->format_reponse_label }}</p>
        </div>
        @if ($question->fichier_audio)
            <button type="button" class="btn btn-purple ms-auto play-question"
                data-question-text="{{ $question->texte }}">
                <i class="fas fa-volume-up me-1"></i> Écouter
            </button>
        @endif
    </div>

    <div class="card question-card border-0 shadow-sm mb-4">
        <div class="card-body">
            <p id="question-text-{{ $question->id }}" class="lead fw-medium mb-4 question-text-hover"
                data-text="{{ $question->texte }}" style="cursor: pointer;">
                {{ $question->texte }}
                <span class="tts-indicator ms-2 d-none">
                    <i class="fas fa-volume-up text-purple"></i>
                </span>
            </p>

            @if ($question->fichier_audio)
                <div class="mb-4">
                    <div class="d-flex align-items-center text-purple mb-2">
                        <i class="fas fa-headphones me-2"></i>
                        <span class="fw-medium">Fichier audio :</span>
                    </div>
                    <audio controls class="w-100 rounded shadow-sm">
                        <source src="{{ Storage::url($question->fichier_audio) }}" type="audio/mpeg">
                    </audio>
                </div>
            @endif

            <form id="question-form" data-question-id="{{ $question->id }}">
                @csrf

                @if ($question->format_reponse === 'choix_multiple')
                    <div class="mb-4">
                        <div class="row g-3">
                            @foreach ($question->clean_choix as $key => $choice)
                                <div class="col-md-6">
                                    <div class="answer-option p-3 rounded shadow-sm"
                                        data-question-id="{{ $question->id }}"
                                        data-radio-id="choice_{{ $question->id }}_{{ $key }}">
                                        <div class="form-check d-flex align-items-center mb-0">
                                            <input type="radio" name="reponse" value="{{ $choice }}"
                                                class="form-check-input me-3"
                                                id="choice_{{ $question->id }}_{{ $key }}">
                                            <label for="choice_{{ $question->id }}_{{ $key }}"
                                                class="form-check-label fs-5 flex-grow-1">
                                                {{ $choice }}
                                            </label>
                                            <div class="check-icon">
                                                <i class="fas fa-check-circle text-purple opacity-0"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @elseif($question->format_reponse === 'texte_libre')
                    <div class="mb-4">
                        <div class="input-group">
                            <textarea name="reponse" rows="3"
                                class="form-control border border-2 border-purple border-opacity-25 rounded-start"
                                placeholder="Écrivez votre réponse ici...">{{ $userResult->reponse ?? '' }}</textarea>
                            <button type="button" class="btn btn-purple start-recording"
                                data-question-id="{{ $question->id }}">
                                <i class="fas fa-microphone"></i>
                            </button>
                        </div>
                    </div>
                @elseif($question->format_reponse === 'texte_libre')
                    <!-- ... code existant ... -->
                @elseif($question->format_reponse === 'audio')
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="d-flex align-items-center text-purple">
                                <i class="fas fa-microphone-alt me-2"></i>
                                <span class="fw-medium">Enregistrez votre réponse</span>
                            </div>
                            <button type="button" class="btn btn-sm btn-purple" onclick="startSpeechRecognition(this)"
                                data-question-id="{{ $question->id }}">
                                <i class="fas fa-record-vinyl me-1"></i>
                                <span id="recording-status-{{ $question->id }}">Commencer la reconnaissance</span>
                            </button>
                        </div>

                        <div
                            class="recording-container text-center p-4 rounded bg-light border border-purple border-opacity-25">
                            <div id="recognition-result-{{ $question->id }}" class="mb-3 p-2 bg-white rounded">
                                <!-- La transcription apparaîtra ici -->
                            </div>
                            <p class="recording-status mb-0 text-muted" id="recording-status-text-{{ $question->id }}">
                                Cliquez sur le bouton puis parlez
                            </p>
                        </div>
                        <input type="hidden" name="reponse" id="audio-response-{{ $question->id }}">
                    </div>
                @endif

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-purple rounded-pill px-5 shadow-sm pulse-animation">
                        <i class="fas fa-paper-plane me-2"></i> Valider la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .answer-option {
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .answer-option:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: #6f42c1;
    }

    .answer-option.selected {
        border-color: #6f42c1;
        background-color: rgba(111, 66, 193, 0.05);
        box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
    }

    .answer-option.selected .check-icon i {
        opacity: 1 !important;
    }

    .form-check-input:checked {
        background-color: #6f42c1;
        border-color: #6f42c1;
    }

    .question-text-hover:hover {
        background-color: rgba(111, 66, 193, 0.05);
        border-left: 3px solid #6f42c1;
        padding-left: 10px;
        transition: all 0.3s ease;
    }

    .tts-indicator {
        opacity: 0.7;
        font-size: 0.8em;
    }

    .recording-container {
        transition: all 0.3s ease;
    }

    .recording-active {
        background-color: rgba(255, 0, 0, 0.05);
        border-color: #dc3545 !important;
        box-shadow: 0 0 10px rgba(220, 53, 69, 0.2);
    }

    .recording-visualizer {
        background: repeating-linear-gradient(45deg,
                rgba(111, 66, 193, 0.1),
                rgba(111, 66, 193, 0.1) 10px,
                rgba(111, 66, 193, 0.2) 10px,
                rgba(111, 66, 193, 0.2) 20px);
        border-radius: 5px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuration de la synthèse vocale
        const synth = window.speechSynthesis;
        let voices = [];

        // Charger les voix disponibles
        function loadVoices() {
            voices = synth.getVoices();
            const frenchVoices = voices.filter(voice => voice.lang.startsWith('fr'));

            // Trouver une voix française naturelle
            const preferredVoice = frenchVoices.find(voice =>
                voice.name.includes('Google français') ||
                voice.name.includes('French')
            );

            return preferredVoice || frenchVoices[0] || null;
        }

        // Initialiser les voix
        if (synth.onvoiceschanged !== undefined) {
            synth.onvoiceschanged = loadVoices;
        }

        // Fonction pour lire le texte
        function speakText(text) {
            if (synth.speaking) {
                synth.cancel();
            }

            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'fr-FR';
            utterance.rate = 1.0;
            utterance.pitch = 1.0;

            const frenchVoice = loadVoices();
            if (frenchVoice) {
                utterance.voice = frenchVoice;
            }

            synth.speak(utterance);
        }

        // Activer la lecture au survol
        const questionText = document.getElementById('question-text-{{ $question->id }}');
        const ttsIndicator = questionText.querySelector('.tts-indicator');

        questionText.addEventListener('mouseenter', function() {
            ttsIndicator.classList.remove('d-none');
            speakText(this.dataset.text);
        });

        questionText.addEventListener('mouseleave', function() {
            ttsIndicator.classList.add('d-none');
            synth.cancel();
        });

        // Lecture automatique pour les exercices oraux
        @if ($exercise->type === 'oral')
            setTimeout(() => {
                const questionText = document.getElementById('question-text-{{ $question->id }}');
                if (questionText) {
                    speakText(questionText.dataset.text);
                }
            }, 500);
        @endif

        // Enregistrement audio
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        let recognition;

        if (SpeechRecognition) {
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.lang = 'fr-FR';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;
        }

        // Fonction pour démarrer la reconnaissance vocale
        window.startSpeechRecognition = function(button) {
            if (!recognition) {
                alert("La reconnaissance vocale n'est pas supportée par votre navigateur");
                return;
            }

            const questionId = button.dataset.questionId;
            const statusEl = document.getElementById(`recording-status-${questionId}`);
            const statusTextEl = document.getElementById(`recording-status-text-${questionId}`);

            statusEl.innerHTML = '<i class="fas fa-circle-notch fa-spin me-1"></i> Écoute...';
            statusTextEl.textContent = 'Parlez maintenant...';

            recognition.start();

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                document.getElementById(`audio-response-${questionId}`).value = transcript;

                // Afficher la transcription
                document.getElementById(`recognition-result-${questionId}`).textContent = transcript;

                statusEl.innerHTML = '<i class="fas fa-check-circle me-1"></i> Terminé';
                statusTextEl.textContent = 'Transcription réussie';
            };

            recognition.onerror = function(event) {
                console.error('Erreur de reconnaissance:', event.error);
                statusEl.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Erreur';
                statusTextEl.textContent = 'Problème de reconnaissance: ' + event.error;
            };
        };
    });
</script>
