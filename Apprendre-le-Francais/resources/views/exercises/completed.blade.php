@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center py-5">
        <div class="mb-4">
            <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
        </div>
        <h1 class="fw-bold text-purple mb-3">Félicitations !</h1>
        <p class="text-muted fs-5 mb-5">Vous avez complété tous les exercices disponibles pour votre niveau.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-purple rounded-pill px-4 py-2">
            <i class="fas fa-tachometer-alt me-2"></i> Tableau de bord
        </a>
    </div>
</div>
@endsection