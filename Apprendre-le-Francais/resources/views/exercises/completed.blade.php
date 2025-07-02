@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle fa-5x text-success"></i>
        </div>
        <h1 class="font-bold text-3xl text-gray-800 mb-3">Félicitations !</h1>
        <p class="text-gray-600 text-lg mb-5">Vous avez complété tous les exercices disponibles pour votre niveau.</p>
        <a href="{{ route('dashboard') }}" class="btn bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-3 px-6 shadow-md hover:shadow-lg transition">
            <i class="fas fa-tachometer-alt me-2"></i> Retour au tableau de bord
        </a>
    </div>
</div>
@endsection