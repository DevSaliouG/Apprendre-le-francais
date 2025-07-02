@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
            <!-- En-tête -->
            <div class="bg-gradient-to-r from-{{ $badge->color }}-50 to-{{ $badge->color }}-100 p-6 text-center">
                <div class="mx-auto w-32 h-32 rounded-full bg-gradient-to-r from-{{ $badge->color }}-100 to-{{ $badge->color }}-200 flex items-center justify-center mb-6">
                    <i class="{{ $badge->icon }} text-5xl text-{{ $badge->color }}-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $badge->name }}</h1>
                <p class="text-gray-600 text-lg">{{ $badge->description }}</p>
                
                @if($hasBadge)
                <span class="inline-block mt-4 bg-green-100 text-green-800 rounded-full py-2 px-5 text-sm font-medium">
                    <i class="fas fa-check-circle mr-2"></i> Badge obtenu
                </span>
                @endif
            </div>
            
            <!-- Contenu -->
            <div class="p-6">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Conditions d'obtention</h2>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <div class="flex items-center">
                            <i class="fas fa-trophy text-{{ $badge->color }}-500 text-xl mr-3"></i>
                            <div>
                                <p class="text-gray-700">
                                    Compléter 
                                    <span class="font-semibold">{{ $badge->threshold }}</span> 
                                    {{ $badge->type === 'lesson' ? 'leçons' : ($badge->type === 'streak' ? 'jours consécutifs' : 'exercices') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-3">Votre progression</h2>
                    <div class="bg-white border border-gray-200 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-gray-700">Objectif atteint</span>
                            <span class="font-semibold">{{ $progress['current'] }}/{{ $progress['target'] }}</span>
                        </div>
                        
                        <div class="w-full bg-gray-200 rounded-full h-3 mb-4">
                            <div class="bg-gradient-to-r from-{{ $badge->color }}-500 to-{{ $badge->color }}-600 h-3 rounded-full" 
                                 style="width: {{ $progress['percentage'] }}%"></div>
                        </div>
                        
                        <div class="text-center">
                            <span class="inline-block bg-gray-100 text-gray-700 rounded-full py-1 px-3 text-sm">
                                {{ $progress['percentage'] }}% complété
                            </span>
                        </div>
                    </div>
                </div>
                
                @if(!$hasBadge)
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex">
                        <i class="fas fa-lightbulb text-blue-500 text-xl mr-3 mt-1"></i>
                        <div>
                            <h3 class="font-bold text-blue-800 mb-1">Conseil</h3>
                            <p class="text-blue-700">
                                @if($badge->type === 'lesson')
                                    Complétez {{ $progress['target'] - $progress['current'] }} leçons supplémentaires pour débloquer ce badge.
                                @elseif($badge->type === 'streak')
                                    Connectez-vous {{ $progress['target'] - $progress['current'] }} jours de plus consécutivement pour obtenir ce badge.
                                @else
                                    Terminez {{ $progress['target'] - $progress['current'] }} exercices supplémentaires pour gagner ce badge.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <div class="mt-6 text-center">
            <a href="{{ route('badges.index') }}" class="btn border-2 border-gray-300 bg-white text-gray-700 hover:bg-gray-50 rounded-xl py-2.5 px-5">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux badges
            </a>
        </div>
    </div>
</div>
@endsection