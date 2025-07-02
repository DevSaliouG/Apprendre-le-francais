@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
        <div class="card-header bg-gradient-to-r from-purple-50 to-indigo-50 border-0 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 text-gray-800 font-bold">{{ $exercise->lesson->title }}</h4>
                <span class="badge rounded-pill bg-white text-gray-700 border border-gray-200 py-2 px-3">
                    <i class="fas fa-question-circle me-1 text-purple-500"></i>
                    <span id="question-counter">1</span>/{{ $questions->count() }}
                </span>
            </div>
        </div>

        <div class="card-body bg-gray-50">
            <div class="progress mb-5 h-3 rounded-full bg-gray-200 overflow-hidden">
                <div class="progress-bar bg-gradient-to-r from-purple-500 to-indigo-500 h-full" 
                     style="width: 0%" 
                     id="exercise-progress"></div>
            </div>

            <div id="question-container" class="question-card p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                @include('partials.question', [
                    'question' => $questions->first(),
                    'exercise' => $exercise,
                    'currentIndex' => 0,
                    'totalQuestions' => $questions->count()
                ])
            </div>
        </div>
    </div>
</div>
<div class="card-footer bg-gray-50 py-4 border-t border-gray-200">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('exercises.index') }}" class="btn btn-outline-secondary rounded-xl py-2 px-4 border-2">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
        
        @if($questions->count() < $exercise->questions->count())
        <a href="{{ route('exercises.show', ['exercise' => $exercise, 'retry_all' => 1]) }}" 
           class="btn btn-outline-warning rounded-xl py-2 px-4 border-2">
            <i class="fas fa-sync-alt me-2"></i>Tout refaire
        </a>
        @endif
        
        <button type="submit" class="btn bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-2 px-5 shadow-md hover:shadow-lg transition">
            Soumettre mes réponses <i class="fas fa-paper-plane ms-2"></i>
        </button>
    </div>
    @if($existingResults->isNotEmpty())
<div class="mb-6">
    <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
        <i class="fas fa-check-circle text-green-500"></i>
        Questions déjà réussies
    </h4>
    
    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
        @foreach($exercise->questions as $question)
            @if($existingResults->has($question->id))
            <div class="mb-3 last:mb-0 p-3 bg-white rounded-lg border border-green-100">
                <p class="font-medium text-gray-700 mb-1">
                    Question {{ $loop->iteration }}: {{ $question->texte }}
                </p>
                <div class="flex items-center gap-2 text-green-600">
                    <i class="fas fa-check"></i>
                    <span>Réussie - Votre réponse: {{ $existingResults[$question->id]->reponse }}</span>
                </div>
            </div>
            @endif
        @endforeach
    </div>
</div>
@endif

<div class="questions-container space-y-5">
    @foreach($questions as $index => $question)
        <!-- Contenu de la question (comme avant) -->
    @endforeach
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const questions = @json($questions);
    let currentIndex = 0;
    let answeredCount = 0;

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
            });
    }

    function showFeedback(data) {
        const feedback = data.correct ? 
            `<div class="alert alert-success mb-4">
                <i class="fas fa-check-circle me-2"></i> Correct !
            </div>` :
            `<div class="alert alert-danger mb-4">
                <i class="fas fa-times-circle me-2"></i> Incorrect. 
                Réponse : ${data.correction}
            </div>`;
        
        document.getElementById('question-container').innerHTML += feedback;
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
                       <p>Nouveau niveau : <strong>${response.new_level}</strong></p>`,
                icon: 'success',
                confirmButtonText: 'Continuer',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-2 px-5 shadow-md hover:shadow-lg transition'
                }
            }).then(() => {
                window.location.href = "{{ route('exercises.index') }}";
            });
        }
    }
});
</script>
@endsection