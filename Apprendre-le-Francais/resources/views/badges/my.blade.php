@extends('layouts.app')

@section('content')
<div class="container py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Mes badges</h1>
        <p class="text-gray-600">Votre collection de récompenses obtenues</p>
    </div>

    @if($badges->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
        <div class="mx-auto w-24 h-24 rounded-full bg-gray-100 flex items-center justify-center mb-4">
            <i class="fas fa-trophy text-3xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Vous n'avez pas encore de badges</h3>
        <p class="text-gray-600 mb-4">Complétez des leçons et des exercices pour débloquer vos premières récompenses !</p>
        <a href="{{ route('exercises.index') }}" class="btn bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-2.5 px-5">
            Commencer les exercices
        </a>
    </div>
    @else
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
        @foreach ($badges as $badge)
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            <div class="p-5 text-center">
                <div class="mx-auto w-20 h-20 rounded-full bg-gradient-to-r from-{{ $badge->color }}-100 to-{{ $badge->color }}-200 flex items-center justify-center mb-4">
                    <i class="{{ $badge->icon }} text-2xl text-{{ $badge->color }}-600"></i>
                </div>
                <h3 class="font-bold text-gray-800 text-lg mb-1">{{ $badge->name }}</h3>
                <p class="text-gray-600 text-sm mb-3">{{ $badge->description }}</p>
                <span class="inline-block bg-gray-100 text-gray-700 rounded-full py-1 px-3 text-xs">
                    {{ $badge->threshold }} {{ $badge->type === 'lesson' ? 'leçons' : ($badge->type === 'streak' ? 'jours' : 'exercices') }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    @if($nextBadges->isNotEmpty())
    <div class="mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Prochains badges à débloquer</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            @foreach ($nextBadges as $badge)
            <div class="bg-gray-50 rounded-2xl border border-gray-200 p-5">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center opacity-70">
                        <i class="{{ $badge->icon }} text-xl text-gray-500"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="font-bold text-gray-800">{{ $badge->name }}</h3>
                        <p class="text-gray-600 text-sm">{{ $badge->description }}</p>
                    </div>
                </div>
                
                @php $progress = $this->calculateProgress(Auth::user(), $badge) @endphp
                <div class="text-sm text-gray-700 mb-1">
                    Progression: {{ $progress['current'] }}/{{ $progress['target'] }}
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-{{ $badge->color }}-400 to-{{ $badge->color }}-500 h-2 rounded-full" 
                         style="width: {{ $progress['percentage'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection