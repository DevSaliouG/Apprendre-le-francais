@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Détails de la question #{{ $question->id }}</h1>
    
    <div class="bg-white rounded shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Exercice parent: #{{ $question->exercise_id }}</h2>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Question:</label>
            <p class="text-gray-800">{{ $question->texte }}</p>
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Options:</label>
            <ul class="list-disc pl-6">
                @foreach($question->clean_choix as $index => $option)
                <li class="mb-1">
                    {{ $option }}
                    @if($index == $question->reponse_correcte)
                    <span class="text-green-600 font-bold">(Réponse correcte)</span>
                    @endif
                </li>
                @endforeach
            </ul>
        </div>
        
        @if($question->fichier_audio)
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Fichier audio:</label>
            <audio controls class="mt-2">
                <source src="{{ Storage::url($question->fichier_audio) }}" type="audio/mpeg">
            </audio>
        </div>
        @endif
    </div>
    
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-warning">
            Modifier
        </a>
        <a href="{{ route('admin.exercises.show', $question->exercise) }}" class="btn btn-secondary">
            Retour à l'exercice
        </a>
    </div>
</div>
@endsection