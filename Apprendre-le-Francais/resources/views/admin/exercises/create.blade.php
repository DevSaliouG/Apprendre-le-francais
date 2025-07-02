@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Créer un nouvel exercice</h1>
            <div class="flex items-center text-sm text-gray-600">
                <a href="{{ route('admin.exercises.index') }}" class="text-primary hover:text-primary-light flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour aux exercices
                </a>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.exercises.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                @include('admin.exercises._form')
                
                <div class="flex items-center gap-4 mt-8">
                    <button type="submit" class="btn-primary flex items-center px-4 py-2 rounded-full transition-all">
                        <i class="fas fa-plus mr-2"></i> Créer l'exercice
                    </button>
                    <a href="{{ route('admin.exercises.index') }}" 
                       class="btn-secondary flex items-center px-4 py-2 rounded-full transition-all">
                        <i class="fas fa-times mr-2"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-primary {
        background: linear-gradient(135deg, #6C63FF, #4A42D6);
        color: white;
        box-shadow: 0 4px 10px rgba(108, 99, 255, 0.3);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(108, 99, 255, 0.4);
    }
    .btn-secondary {
        background: linear-gradient(135deg, #e0e0e0, #bdbdbd);
        color: #333;
        box-shadow: 0 4px 10px rgba(189, 189, 189, 0.3);
    }
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(189, 189, 189, 0.4);
    }
    .card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(46, 42, 71, 0.1);
        overflow: hidden;
    }
    .card-body {
        padding: 30px;
    }
</style>
@endsection