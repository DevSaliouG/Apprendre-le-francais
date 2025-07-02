@extends('layouts.app')

@section('title', 'Mes Exercices')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Exercices pratiques</h1>
        <div class="d-flex">
            <div class="input-group me-2" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Rechercher un exercice...">
                <button class="btn btn-outline-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#">Tous les exercices</a></li>
                    <li><a class="dropdown-item" href="#">Grammaire</a></li>
                    <li><a class="dropdown-item" href="#">Vocabulaire</a></li>
                    <li><a class="dropdown-item" href="#">Compréhension</a></li>
                    <li><a class="dropdown-item" href="#">Expression</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white text-center p-3">
                        <h3 class="h5 mb-0">Exercices complétés</h3>
                        <p class="display-4 mb-0">{{ $completedExercises }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white text-center p-3">
                        <h3 class="h5 mb-0">Taux de réussite</h3>
                        <p class="display-4 mb-0">{{ $successRate }}%</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white text-center p-3">
                        <h3 class="h5 mb-0">Points gagnés</h3>
                        <p class="display-4 mb-0">{{ $xpPoints }}</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark text-center p-3">
                        <h3 class="h5 mb-0">Série actuelle</h3>
                        <p class="display-4 mb-0">{{ $currentStreak }}</p>
                    </div>
                </div>
            </div>

            <div class="row">
                @foreach($exercises as $exercise)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card exercise-card h-100">
                        <div class="card-header bg-transparent d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $exercise->is_completed ? 'success' : 'primary' }}">
                                {{ $exercise->type }}
                            </span>
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-clock me-1"></i> {{ $exercise->duration }} min
                            </span>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ $exercise->title }}</h5>
                            <p class="card-text text-muted small">{{ $exercise->description }}</p>
                            
                            @if($exercise->is_completed)
                            <div class="progress mb-3">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: 100%;" 
                                     aria-valuenow="100" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                    100%
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                    <i class="far fa-star"></i>
                                </span>
                            </div>
                            <a href="{{ route('user.exercises.show', $exercise) }}" class="btn btn-sm btn-{{ $exercise->is_completed ? 'outline-success' : 'primary' }}">
                                @if($exercise->is_completed)
                                <i class="fas fa-redo me-1"></i> Refaire
                                @else
                                <i class="fas fa-play me-1"></i> Commencer
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $exercises->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .exercise-card {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        border: none;
    }
    
    .exercise-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .exercise-card .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endsection