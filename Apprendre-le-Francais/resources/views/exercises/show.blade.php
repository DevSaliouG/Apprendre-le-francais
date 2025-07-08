@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- Carte d'exercice -->
        <div class="card shadow-lg border-0 rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-purple bg-opacity-10 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold text-purple">{{ $exercise->lesson->title }}</h4>
                        <p class="mb-0 text-muted">Exercice de {{ $exercise->lesson->type }}</p>
                    </div>
                    <span class="badge bg-white text-purple border border-purple rounded-pill py-2 px-3">
                        <i class="fas fa-question-circle me-1"></i>
                        <span id="question-counter">1</span>/{{ $questions->count() }}
                    </span>
                </div>
            </div>

            <div class="card-body bg-light">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-circle p-2 me-3">
                            <i class="fas fa-brain text-white fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-0">Votre progression</h5>
                            <small class="text-muted">Continuez comme ça !</small>
                        </div>
                    </div>
                    <div class="progress rounded-pill" style="height: 12px; width: 60%;">
                        <div class="progress-bar bg-purple" id="exercise-progress" role="progressbar" style="width: 0%">
                        </div>
                    </div>
                </div>

                <div id="question-container" class="bg-white rounded-3 border p-4 shadow-sm">
                    @include('partials.question', [
                        'question' => $questions->first(),
                        'exercise' => $exercise,
                        'currentIndex' => 0,
                        'totalQuestions' => $questions->count(),
                    ])
                </div>
            </div>

            <div class="card-footer bg-light py-4 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('exercises.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="fas fa-arrow-left me-2"></i>Retour
                    </a>

                    <div class="d-flex gap-2">
                        @if ($questions->count() < $exercise->questions->count())
                            <a href="{{ route('exercises.show', ['exercise' => $exercise, 'retry_all' => 1]) }}"
                                class="btn btn-warning rounded-pill px-4">
                                <i class="fas fa-sync-alt me-2"></i>Tout refaire
                            </a>
                        @endif

                        <button type="button" class="btn btn-purple rounded-pill px-4">
                            Soumettre <i class="fas fa-paper-plane ms-2"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Résultats existants -->
        @if ($existingResults->isNotEmpty())
            <div class="card border-success mb-4 overflow-hidden">
                <div class="card-header bg-success bg-opacity-10 text-success d-flex align-items-center">
                    <div class="bg-success bg-opacity-20 p-2 rounded-circle me-3">
                        <i class="fas fa-check-circle fs-4"></i>
                    </div>
                    <h5 class="mb-0 fw-bold">Questions déjà réussies</h5>
                </div>
                <div class="card-body">
                    @foreach ($exercise->questions as $question)
                        @if ($existingResults->has($question->id))
                            <div
                                class="border-start border-success border-3 ps-4 py-3 mb-3 bg-success bg-opacity-5 rounded-end">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-white border border-success rounded-circle p-2 me-3">
                                        <span class="text-success fw-bold">{{ $loop->iteration }}</span>
                                    </div>
                                    <p class="fw-medium mb-0">
                                        {{ $question->texte }}
                                    </p>
                                </div>
                                <div class="d-flex align-items-center text-success ms-5">
                                    <i class="fas fa-check me-2"></i>
                                    <span>Réussie - Votre réponse: {{ $existingResults[$question->id]->reponse }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <style>
        .question-card {
            border-left: 4px solid #6f42c1;
            transition: all 0.3s ease;
        }

        .question-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(111, 66, 193, 0.1);
        }

        .answer-option {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 2px solid #e9ecef;
        }

        .answer-option:hover {
            border-color: #6f42c1;
            background-color: rgba(111, 66, 193, 0.05);
            transform: translateY(-3px);
        }

        .answer-option.selected {
            border-color: #6f42c1;
            background-color: rgba(111, 66, 193, 0.1);
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.2);
        }

        .pulse-animation {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        .bg-gradient-to-r {
            background: linear-gradient(to right, #6f42c1, #6610f2);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const questions = @json($questions);
            let currentIndex = 0;
            let answeredCount = 0;

            // Gestion de la sélection des réponses
            document.querySelectorAll('.answer-option').forEach(option => {
                option.addEventListener('click', function() {
                    // Désélectionner toutes les options pour cette question
                    const questionId = this.closest('.question-container').dataset.questionId;
                    document.querySelectorAll(`.answer-option[data-question-id="${questionId}"]`)
                        .forEach(opt => {
                            opt.classList.remove('selected');
                        });

                    // Sélectionner l'option actuelle
                    this.classList.add('selected');

                    // Mettre à jour la valeur du radio bouton
                    const radioId = this.dataset.radioId;
                    document.getElementById(radioId).checked = true;
                });
            });

            // Gestion de la soumission
            document.addEventListener('submit', function(e) {
                if (e.target.matches('#question-form')) {
                    e.preventDefault();
                    handleSubmit(e);
                }
            });

            function handleSubmit(e) {
                const form = e.target;
                const formData = new FormData(form);
                const questionId = form.dataset.questionId;

                // Animation de validation
                form.querySelector('button[type="submit"]').innerHTML =
                    '<i class="fas fa-spinner fa-spin me-2"></i> Validation...';

                fetch(`/exercises/{{ $exercise->id }}/questions/${questionId}/submit`, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        showFeedback(data);

                        // Vérifier si l'utilisateur a monté de niveau
                        if (data.level_up) {
                            setTimeout(() => {
                                handleLevelUp(data);
                            }, 1000);
                        }

                        // Charger la prochaine question ou rediriger
                        setTimeout(() => {
                            if (currentIndex < questions.length - 1) {
                                currentIndex++;
                                loadQuestion(questions[currentIndex]);
                            } else {
                                window.location.href = "{{ route('exercises.result', $exercise) }}";
                            }
                        }, 2000);
                    });
            }

            function loadQuestion(question) {
        fetch(`/questions/${question.id}/html`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('question-container').innerHTML = html;
                document.getElementById('question-counter').textContent = currentIndex + 1;
                updateProgress();
                
                // Réattacher les gestionnaires
                attachAnswerHandlers();
                attachVoiceHandlers();
                
                // Activer la lecture automatique après interaction utilisateur
                setTimeout(() => {
                    const questionText = document.querySelector('.question-text-hover');
                    if (questionText) {
                        speakText(questionText.dataset.text);
                        
                        // Afficher l'indicateur brièvement
                        const ttsIndicator = questionText.querySelector('.tts-indicator');
                        ttsIndicator.classList.remove('d-none');
                        setTimeout(() => ttsIndicator.classList.add('d-none'), 2000);
                    }
                }, 300);
            });
    }
            function showFeedback(data) {
                const form = document.getElementById('question-form');
                let feedback;

                if (data.correct) {
                    feedback = `
                <div class="alert alert-success border-0 shadow-sm pulse-animation">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle fa-2x me-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading">Correct !</h5>
                            <p class="mb-0">Bonne réponse, continuez comme ça !</p>
                        </div>
                    </div>
                </div>`;
                } else {
                    feedback = `
                <div class="alert alert-danger border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-times-circle fa-2x me-3"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="alert-heading">Incorrect</h5>
                            <p class="mb-0">Réponse attendue : <strong>${data.correction}</strong></p>
                        </div>
                    </div>
                </div>`;
                }

                form.insertAdjacentHTML('beforebegin', feedback);
            }

            function updateProgress() {
                const progress = ((currentIndex) / questions.length) * 100;
                document.getElementById('exercise-progress').style.width = `${progress}%`;
            }

            // Gestion de la promotion de niveau
            function handleLevelUp(response) {
                if (response.level_up) {
                    Swal.fire({
                        title: 'Félicitations !',
                        html: `<p>Vous avez complété le niveau avec succès !</p>
                   <p class="fw-bold">Nouveau niveau : ${response.new_level}</p>`,
                        icon: 'success',
                        confirmButtonText: 'Continuer',
                        customClass: {
                            popup: 'rounded-3',
                            confirmButton: 'btn-purple rounded-pill px-4 py-2'
                        },
                        background: 'linear-gradient(to bottom, #ffffff, #f8f9fa)',
                        backdrop: 'rgba(111, 66, 193, 0.2)'
                    }).then(() => {
                        window.location.href = "{{ route('exercises.index') }}";
                    });
                }
            }

            // Réattacher les gestionnaires vocaux
            function speakText(text) {
                if ('speechSynthesis' in window) {
                    // Arrêter toute parole en cours
                    window.speechSynthesis.cancel();

                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'fr-FR';
                    utterance.rate = 1.0;
                    utterance.pitch = 1.0;

                    // Trouver une voix féminine française si possible
                    const voices = speechSynthesis.getVoices();
                    const frenchVoice = voices.find(voice =>
                        voice.lang === 'fr-FR' && voice.name.includes('female')
                    );

                    if (frenchVoice) {
                        utterance.voice = frenchVoice;
                    }

                    window.speechSynthesis.speak(utterance);
                } else {
                    alert("Votre navigateur ne supporte pas la lecture vocale. Essayez avec Chrome ou Edge.");
                }
            }

            // Fonction pour démarrer l'enregistrement (Speech-to-Text)
            function startRecording(questionId, button, targetInputId) {
                const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

                if (!SpeechRecognition) {
                    alert("Reconnaissance vocale non supportée sur ce navigateur. Essayez avec Chrome ou Edge.");
                    return;
                }

                const recognition = new SpeechRecognition();
                recognition.lang = 'fr-FR';
                recognition.interimResults = false;
                recognition.maxAlternatives = 1;

                // Mise à jour UI - démarrage
                const statusElement = document.getElementById(`status-${questionId}`);
                const feedbackElement = document.getElementById(`recording-feedback-${questionId}`);
                const pulseElement = feedbackElement.querySelector('.pulse-animation');

                if (statusElement) {
                    statusElement.innerHTML = '<span class="badge bg-danger">Enregistrement</span>';
                }

                button.innerHTML = '<i class="fas fa-microphone-slash me-2"></i> Arrêter';
                button.classList.remove('btn-purple');
                button.classList.add('btn-danger');

                if (pulseElement) {
                    pulseElement.classList.remove('d-none');
                }

                // Démarrer l'enregistrement
                recognition.start();

                recognition.onresult = function(event) {
                    const transcript = event.results[0][0].transcript;
                    document.getElementById(targetInputId).value = transcript;

                    // Afficher la transcription pour les questions texte_libre
                    if (targetInputId.startsWith('text-response')) {
                        document.getElementById(targetInputId).value = transcript;
                    }
                };

                recognition.onerror = function(event) {
                    console.error('Erreur reconnaissance:', event.error);
                    if (statusElement) {
                        statusElement.innerHTML =
                        `<span class="badge bg-warning">Erreur: ${event.error}</span>`;
                    }
                };

                recognition.onend = function() {
                    // Réinitialiser UI
                    if (statusElement) {
                        statusElement.innerHTML = '<span class="badge bg-success">Terminé</span>';
                    }

                    button.innerHTML = '<i class="fas fa-microphone me-2"></i> Réessayer';
                    button.classList.remove('btn-danger');
                    button.classList.add('btn-purple');

                    if (pulseElement) {
                        pulseElement.classList.add('d-none');
                    }
                };

                // Arrêter l'enregistrement si on reclique
                button.onclick = function() {
                    recognition.stop();
                };
            }

            // Réattacher les gestionnaires vocaux
            function attachVoiceHandlers() {
                // Synthèse vocale
                document.querySelectorAll('.play-question').forEach(button => {
                    button.addEventListener('click', function() {
                        const text = this.getAttribute('data-question-text');
                        speakText(text);

                        // Animation pendant la lecture
                        this.innerHTML = '<i class="fas fa-volume-up fa-spin me-1"></i> Lecture...';
                        setTimeout(() => {
                            this.innerHTML =
                                '<i class="fas fa-volume-up me-1"></i> Réécouter';
                        }, 2000);
                    });
                });

                // Reconnaissance vocale
                document.querySelectorAll('.start-recording').forEach(button => {
                    button.addEventListener('click', function() {
                        const questionId = this.getAttribute('data-question-id');
                        const targetInputId = this.getAttribute('data-target-input');
                        startRecording(questionId, this, targetInputId);
                    });
                });
                document.querySelectorAll('.question-text-hover').forEach(element => {
                    element.addEventListener('mouseenter', function() {
                        speakText(this.dataset.text);
                    });
                });
            }

            // Réattacher les gestionnaires de réponses
            function attachAnswerHandlers() {
                document.querySelectorAll('.answer-option').forEach(option => {
                    option.addEventListener('click', function() {
                        const questionId = this.closest('.question-container').dataset.questionId;
                        document.querySelectorAll(
                            `.answer-option[data-question-id="${questionId}"]`).forEach(opt => {
                            opt.classList.remove('selected');
                        });
                        this.classList.add('selected');
                        const radioId = this.dataset.radioId;
                        document.getElementById(radioId).checked = true;
                    });
                });
            }

            // Initialisation
            attachAnswerHandlers();
            attachVoiceHandlers();

            // Lire automatiquement la question si c'est un exercice oral
            @if ($exercise->type === 'oral')
                setTimeout(() => {
                    const firstQuestionText = document.querySelector('.question-text-hover');
                    if (firstQuestionText) {
                        speakText(firstQuestionText.dataset.text);
                    }
                }, 1000);
            @endif
        });
    </script>
@endsection
