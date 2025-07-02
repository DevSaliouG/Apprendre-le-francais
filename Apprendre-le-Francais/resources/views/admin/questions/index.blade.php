@extends('layouts.app')

@section('title', 'Gestion des Questions')


@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Gestion des questions</h1>
    
    <div class="mb-4 flex justify-between items-center">
        <div>
            <a href="{{ route('admin.exercises.index') }}" class="btn btn-secondary">
                &larr; Retour aux exercices
            </a>
        </div>
    </div>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left">ID</th>
                    <th class="px-6 py-3 text-left">Exercice</th>
                    <th class="px-6 py-3 text-left">Question</th>
                    <th class="px-6 py-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($questions as $question)
                <tr>
                    <td class="px-6 py-4">{{ $question->id }}</td>
                    <td class="px-6 py-4">Exercice #{{ $question->exercise_id }}</td>
                    <td class="px-6 py-4">{{ Str::limit($question->texte, 70) }}</td>
                    <td class="px-6 py-4 flex space-x-2">
                        <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-info">
                            Voir
                        </a>
                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-warning">
                            Modifier
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-4">
        {{ $questions->links() }}
    </div>
</div>
@endsection