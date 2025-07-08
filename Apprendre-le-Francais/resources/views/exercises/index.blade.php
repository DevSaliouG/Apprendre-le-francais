@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <!-- En-tête -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
            <div class="mb-3 mb-md-0">
                <h1 class="fw-bold text-purple mb-2">Exercices de français</h1>
                <p class="text-muted">Pratiquez vos compétences écrites et orales</p>
            </div>

            <!-- Widget progression -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body d-flex align-items-center">
                    <div class="position-relative me-3">
                        <div class="progress-circle position-relative" style="width: 60px; height: 60px;">
                            <svg viewBox="0 0 100 100" class="position-absolute">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e0e7ff"
                                    stroke-width="8" />
                                <circle cx="50" cy="50" r="45" fill="none" stroke="url(#progressGradient)"
                                    stroke-width="8" stroke-linecap="round" stroke-dasharray="283"
                                    stroke-dashoffset="{{ 283 - (283 * $progress['percentage']) / 100 }}"
                                    transform="rotate(-90 50 50)" />
                            </svg>
                            <span class="position-absolute top-50 start-50 translate-middle fw-bold text-purple"
                                style="font-size: 10px">
                                {{ $progress['percentage'] }}%
                            </span>
                        </div>
                    </div>
                    <div>
                        <p class="fw-medium text-muted mb-0">Progression</p>
                        <p class="mb-0">{{ $progress['completed'] }}/{{ $progress['total'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression niveau -->
        <div class="card shadow-sm border-0 rounded-3 mb-4 overflow-hidden">
            <div class="card-header bg-purple bg-opacity-10 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-purple bg-opacity-25 p-2 rounded-circle me-3">
                        <i class="fas fa-chart-line text-purple"></i>
                    </div>
                    <h3 class="mb-0 fw-bold text-purple">
                        Progression du niveau {{ $user->level ? $user->level->name : 'Non défini' }}
                    </h3>
                </div>
            </div>

            <div class="card-body">
                <div class="d-flex flex-column flex-md-row align-items-center">
                    <div class="position-relative me-md-4 mb-4 mb-md-0">
                        <div class="progress-circle position-relative" style="width: 100px; height: 100px;">
                            <svg viewBox="0 0 100 100" class="position-absolute">
                                <circle cx="50" cy="50" r="45" fill="none" stroke="#e0e7ff"
                                    stroke-width="8" />
                                <circle cx="50" cy="50" r="45" fill="none" stroke="url(#levelGradient)"
                                    stroke-width="8" stroke-linecap="round" stroke-dasharray="283"
                                    stroke-dashoffset="{{ 283 - (283 * $progress['percentage']) / 100 }}"
                                    transform="rotate(-90 50 50)" />
                            </svg>
                            <span class="position-absolute top-50 start-50 translate-middle fw-bold text-purple fs-4">
                                {{ $progress['percentage'] }}%
                            </span>
                        </div>
                        <div class="position-absolute top-0 end-0 bg-warning text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 36px; height: 36px;">
                            <i class="fas fa-medal"></i>
                        </div>
                    </div>

                    <div class="flex-grow-1 w-100">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Exercices complétés</span>
                            <span class="fw-medium">{{ $progress['completed'] }}/{{ $progress['total'] }}</span>
                        </div>
                        <div class="progress rounded-pill mb-3" style="height: 10px;">
                            <div class="progress-bar bg-purple" role="progressbar"
                                style="width: {{ $progress['percentage'] }}%"></div>
                        </div>

                        @if ($progress['percentage'] >= 84.5)
                            @if ($user->level->getNextLevel())
                                <div class="alert alert-success mb-4">
                                    <div class="d-flex">
                                        <i class="fas fa-trophy text-warning me-3 fs-4"></i>
                                        <div>
                                            <p class="fw-bold mb-1">Niveau complété avec succès !</p>
                                            <p class="mb-0">Vous êtes prêt pour le niveau supérieur</p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('level.upgrade') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 rounded-pill py-2">
                                        <i class="fas fa-level-up-alt me-2"></i>
                                        Passer au niveau supérieur
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning d-flex align-items-center">
                                    <i class="fas fa-crown text-warning me-3 fs-4"></i>
                                    <div>
                                        <p class="fw-bold mb-0">Félicitations !</p>
                                        <p class="mb-0">Vous avez atteint le niveau maximum</p>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Barre de recherche -->
        <div class="card shadow-sm border-0 rounded-3 mb-4">
            <div class="card-body py-3">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="fas fa-search text-muted"></i>
                    </span>
                    <input 
                        type="text" 
                        id="searchInput" 
                        class="form-control border-start-0" 
                        placeholder="Rechercher un exercice..."
                        aria-label="Rechercher un exercice"
                    >
                </div>
            </div>
        </div>

        <!-- Grille exercices -->
        <div class="row g-4" id="exercisesContainer">
            @foreach ($exercises as $exercise)
                @php
                    $status = $completionStatus[$exercise->id] ?? [
                        'completed' => false,
                        'started' => false,
                        'correct_count' => 0,
                    ];
                    $questions_count = $exercise->questions_count ?? 1;
                    $correct_count = $status['correct_count'] ?? 0;
                    $exerciseProgress = $questions_count > 0 ? ($correct_count / $questions_count) * 100 : 0;
                @endphp

                <div class="col-md-6 col-lg-4 exercise-card">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-purple bg-opacity-10 p-2 rounded-circle me-3">
                                        @if ($exercise->type === 'oral')
                                            <i class="fas fa-microphone-alt text-purple"></i>
                                        @elseif($exercise->type === 'mixte')
                                            <i class="fas fa-language text-purple"></i>
                                        @else
                                            <i class="fas fa-pen text-purple"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-medium text-purple">{{ $exercise->lesson->level->name }}</span>
                                        <div class="text-muted small">
                                            <i class="fas fa-book me-1"></i>Leçon {{ $loop->iteration }}
                                        </div>
                                    </div>
                                </div>

                                <span class="badge bg-light text-muted">
                                    {{ $exercise->questions_count }} questions
                                </span>
                            </div>

                            <h5 class="fw-bold text-dark mb-3">{{ $exercise->lesson->title }}</h5>
                            <p class="text-muted small mb-4">
                                <span class="badge bg-primary">{{ ucfirst($exercise->lesson->type) }}</span>
                                {{ Str::limit($exercise->lesson->content, 80) }}
                            </p>

                            @if ($status['started'])
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between small text-muted mb-1">
                                        <span>Votre progression</span>
                                        <span>{{ round($exerciseProgress) }}%</span>
                                    </div>
                                    <div class="progress rounded-pill" style="height: 6px;">
                                        <div class="progress-bar bg-purple" style="width: {{ $exerciseProgress }}%">
                                        </div>
                                    </div>
                                    <div class="text-end small text-muted mt-1">
                                        {{ $status['correct_count'] }} réponses correctes
                                    </div>
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div>
                                    @if ($status['completed'])
                                        <span class="badge bg-success bg-opacity-10 text-success">
                                            <i class="fas fa-check-circle me-1"></i>Terminé
                                        </span>
                                    @elseif ($status['started'])
                                        <span class="badge bg-info bg-opacity-10 text-info">
                                            <i class="fas fa-spinner me-1"></i>En cours
                                        </span>
                                    @else
                                        <span class="badge bg-light text-muted">
                                            <i class="fas fa-clock me-1"></i>À commencer
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('exercises.show', $exercise) }}"
                                    class="btn btn-sm btn-outline-purple rounded-pill px-3">
                                    @if ($status['started'])
                                        Continuer <i class="fas fa-arrow-right ms-1"></i>
                                    @else
                                        Commencer <i class="fas fa-play ms-1"></i>
                                    @endif
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-5">
                <ul class="pagination shadow-sm rounded-pill">
                    {{-- Lien précédent --}}
                    @if ($exercises ->onFirstPage())
                        <li class="page-item disabled"><span class="page-link rounded-pill">&laquo;</span></li>
                    @else
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $exercises->previousPageUrl() }}">&laquo;</a></li>
                    @endif

                    {{-- Pages --}}
                    @foreach ($exercises->getUrlRange(1, $exercises->lastPage()) as $page => $url)
                        <li class="page-item {{ $exercises->currentPage() === $page ? 'active' : '' }}">
                            <a class="page-link rounded-pill" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    {{-- Lien suivant --}}
                    @if ($exercises->hasMorePages())
                        <li class="page-item"><a class="page-link rounded-pill"
                                href="{{ $exercises->nextPageUrl() }}">&raquo;</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link rounded-pill">&raquo;</span></li>
                    @endif
                </ul>
            </div>
    </div>

    <style>
        /* Animation pour la barre de progression circulaire */
        .progress-circle {
            transition: stroke-dashoffset 1s ease-in-out;
        }

        /* Taille ultra réduite pour le cercle */
        .relative.w-10.h-10 {
            width: 10.5rem;
            height: 10.5rem;
        }

        .relative.w-10.h-10 span {
            font-size: 10px;
            line-height: 1;
        }

        /* Adaptation pour les petits écrans */
        @media (max-width: 640px) {
            .flex-col.lg\:flex-row>div:last-child {
                margin-top: 0.5rem;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const exerciseCards = document.querySelectorAll('.exercise-card');
            
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.trim().toLowerCase();
                
                exerciseCards.forEach(card => {
                    const title = card.querySelector('h5').textContent.toLowerCase();
                    const content = card.querySelector('p').textContent.toLowerCase();
                    const level = card.querySelector('.fw-medium.text-purple').textContent.toLowerCase();
                    
                    const isVisible = title.includes(searchTerm) || 
                                     content.includes(searchTerm) || 
                                     level.includes(searchTerm);
                    
                    card.style.display = isVisible ? 'block' : 'none';
                });
            });
        });
    </script>
@endsection