@extends('layouts.app')

@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Question {{ $current_index }} sur {{ $question_count }}
                        <div class="float-end">{{ $progress }}% complété</div>
                    </div>

                    <div class="card-body">
                        <div class="progress mb-4">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;"
                                aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <h5 class="card-title mb-4">{{ $question->texte }}</h5>

                        @if ($question->exercise->type === 'écrit')
                            <form method="POST" action="{{ route('test.submitAnswer') }}">
                                @csrf
                                
                                @if($question->format_reponse === 'choix_multiple')
                                    <!-- Affichage des options pour choix multiple -->
                                    @foreach ($question->clean_choix as $key => $option)
                                        <div class="form-check my-2 option-card">
                                            <input class="form-check-input" type="radio" name="reponse"
                                                id="option{{ $key }}" value="{{ $option }}" required>
                                            <label class="form-check-label" for="option{{ $key }}">
                                                {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <!-- Champ texte pour réponse libre -->
                                    <div class="form-group">
                                        <label for="reponse" class="form-label">Votre réponse :</label>
                                        <input type="text" class="form-control" name="reponse" id="reponse" required>
                                    </div>
                                @endif
                                
                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="fas fa-arrow-right me-1"></i> Suivant
                                </button>
                            </form>
                        @else
                            <div class="mb-4">
                                <audio controls class="w-100">
                                    <source src="{{ asset('storage/' . $question->fichier_audio) }}" type="audio/mpeg">
                                    Votre navigateur ne supporte pas l'élément audio.
                                </audio>
                            </div>
                            <form method="POST" action="{{ route('test.submitAnswer') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="reponse" class="form-label">Votre réponse :</label>
                                    <input type="text" class="form-control" name="reponse" id="reponse" required>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="fas fa-arrow-right me-1"></i> Suivant
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.form-check-input').forEach(input => {
        input.addEventListener('change', function() {
            // Trouver la carte parente
            const card = this.closest('.option-card');
            if (card) {
                // Ajouter une classe pour l'animation
                card.classList.add('option-selected');
                
                // Enlever la classe après l'animation
                setTimeout(() => {
                    card.classList.remove('option-selected');
                }, 300);
            }
        });
    });
});
</script>

<style>
.option-card {
    transition: all 0.3s ease;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #e0e0e0;
}

.option-selected {
    background-color: #e6f7ff;
    box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.3);
    border-color: #4361ee;
    transform: scale(1.02);
}
</style>