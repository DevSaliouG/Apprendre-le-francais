@extends('layouts.app')

@section('title', 'Créer une Question')
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Ajouter une question à l'exercice #{{ $exercise->id }}</h1>
    
    <form action="{{ route('admin.exercises.questions.store', $exercise) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="bg-white rounded shadow p-6 mb-6">
            @include('admin.questions._form')
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">
                Ajouter la question
            </button>
            <a href="{{ route('admin.exercises.show', $exercise) }}" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection