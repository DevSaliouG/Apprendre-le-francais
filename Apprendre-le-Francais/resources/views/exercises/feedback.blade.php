@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-xl overflow-hidden">
                <div class="card-header bg-gradient-to-r from-purple-50 to-indigo-50 border-0 py-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 font-bold text-gray-800">Feedback détaillé</h5>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-xl py-2 px-4 border-2">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                    </div>
                </div>

                <div class="card-body p-5">
                    <div class="mb-5">
                        <h6 class="text-gray-600 mb-3">Question :</h6>
                        <p class="mb-0 text-gray-800 bg-gray-50 p-4 rounded-xl">{{ $question->texte }}</p>
                    </div>
                    
                    <div class="mb-5">
                        <h6 class="text-gray-600 mb-3">Votre réponse :</h6>
                        <div class="bg-gray-50 p-4 rounded-xl">
                            @if($question->format_reponse === 'audio' && $result->audio_response)
                            <audio controls class="w-full rounded-xl shadow-sm">
                                <source src="{{ Storage::url($result->audio_response) }}" type="audio/mpeg">
                            </audio>
                            @else
                            <p class="mb-0 text-gray-800">{{ $result->reponse ?? 'Aucune réponse fournie' }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <h6 class="text-gray-600 mb-3">Réponse correcte :</h6>
                        <p class="mb-0 text-gray-800 bg-gray-50 p-4 rounded-xl">{{ $question->reponse_correcte }}</p>
                    </div>
                    
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-3">
                            <h6 class="text-gray-600 mb-0">Statut :</h6>
                            <span class="badge rounded-pill py-2 px-3 ms-3 bg-gradient-to-r {{ $result->correct ? 'from-green-500 to-teal-500' : 'from-pink-500 to-rose-500' }} text-white">
                                {{ $result->correct ? 'Correct' : 'Incorrect' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-5">
                        <h5 class="mb-4 font-bold text-gray-800 flex items-center">
                            <i class="fas fa-lightbulb me-3 text-2xl {{ $result->correct ? 'text-green-500' : 'text-yellow-500' }}"></i>
                            {{ $result->correct ? 'Bonne réponse !' : 'Explication' }}
                        </h5>
                        <div class="alert rounded-xl border-l-4 {{ $result->correct ? 'border-green-500 bg-green-50' : 'border-blue-500 bg-blue-50' }} p-4">
                            <p class="mb-0 text-gray-700">{{ $explanation['message'] }}</p>
                        </div>
                        
                        @if(!$result->correct)
                        <div class="mt-6">
                            <h6 class="mb-4 font-bold text-gray-800 flex items-center">
                                <i class="fas fa-book me-3 text-purple-500"></i>Ressources pour vous améliorer
                            </h6>
                            <div class="space-y-3">
                                @foreach($explanation['resources'] as $resource)
                                <a href="{{ $resource['url'] }}" class="block p-4 bg-white border border-gray-200 rounded-xl hover:border-purple-300 hover:shadow-sm transition-all duration-300">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-external-link-alt me-4 text-purple-500"></i>
                                        <div class="font-medium text-gray-800">{{ $resource['title'] }}</div>
                                        <i class="fas fa-chevron-right ms-auto text-gray-400"></i>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection