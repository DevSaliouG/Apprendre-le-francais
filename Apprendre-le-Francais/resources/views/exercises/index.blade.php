@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- En-tête amélioré avec cercle mini -->
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="flex-1">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Exercices de français</h1>
            <p class="text-lg text-gray-600">Pratiquez vos compétences écrites et orales</p>
        </div>
        
        <!-- Widget de progression CIRCULAIRE - TRÈS RÉDUIT -->
        <div class="bg-white rounded-2xl shadow-sm p-4 w-full lg:w-auto flex items-center">
            <!-- Conteneur du cercle très compact -->
            <div class="relative w-10 h-10 mr-3">  <!-- Taille très réduite -->
                <svg class="w-full h-full" viewBox="0 0 100 100">
                    <!-- Cercle de fond -->
                    <circle cx="50" cy="50" r="38" fill="none" stroke="#e0e7ff" stroke-width="8" />
                    <!-- Cercle de progression avec dégradé -->
                    <circle cx="50" cy="50" r="38" fill="none" 
                             stroke="url(#progressGradient)" 
                             stroke-width="8" 
                             stroke-linecap="round"
                             stroke-dasharray="239" 
                             stroke-dashoffset="{{ 239 - (239 * $progress['percentage']) / 100 }}" 
                             class="progress-circle"
                             transform="rotate(-90 50 50)" />
                    <!-- Définition du dégradé -->
                    <defs>
                        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#8B5CF6" />
                            <stop offset="100%" stop-color="#6366F1" />
                        </linearGradient>
                    </defs>
                </svg>
                <!-- Texte au centre du cercle -->
                <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                    <span class="text-[10px] font-bold text-purple-600">{{ $progress['percentage'] }}%</span>
                </div>
                
            </div>
            <div class="text-left">
                <p class="font-medium text-gray-700 text-xs">Progression</p>
                <p class="text-xs text-gray-600">{{ $progress['completed'] }}/{{ $progress['total'] }}</p>
            </div>
        </div>
    </div>

    @php
        $user = Auth::user();
        $levelProgress = $user->getLevelProgress();
    @endphp

    <!-- Carte de progression du niveau -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden mb-8">
        <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-4 border-b border-purple-100">
            <div class="flex items-center gap-3">
                <div class="bg-purple-100 p-3 rounded-full">
                    <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Progression du niveau {{ $user->level ? $user->level->name : 'Non défini' }}</h3>
            </div>
        </div>
        
        <div class="p-6">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="relative">
                    <div class="w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 p-1">
                        <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                            <span class="text-2xl font-bold text-purple-600">{{ $levelProgress['percentage'] }}%</span>
                        </div>
                    </div>
                    <div class="absolute -top-2 -right-2 bg-yellow-400 text-white rounded-full w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-medal"></i>
                    </div>
                </div>
                
                <div class="flex-grow w-full">
                    <div class="flex justify-between text-gray-700 mb-2">
                        <span>Exercices complétés</span>
                        <span>{{ $levelProgress['completed'] }}/{{ $levelProgress['total'] }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden mb-6">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-3 rounded-full" 
                             style="width: {{ $levelProgress['percentage'] }}%"></div>
                    </div>
                    
                    @if($levelProgress['percentage'] >= 84.5)
                        @if($user->level->getNextLevel())
                            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex items-start">
                                <i class="fas fa-trophy text-yellow-500 text-2xl mr-3 mt-1"></i>
                                <div>
                                    <p class="font-semibold text-green-800">
                                        Niveau complété avec succès !
                                    </p>
                                    <p class="text-green-700 mt-1">
                                        Vous êtes prêt pour le niveau supérieur
                                    </p>
                                </div>
                            </div>
                            
                            <form action="{{ route('level.upgrade') }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="btn bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 text-white rounded-xl py-3 px-6 w-full
                                               shadow-md hover:shadow-lg transition-all duration-300">
                                    <i class="fas fa-level-up-alt me-2"></i> 
                                    Passer au niveau supérieur
                                </button>
                            </form>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 flex items-center">
                                <i class="fas fa-crown text-yellow-500 text-2xl mr-3"></i>
                                <div>
                                    <p class="font-bold text-yellow-700">Félicitations !</p>
                                    <p class="text-yellow-700">Vous avez atteint le niveau maximum</p>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Grille de cartes d'exercices -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($exercises as $exercise)
        @php
            $status = $completionStatus[$exercise->id] ?? [
                'completed' => false,
                'started' => false,
                'correct_count' => 0
            ];
            
            $questions_count = $exercise->questions_count ?? 1;
            $correct_count = $status['correct_count'] ?? 0;
            $exerciseProgress = ($questions_count > 0) 
                ? ($correct_count / $questions_count) * 100
                : 0;
        @endphp

        <!-- Carte d'exercice -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1 h-full flex flex-col">
            <!-- En-tête de la carte -->
            <div class="px-5 pt-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-r from-purple-100 to-indigo-100 p-3 rounded-xl">
                            @if ($exercise->type === 'oral')
                                <i class="fas fa-microphone-alt text-purple-500 text-lg"></i>
                            @elseif($exercise->type === 'mixte')
                                <i class="fas fa-language text-indigo-500 text-lg"></i>
                            @else
                                <i class="fas fa-pen text-blue-500 text-lg"></i>
                            @endif
                        </div>
                        <div>
                            <span class="font-semibold text-gray-800">{{ $exercise->lesson->level->name }}</span>
                            <div class="text-xs text-gray-500 flex items-center gap-1">
                                <i class="fas fa-book"></i>
                                <span>Leçon {{ $loop->iteration }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <span class="bg-gray-100 text-gray-700 rounded-full py-1 px-3 text-sm">
                        {{ $exercise->questions_count }} questions
                    </span>
                </div>
                
                <h3 class="font-bold text-gray-800 text-xl mb-3">{{ $exercise->lesson->title }}</h3>
                <p class="text-gray-600 mb-4">{{ Str::limit($exercise->lesson->content, 90) }}</p>
            </div>
            
            <!-- Corps de la carte -->
            <div class="px-5 pb-5 mt-auto">
                <!-- Barre de progression -->
                @if ($status['started'])
                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-1">
                        <span>Votre progression</span>
                        <span>{{ round($exerciseProgress) }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 h-2 rounded-full" 
                             style="width: {{ $exerciseProgress }}%"></div>
                    </div>
                    <div class="text-right text-xs text-gray-500 mt-1">
                        {{ $status['correct_count'] }} réponses correctes
                    </div>
                </div>
                @endif
                
                <!-- Pied de carte -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <div>
                        @if ($status['completed'])
                            <span class="inline-flex items-center bg-green-100 text-green-800 rounded-full py-1 px-3 text-xs font-medium">
                                <i class="fas fa-check-circle me-1"></i>Terminé
                            </span>
                        @elseif ($status['started'])
                            <span class="inline-flex items-center bg-blue-100 text-blue-800 rounded-full py-1 px-3 text-xs font-medium">
                                <i class="fas fa-spinner me-1"></i>En cours
                            </span>
                        @else
                            <span class="inline-flex items-center bg-gray-100 text-gray-800 rounded-full py-1 px-3 text-xs font-medium">
                                <i class="fas fa-clock me-1"></i>À commencer
                            </span>
                        @endif
                    </div>
                    
                    <a href="{{ route('exercises.show', $exercise) }}"
                       class="btn border-2 border-purple-600 bg-white text-purple-600 hover:bg-purple-50 rounded-xl py-2.5 px-5 text-sm font-semibold shadow-sm hover:shadow-md transition flex items-center justify-center min-w-[120px]">
                        @if ($status['started'])
                            Continuer <i class="fas fa-arrow-right ml-2"></i>
                        @else
                            Commencer <i class="fas fa-play ml-2"></i>
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-10">
        {{ $exercises->links() }}
    </div>
</div>

<style>
    /* Animation pour la barre de progression circulaire */
    .progress-circle {
        transition: stroke-dashoffset 1s ease-in-out;
    }
    
    /* Taille ultra réduite pour le cercle */
    .relative.w-10.h-10 {
        width: 10.5rem;
        height: 10.5rem;
    }
    
    .relative.w-10.h-10 span {
        font-size: 10px;
        line-height: 1;
    }
    
    /* Adaptation pour les petits écrans */
    @media (max-width: 640px) {
        .flex-col.lg\:flex-row > div:last-child {
            margin-top: 0.5rem;
        }
    }
</style>
@endsection