@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Leçons - Niveau {{ $user->level->name }}</h1>

    <div class="lessons-container">
        @foreach($lessons as $lesson)
        <div class="lesson-card">
            <div class="lesson-header">
                <h3>{{ $lesson->title }}</h3>
                <span class="badge bg-primary">{{ $lesson->exercises_count }} exercices</span>
            </div>
            
            <p class="lesson-description">{{ Str::limit($lesson->content, 150) }}</p>
            
            <div class="lesson-progress">
                @php $completed = auth()->user()->completedExercisesInLesson($lesson); @endphp
                <div class="progress">
                    <div class="progress-bar" style="width: {{ ($completed / $lesson->exercises_count) * 100 }}%"></div>
                </div>
                <span>{{ $completed }}/{{ $lesson->exercises_count }} complétés</span>
            </div>
            
            <a href="{{ route('lessons.show', $lesson) }}" class="btn btn-primary">
                @if($completed > 0) Continuer @else Commencer @endif
            </a>
        </div>
        @endforeach
    </div>
</div>
@endsection