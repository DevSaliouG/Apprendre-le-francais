@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-xl">
                <div class="card-header bg-gradient-to-r from-purple-50 to-indigo-50 border-0 text-center py-5">
                    <div class="icon-result mb-4">
                        @if($isSuccess)
                        <div class="bg-gradient-to-r from-green-100 to-teal-100 d-inline-flex p-4 rounded-full">
                            <i class="fas fa-check-circle fa-4x text-gradient-to-r from-green-500 to-teal-500"></i>
                        </div>
                        @else
                        <div class="bg-gradient-to-r from-pink-100 to-rose-100 d-inline-flex p-4 rounded-full">
                            <i class="fas fa-exclamation-circle fa-4x text-gradient-to-r from-pink-500 to-rose-500"></i>
                        </div>
                        @endif
                    </div>
                    <h3 class="mb-2 text-2xl font-bold text-gray-800">
                        {{ $isSuccess ? 'Félicitations !' : 'Presque réussi !' }}
                    </h3>
                    <p class="text-gray-600">
                        Vous avez terminé l'exercice "{{ $exercise->lesson->title }}"
                    </p>
                </div>

                <div class="card-body text-center py-5">
                    <div class="d-flex justify-content-center mb-5">
                        <div class="score-card bg-white p-5 rounded-2xl border-0 shadow-md text-center">
                            <h6 class="text-gray-600 mb-3">Votre score</h6>
                            <div class="score-circle mx-auto position-relative">
                                <div class="progress-circle" 
                                     data-percent="{{ $percentage }}"
                                     data-color="{{ $isSuccess ? '#6C63FF' : '#FF6584' }}"></div>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h2 class="mb-0 fw-bold text-3xl">{{ $percentage }}%</h2>
                                </div>
                            </div>
                            <p class="mt-4 mb-0 text-gray-600">
                                {{ $score }} bonnes réponses sur {{ $totalQuestions }} questions
                            </p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mb-5">
                        <div class="d-flex align-items-center bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-xl">
                            <div class="me-3">
                                <span class="badge rounded-pill py-2 px-3 bg-gradient-to-r {{ $isExerciseCompleted ? 'from-green-500 to-teal-500' : 'from-yellow-400 to-orange-400' }}">
                                    {{ $isExerciseCompleted ? 'Exercice complété' : 'Exercice en cours' }}
                                </span>
                            </div>
                            <div>
                                @if($isExerciseCompleted)
                                <span class="text-green-600 font-medium">
                                    <i class="fas fa-check-circle me-1"></i>Vous avez réussi cet exercice
                                </span>
                                @else
                                <span class="text-yellow-600 font-medium">
                                    <i class="fas fa-spinner me-1"></i>Continuez à pratiquer
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-4 mt-6">
                        <a href="{{ route('exercises.show', $exercise) }}" class="btn btn-lg btn-outline-primary rounded-xl px-5 py-2 border-2">
                            <i class="fas fa-redo me-2"></i>Recommencer
                        </a>
                        <a href="{{ route('exercises.index') }}" class="btn btn-lg bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl px-5 py-2 shadow-lg hover:shadow-xl transition">
                            <i class="fas fa-book me-2"></i>Autres exercices
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.score-circle {
    width: 150px;
    height: 150px;
    position: relative;
}

.progress-circle {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    background: conic-gradient(
        var(--circle-color) calc(var(--percent) * 3.6deg), 
        #f0f2f5 0deg
    );
    --circle-color: #6C63FF;
    --percent: {{ $percentage }};
    box-shadow: 0 8px 25px rgba(108, 99, 255, 0.15);
}
</style>
@endsection