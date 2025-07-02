@extends('layouts.app')

@section('title', $exercise->title)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('user.exercises') }}">Mes Exercices</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($exercise->title, 20) }}</li>
                </ol>
            </nav>
            <h1 class="h2 mb-0">{{ $exercise->title }}</h1>
        </div>
        <div>
            @if($exercise->is_completed)
            <span class="badge bg-success p-2">
                <i class="fas fa-check-circle me-1"></i> Terminé
            </span>
            @else
            <span class="badge bg-light text-dark p-2">
                <i class="fas fa-clock me-1"></i> {{ $exercise->duration }} min
            </span>
            @endif
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-8">
                    <div class="exercise-instructions mb-4 p-4 bg-light rounded">
                        <h4 class="h5 mb-3">Instructions</h4>
                        <p>{{ $exercise->instructions }}</p>
                        
                        @if($exercise->example)
                        <div class="example-box p-3 mt-3 bg-white rounded">
                            <h5 class="h6 mb-2">Exemple:</h5>
                            <p class="mb-1"><strong>{{ $exercise->example['question'] }}</strong></p>
                            <p class="text-success mb-0">{{ $exercise->example['answer'] }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('user.exercises.submit', $exercise) }}">
                        @csrf
                        
                        <div class="exercise-content">
                            @foreach($exercise->questions as $index => $question)
                            <div class="question-card mb-4 p-4 border rounded">
                                <h5 class="mb-3">Question {{ $index + 1 }}</h5>
                                <p class="mb-3">{{ $question['text'] }}</p>
                                
                                @if($question['type'] === 'multiple_choice')
                                <div class="choices">
                                    @foreach($question['options'] as $option)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $index }}]" 
                                               id="q{{ $index }}-option{{ $loop->index }}"
                                               value="{{ $option }}">
                                        <label class="form-check-label" for="q{{ $index }}-option{{ $loop->index }}">
                                            {{ $option }}
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                                
                                @elseif($question['type'] === 'fill_in_blank')
                                <div class="fill-blank">
                                    <p>{!! $question['text_with_blanks'] !!}</p>
                                    
                                    <div class="row">
                                        @foreach($question['blanks'] as $blankIndex => $blank)
                                        <div class="col-md-4 mb-3">
                                            <label for="blank-{{ $blankIndex }}" class="form-label">Mot #{{ $blankIndex + 1 }}</label>
                                            <input type="text" class="form-control" 
                                                   id="blank-{{ $blankIndex }}" 
                                                   name="answers[{{ $index }}][{{ $blankIndex }}]">
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                @elseif($question['type'] === 'true_false')
                                <div class="true-false">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $index }}]" 
                                               id="q{{ $index }}-true" 
                                               value="true">
                                        <label class="form-check-label" for="q{{ $index }}-true">
                                            Vrai
                                        </label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" 
                                               name="answers[{{ $index }}]" 
                                               id="q{{ $index }}-false" 
                                               value="false">
                                        <label class="form-check-label" for="q{{ $index }}-false">
                                            Faux
                                        </label>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary">
                                <i class="fas fa-save me-1"></i> Sauvegarder
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-1"></i> Soumettre les réponses
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="col-lg-4">
                    <div class="sticky-top" style="top: 20px;">
                        <div class="content-card mb-4">
                            <div class="card-header">
                                <i class="fas fa-info-circle me-2"></i> Détails de l'exercice
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Type:</span>
                                        <span class="fw-bold">{{ ucfirst($exercise->type) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Difficulté:</span>
                                        <span class="badge bg-{{ $exercise->difficulty === 'facile' ? 'success' : ($exercise->difficulty === 'moyen' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($exercise->difficulty) }}
                                        </span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Nombre de questions:</span>
                                        <span class="fw-bold">{{ count($exercise->questions) }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Points XP:</span>
                                        <span class="fw-bold text-success">+{{ $exercise->xp_points }}</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between">
                                        <span>Leçon associée:</span>
                                        <span class="fw-bold">
                                            <a href="{{ route('user.lessons.show', $exercise->lesson) }}">
                                                {{ $exercise->lesson->title }}
                                            </a>
                                        </span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="content-card">
                            <div class="card-header">
                                <i class="fas fa-trophy me-2"></i> Récompenses
                            </div>
                            <div class="card-body text-center">
                                <div class="reward-badge mb-3 mx-auto">
                                    <i class="fas fa-medal fa-3x text-warning"></i>
                                </div>
                                <p class="mb-0">Complétez cet exercice pour débloquer ce badge et gagner {{ $exercise->xp_points }} points d'expérience!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .exercise-instructions {
        border-left: 4px solid var(--primary-color);
    }
    
    .example-box {
        border-left: 3px solid var(--success-color);
    }
    
    .question-card {
        background-color: white;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
    }
    
    .reward-badge {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ffd700 0%, #ffec8b 100%);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .fill-blank .blank {
        display: inline-block;
        border-bottom: 2px dashed #4361ee;
        min-width: 80px;
        margin: 0 5px;
        text-align: center;
    }
</style>
@endsection