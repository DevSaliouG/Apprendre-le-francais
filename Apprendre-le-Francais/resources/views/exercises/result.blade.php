@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <div class="card-header bg-purple bg-opacity-10 text-center py-5">
                        <div class="mb-4">
                            @if ($isSuccess)
                                <div class="bg-success bg-opacity-10 d-inline-block p-4 rounded-circle">
                                    <i class="fas fa-check-circle fa-4x text-success"></i>
                                </div>
                            @else
                                <div class="bg-danger bg-opacity-10 d-inline-block p-4 rounded-circle">
                                    <i class="fas fa-exclamation-circle fa-4x text-danger"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="fw-bold text-purple">
                            {{ $isSuccess ? 'Félicitations !' : 'Presque réussi !' }}
                        </h3>
                        <p class="text-muted">
                            Vous avez terminé l'exercice "{{ $exercise->lesson->title }}"
                        </p>
                    </div>

                    <div class="card-body text-center py-5">
                        <div class="mb-5">
                            <div class="position-relative mx-auto mb-3" style="width: 150px; height: 150px;">
                                <div class="progress-circle rounded-circle"
                                    style="background: conic-gradient(
                                    var(--circle-color) calc({{ $percentage }} * 3.6deg), 
                                    #f0f2f5 0deg
                                 ); 
                                 --circle-color: {{ $isSuccess ? '#6C63FF' : '#FF6584' }}">
                                </div>
                                <div class="position-absolute top-50 start-50 translate-middle">
                                    <h2 class="mb-0 fw-bold">{{ $percentage }}%</h2>
                                </div>
                            </div>
                            <p class="text-muted">
                                {{ $score }} bonnes réponses sur {{ $totalQuestions }} questions
                            </p>
                        </div>

                        <div
                            class="alert alert-{{ $isExerciseCompleted ? 'success' : 'warning' }} d-flex align-items-center justify-content-center mb-5">
                            <div>
                                @if ($isExerciseCompleted)
                                    <span class="fw-medium">
                                        <i class="fas fa-check-circle me-2"></i>Vous avez réussi cet exercice
                                    </span>
                                @else
                                    <span class="fw-medium">
                                        <i class="fas fa-spinner me-2"></i>Continuez à pratiquer
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('exercises.show', $exercise) }}"
                                class="btn btn-outline-purple rounded-pill px-4">
                                <i class="fas fa-redo me-2"></i>Recommencer
                            </a>
                            <a href="{{ route('exercises.index') }}" class="btn btn-purple rounded-pill px-4">
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
            background: conic-gradient(var(--circle-color) calc(var(--percent) * 3.6deg),
                    #f0f2f5 0deg);
            --circle-color: #6C63FF;
            --percent: {{ $percentage }};
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.15);
        }
    </style>
@endsection
