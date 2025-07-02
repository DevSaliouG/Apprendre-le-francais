@extends('layouts.app')

@section('title', 'Modifier la Question')
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Modifier la question #{{ $question->id }}</h1>
    
    <form action="{{ route('admin.questions.update', $question) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded shadow p-6 mb-6">
            @include('admin.questions._form', ['question' => $question])
        </div>
        
        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">
                Mettre à jour
            </button>
            <a href="{{ route('admin.exercises.show', $question->exercise) }}" class="btn btn-secondary">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection