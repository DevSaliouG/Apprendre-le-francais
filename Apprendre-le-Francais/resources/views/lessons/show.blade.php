@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2>{{ $lesson->title }}</h2>
                            <span class="badge bg-secondary">{{ $lesson->level->name }}</span>
                            <span class="badge bg-primary py-2">{{ ucfirst($lesson->type) }}</span>

                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="mb-4">
                            {!! $lesson->content !!}
                        </div>

                        <hr>

                        <h4>Exercices</h4>
                        <div class="list-group mb-4">
                            @foreach ($lesson->exercises as $exercise)
                                <a href="{{ route('exercises.show', $exercise) }}"
                                    class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $exercise->title }}
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $exercise->questions_count }} questions
                                    </span>
                                </a>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            @if (!$isCompleted)
                                <form method="POST" action="{{ route('lessons.complete', $lesson) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        Marquer comme terminé
                                    </button>
                                </form>
                            @else
                                <span class="text-success fw-bold">
                                    ✔ Leçon complétée
                                </span>
                            @endif

                            @if ($nextLesson)
                                <a href="{{ route('lessons.show', $nextLesson) }}" class="btn btn-primary">
                                    Leçon suivante &rarr;
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
