@extends('layouts.app')

@section('content')
<div class="container">
    <div class="lesson-header mb-4">
        <h1>{{ $lesson->title }}</h1>
        <p class="text-muted">Niveau {{ $lesson->level->name }}</p>
    </div>

    <div class="lesson-content mb-5">
        {!! $lesson->content !!}
    </div>

    <h2 class="mb-3">Exercices</h2>
    
    <div class="exercises-list">
        @foreach($exercises as $exercise)
        <div class="exercise-card @if(auth()->user()->hasCompletedExercise($exercise)) completed @endif">
            <div class="exercise-icon">
                @if($exercise->type === 'oral')
                    <i class="fas fa-microphone"></i>
                @elseif($exercise->type === 'mixte')
                    <i class="fas fa-blender-phone"></i>
                @else
                    <i class="fas fa-edit"></i>
                @endif
            </div>
            
            <div class="exercise-info">
                <h4>Exercice {{ $loop->iteration }}</h4>
                <p>{{ $exercise->questions_count }} questions</p>
            </div>
            
            @if(auth()->user()->hasCompletedExercise($exercise))
                <span class="badge bg-success">Terminé</span>
            @else
                <a href="{{ route('exercises.show', $exercise) }}" class="btn btn-sm btn-primary">
                    Commencer
                </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection