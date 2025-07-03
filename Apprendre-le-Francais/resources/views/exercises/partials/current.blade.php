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
        <form id="exercise-form">
            <div class="mb-4">
                @foreach ($questions as $index => $question)
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-body">
                            <h5 class="fw-bold text-dark mb-3">Question {{ $index + 1 }}</h5>
                            <p class="mb-4">{{ $question->texte }}</p>

                            @if ($question->format_reponse === 'choix_multiple')
                                <div class="vstack gap-3">
                                    @foreach ($question->choix as $key => $choice)
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio"
                                                name="answer[{{ $question->id }}]"
                                                id="choice-{{ $question->id }}-{{ $key }}"
                                                value="{{ $choice }}">
                                            <label class="form-check-label"
                                                for="choice-{{ $question->id }}-{{ $key }}">
                                                {{ $choice }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="mb-3">
                                    <input type="text" class="form-control form-control-lg rounded-pill"
                                        name="answer[{{ $question->id }}]" placeholder="Tapez votre réponse ici...">
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-purple btn-lg rounded-pill px-5">
                    <i class="fas fa-paper-plane me-2"></i>Soumettre mes réponses
                </button>
            </div>
        </form>
    </div>
</div>
