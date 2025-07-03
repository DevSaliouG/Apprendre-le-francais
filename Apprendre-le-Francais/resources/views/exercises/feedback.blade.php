@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-purple bg-opacity-10 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold text-purple">Feedback détaillé</h5>
                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <div class="card-body p-4">
            <div class="mb-4">
                <h6 class="text-muted mb-2">Question :</h6>
                <p class="bg-light p-3 rounded">{{ $question->texte }}</p>
            </div>
            
            <div class="mb-4">
                <h6 class="text-muted mb-2">Votre réponse :</h6>
                <div class="bg-light p-3 rounded">
                    @if($question->format_reponse === 'audio' && $result->audio_response)
                    <audio controls class="w-100">
                        <source src="{{ Storage::url($result->audio_response) }}" type="audio/mpeg">
                    </audio>
                    @else
                    <p class="mb-0">{{ $result->reponse ?? 'Aucune réponse fournie' }}</p>
                    @endif
                </div>
            </div>
            
            <div class="mb-4">
                <h6 class="text-muted mb-2">Réponse correcte :</h6>
                <p class="bg-light p-3 rounded">{{ $question->reponse_correcte }}</p>
            </div>
            
            <div class="mb-4">
                <div class="d-flex align-items-center">
                    <h6 class="text-muted mb-0">Statut :</h6>
                    <span class="badge rounded-pill ms-3 py-2 px-3 bg-{{ $result->correct ? 'success' : 'danger' }}">
                        {{ $result->correct ? 'Correct' : 'Incorrect' }}
                    </span>
                </div>
            </div>
            
            <div class="border-top pt-4">
                <h5 class="mb-3 fw-bold text-purple d-flex align-items-center">
                    <i class="fas fa-lightbulb me-3 {{ $result->correct ? 'text-success' : 'text-warning' }}"></i>
                    {{ $result->correct ? 'Bonne réponse !' : 'Explication' }}
                </h5>
                <div class="alert alert-{{ $result->correct ? 'success' : 'info' }} rounded">
                    <p class="mb-0">{{ $explanation['message'] }}</p>
                </div>
                
                @if(!$result->correct)
                <div class="mt-4">
                    <h6 class="mb-3 fw-bold text-purple d-flex align-items-center">
                        <i class="fas fa-book me-3 text-purple"></i>Ressources pour vous améliorer
                    </h6>
                    <div class="list-group">
                        @foreach($explanation['resources'] as $resource)
                        <a href="{{ $resource['url'] }}" class="list-group-item list-group-item-action">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-external-link-alt me-3 text-purple"></i>
                                <div class="fw-medium">{{ $resource['title'] }}</div>
                                <i class="fas fa-chevron-right ms-auto text-muted"></i>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection