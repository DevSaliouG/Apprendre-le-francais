<div class="card shadow-lg border-0 rounded-3 overflow-hidden">
    <div class="card-header bg-purple bg-opacity-10 py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-purple">{{ $exercise->lesson->title }}</h4>
            <span class="badge bg-white text-purple border border-purple rounded-pill py-2 px-3">
                <i class="fas fa-question-circle me-1 text-purple"></i>
                Exercice en cours
            </span>
        </div>
    </div>

    <div class="card-body bg-light">
        <div class="mb-4">
            @foreach ($questions as $index => $question)
                <form class="question-form" data-question-id="{{ $question->id }}">
                    @csrf
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold text-dark mb-0">Question {{ $index + 1 }}</h5>
                                <!-- Bouton Lecture Vocale -->
                                <button type="button" class="btn btn-sm btn-outline-purple play-question"
                                        data-question-text="{{ $question->texte }}">
                                    <i class="fas fa-volume-up"></i>
                                </button>
                            </div>
                            <p class="mb-4">{{ $question->texte }}</p>

                            @if ($question->format_reponse === 'choix_multiple')
                                <div class="vstack gap-3 mb-3">
                                    @foreach ($question->choix as $key => $choice)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                   name="answer"
                                                   id="choice-{{ $question->id }}-{{ $key }}"
                                                   value="{{ $choice }}">
                                            <label class="form-check-label"
                                                   for="choice-{{ $question->id }}-{{ $key }}">
                                                {{ $choice }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($question->format_reponse === 'texte_libre')
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control form-control-lg rounded-pill"
                                           name="answer" 
                                           id="reponse-{{ $question->id }}"
                                           placeholder="Tapez votre réponse ici...">
                                    <!-- Bouton Reconnaissance Vocale -->
                                    <button type="button" 
                                            class="btn btn-outline-purple start-recording"
                                            data-question-id="{{ $question->id }}">
                                        <i class="fas fa-microphone"></i>
                                    </button>
                                </div>
                            @elseif ($question->format_reponse === 'audio')
                                <div class="d-flex align-items-center mb-3">
                                    <button type="button" 
                                            class="btn btn-purple start-recording me-3"
                                            data-question-id="{{ $question->id }}">
                                        <i class="fas fa-microphone me-2"></i> Enregistrer réponse
                                    </button>
                                    <div class="recording-status" id="status-{{ $question->id }}">
                                        <span class="badge badge-light">Prêt</span>
                                    </div>
                                </div>

                                <div class="mt-3" id="recording-feedback-{{ $question->id }}">
                                    <div class="pulse-animation d-none">
                                        <span class="recording-dot"></span> En cours d'enregistrement...
                                    </div>
                                </div>

                                <input type="hidden" 
                                       name="answer" 
                                       id="transcription-{{ $question->id }}">
                            @endif

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-purple rounded-pill px-4">
                                    <i class="fas fa-check-circle me-2"></i>Valider la réponse
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</div>

<style>
    .recording-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        background-color: red;
        border-radius: 50%;
        margin-right: 8px;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .pulse-animation {
        color: #dc3545;
        font-weight: bold;
    }
</style>
