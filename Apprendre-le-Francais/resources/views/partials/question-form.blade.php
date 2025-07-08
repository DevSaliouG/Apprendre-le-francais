<div class="question-card border-0 rounded-3 shadow-sm mb-4 overflow-hidden">
    <div class="card-header bg-light d-flex justify-content-between align-items-center py-3">
        <div class="d-flex align-items-center">
            <i class="fas fa-question-circle text-purple fs-4 me-3"></i>
            <h5 class="mb-0">Configuration de la question</h5>
        </div>
        <button type="button" onclick="removeQuestion(this)" class="btn btn-sm btn-outline-danger">
            <i class="fas fa-trash-alt me-1"></i> Supprimer
        </button>
    </div>

    <div class="card-body">
        @if($question)
            <input type="hidden" name="questions[{{ $question->id }}][id]" value="{{ $question->id }}">
        @else
            <input type="hidden" name="questions[__index__][new]" value="1">
        @endif

        <div class="mb-4">
            <label class="form-label fw-medium text-purple">Texte de la question</label>
            <textarea name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][texte]"
                      class="form-control border-purple border-opacity-25" rows="3" 
                      placeholder="Quelle est la capitale de la France ?" required>{{ $question->texte ?? '' }}</textarea>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium text-purple">Type de réponse</label>
                    <select class="form-select border-purple border-opacity-25">
                        <option>Choix multiple</option>
                        <option>Texte libre</option>
                        <option>Audio</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label fw-medium text-purple">Difficulté</label>
                    <select class="form-select border-purple border-opacity-25">
                        <option>Débutant</option>
                        <option>Intermédiaire</option>
                        <option>Avancé</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium text-purple">Options (pour choix multiple)</label>
            <textarea name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][choix]"
                      class="form-control border-purple border-opacity-25" rows="3"
                      placeholder="Entrez une option par ligne">@isset($question){{ implode("\n", $question->choix) }}@endisset</textarea>
        </div>

        <div class="mb-4">
            <label class="form-label fw-medium text-purple">Réponse correcte</label>
            <input type="text"
                   name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][reponse_correcte]"
                   value="{{ $question->reponse_correcte ?? '' }}"
                   class="form-control border-purple border-opacity-25" 
                   placeholder="Paris" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-medium text-purple">Fichier audio (optionnel)</label>
            <div class="input-group">
                <input type="file"
                       name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][fichier_audio]"
                       class="form-control border-purple border-opacity-25">
                <button class="btn btn-outline-purple" type="button">
                    <i class="fas fa-play me-1"></i> Préécouter
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .question-card {
        border: 1px solid rgba(111, 66, 193, 0.2);
        transition: all 0.3s ease;
    }
    
    .question-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(111, 66, 193, 0.1);
    }
    
    .form-control, .form-select {
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #6f42c1;
        box-shadow: 0 0 0 0.25rem rgba(111, 66, 193, 0.25);
    }
</style>

@section('scripts')
<script>
    // Lecture automatique du texte lors de la création de questions
    document.querySelectorAll('.play-question').forEach(button => {
        button.addEventListener('click', function() {
            const text = this.closest('.card-body')
                         .querySelector('textarea[name*="texte"]').value;
            
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.lang = 'fr-FR';
                window.speechSynthesis.speak(utterance);
            }
        });
    });
</script>
@endsection