@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h1 class="h2 fw-bold mb-0">Détails de la question #{{ $question->id }}</h1>
        </div>
    </div>
    
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="mb-4">
                <label class="form-label fw-bold">Exercice parent:</label>
                <p class="mb-0">#{{ $question->exercise_id }}</p>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Format de réponse:</label>
                <p class="mb-0">
                    @if($question->format_reponse === 'choix_multiple')
                        Choix multiple
                    @elseif($question->format_reponse === 'texte_libre')
                        Texte libre
                    @else
                        Réponse audio
                    @endif
                </p>
            </div>
            
            <div class="mb-4">
                <label class="form-label fw-bold">Question:</label>
                <p class="mb-0">{{ $question->texte }}</p>
            </div>
            
            @if($question->format_reponse === 'choix_multiple')
            <div class="mb-4">
                <label class="form-label fw-bold">Options:</label>
                <ul class="list-group">
                    @foreach($question->clean_choix as $index => $option)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $option }}
                        @if($index == $question->reponse_correcte)
                        <span class="badge bg-success">Réponse correcte</span>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="mb-4">
                <label class="form-label fw-bold">Réponse correcte:</label>
                <p class="mb-0">{{ $question->reponse_correcte }}</p>
            </div>
            @endif
            
            @if($question->fichier_audio)
            <div class="mb-4">
                <label class="form-label fw-bold">Fichier audio:</label>
                <div class="mt-2">
                    <audio controls class="w-100">
                        <source src="{{ Storage::url($question->fichier_audio) }}" type="audio/mpeg">
                    </audio>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <div class="d-flex gap-2">
        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-warning">
            Modifier
        </a>
        <a href="{{ route('admin.exercises.show', $question->exercise) }}" class="btn btn-secondary">
            Retour à l'exercice
        </a>
         <form action="{{ route('admin.questions.destroy', $question) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette question?')">
            <i class="fas fa-trash me-1"></i> Supprimer
        </button>
    </form>
    </div>
</div>
@endsection