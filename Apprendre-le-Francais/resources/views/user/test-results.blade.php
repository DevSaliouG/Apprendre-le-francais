@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-16 text-center">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <!-- Icône de validation -->
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-gray-900 mb-4">Test de niveau terminé !</h1>
        
        <!-- Résultats -->
        <div class="space-y-4 mb-8">
            <div class="text-xl">
                <span class="font-semibold">Votre score:</span> 
                {{ $score }}/{{ $total }} ({{ $percentage }}%)
            </div>
            
            <div class="text-2xl font-bold text-blue-600 py-4">
                Niveau attribué: {{ $level->name }}
            </div>
            
            <p class="text-gray-600">
                {{ $level->description }}
            </p>
        </div>

        <!-- Compte à rebours -->
        <div class="text-lg text-gray-500 mb-6">
            Redirection vers le tableau de bord dans 
            <span id="countdown" class="font-bold text-blue-500">{{ $delay }}</span> 
            secondes...
        </div>

        <!-- Bouton manuel -->
        <a href="{{ $redirectUrl }}" class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
            Accéder maintenant au tableau de bord
        </a>
    </div>
</div>

<script>
    // Compte à rebours automatique
    let seconds = {{ $delay }};
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        seconds--;
        countdownEl.textContent = seconds;
        
        if (seconds <= 0) {
            clearInterval(timer);
            window.location.href = "{{ $redirectUrl }}";
        }
    }, 1000);
</script>
@endsection