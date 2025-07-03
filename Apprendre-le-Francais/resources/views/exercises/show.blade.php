@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-lg border-0 rounded-3 overflow-hidden mb-4">
            <div class="card-header bg-purple bg-opacity-10 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold text-purple">{{ $exercise->lesson->title }}</h4>
                    <span class="badge bg-white text-purple border border-purple rounded-pill py-2 px-3">
                        <i class="fas fa-question-circle me-1 text-purple"></i>
                        <span id="question-counter">1</span>/{{ $questions->count() }}
                    </span>
                </div>
            </div>

            <div class="card-body bg-light">
                <div class="progress mb-4 rounded-pill" style="height: 12px;">
                    <div class="progress-bar bg-purple" id="exercise-progress" role="progressbar" style="width: 0%"></div>
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
        </div>

        <div class="card-footer bg-light py-4 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('exercises.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>

                @if ($questions->count() < $exercise->questions->count())
                    <a href="{{ route('exercises.show', ['exercise' => $exercise, 'retry_all' => 1]) }}"
                        class="btn btn-warning rounded-pill px-4">
                        <i class="fas fa-sync-alt me-2"></i>Tout refaire
                    </a>
                @endif

                <button type="submit" class="btn btn-purple rounded-pill px-4">
                    Soumettre <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </div>
        </div>
    </div>

    @if ($existingResults->isNotEmpty())
        <div class="card border-success mb-4">
            <div class="card-header bg-success bg-opacity-10 text-success d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <h5 class="mb-0 fw-bold">Questions déjà réussies</h5>
            </div>
            <div class="card-body">
                @foreach ($exercise->questions as $question)
                    @if ($existingResults->has($question->id))
                        <div class="border-start border-success border-3 ps-3 mb-3">
                            <p class="fw-medium mb-1">
                                Question {{ $loop->iteration }}: {{ $question->texte }}
                            </p>
                            <div class="d-flex align-items-center text-success">
                                <i class="fas fa-check me-2"></i>
                                <span>Réussie - Votre réponse: {{ $existingResults[$question->id]->reponse }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
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
