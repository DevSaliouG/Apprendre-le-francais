@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Tous les badges</h1>
        <p class="text-gray-600">Débloquez ces récompenses en accomplissant vos objectifs d'apprentissage</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($badges as $badge)
        @php
            $progress = $badge->progress ?? ['percentage' => 0, 'current' => 0, 'target' => 1];
        @endphp
        
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
            <div class="p-5 text-center">
                <div class="mx-auto w-24 h-24 rounded-full bg-gradient-to-r from-{{ $badge->color }}-100 to-{{ $badge->color }}-200 flex items-center justify-center mb-4">
                    <i class="{{ $badge->icon }} text-3xl text-{{ $badge->color }}-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-xl mb-2">{{ $badge->name }}</h3>
                <p class="text-gray-600 mb-4">{{ $badge->description }}</p>
                
                <div class="mt-4">
                    <span class="inline-block bg-gray-100 text-gray-700 rounded-full py-1 px-3 text-sm">
                        {{ $badge->threshold }} {{ $badge->type === 'lesson' ? 'leçons' : ($badge->type === 'streak' ? 'jours' : 'exercices') }}
                    </span>
                </div>
            </div>
            
            <div class="px-5 pb-5">
                @if(in_array($badge->id, $userBadges))
                <div class="bg-green-50 border border-green-200 rounded-xl p-3 text-center">
                    <div class="flex items-center justify-center text-green-600">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>Badge obtenu</span>
                    </div>
                </div>
                @else
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-3">
                    <div class="text-center text-sm text-gray-500">
                        Progression: {{ $progress['percentage'] }}%
                        ({{ $progress['current'] }}/{{ $progress['target'] }})
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-gradient-to-r from-{{ $badge->color }}-500 to-{{ $badge->color }}-600 h-2 rounded-full" 
                             style="width: {{ $progress['percentage'] }}%"></div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection