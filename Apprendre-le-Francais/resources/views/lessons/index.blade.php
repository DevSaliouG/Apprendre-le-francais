@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>{{ $currentLevel->name }} - Leçons</h2>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    <div class="list-group">
                        @forelse($lessons as $lesson)
                            <a href="{{ route('lessons.show', $lesson) }}" 
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div>
                                    <h5>{{ $lesson->title }}</h5>
                                    <p class="mb-0">
                                        <span class="badge bg-primary me-2">{{ ucfirst($lesson->type) }}</span>
                                        Exercices: {{ $lesson->exercises->count() }}
                                        ({{ $lesson->exercises->sum('questions_count') }} questions)
                                    </p>
                                </div>
                                @if($user->hasCompletedLesson($lesson->id))
                                    <span class="badge bg-success rounded-pill">Terminé</span>
                                @endif
                            </a>
                        @empty
                            <div class="list-group-item text-center text-muted">
                                Aucune leçon disponible pour ce niveau
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection