@php
    // Définition sécurisée des variables
    $currentIndex = $currentIndex ?? 0;
    $totalQuestions = $questions->count() ?? 0;
    $questionNumber = $currentIndex + 1;
@endphp

<div class="mb-4">
    <h5 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
        <span class="bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-full w-8 h-8 flex items-center justify-center">
            {{ $questionNumber }}
        </span>
        Question {{ $questionNumber }}/{{ $totalQuestions }}
    </h5>
    <p class="text-gray-800 mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">{{ $question->texte }}</p>
</div>

@if($question->fichier_audio)
<div class="mb-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
    <p class="font-medium text-gray-700 mb-2 flex items-center gap-2">
        <i class="fas fa-headphones text-purple-500"></i> Écoutez l'audio :
    </p>
    <audio controls class="w-full rounded-xl shadow-sm">
        <source src="{{ Storage::url($question->fichier_audio) }}" type="audio/mpeg">
    </audio>
</div>
@endif

<form id="question-form" data-question-id="{{ $question->id }}" class="bg-gray-50 p-5 rounded-xl border border-gray-100">
    @if($question->format_reponse === 'choix_multiple')
        <div class="mb-4">
            <p class="font-medium text-gray-700 mb-3 flex items-center gap-2">
                <i class="fas fa-check-circle text-purple-500"></i> Choisissez la bonne réponse :
            </p>
            <div class="choices-container grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($question->clean_choix as $choice)
                <div class="choice-button-container">
                    <input type="radio" name="reponse" value="{{ $choice }}" 
                           id="choice_{{ $question->id }}_{{ $loop->index }}"
                           class="hidden peer">
                    <label for="choice_{{ $question->id }}_{{ $loop->index }}" 
                           class="block p-4 border border-gray-200 rounded-xl text-center cursor-pointer transition-all duration-300
                                  peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-sm
                                  hover:border-purple-300 hover:shadow-sm">
                        {{ $choice }}
                    </label>
                </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="mb-4">
            <p class="font-medium text-gray-700 mb-2 flex items-center gap-2">
                <i class="fas fa-edit text-purple-500"></i> Votre réponse :
            </p>
            <textarea name="reponse" rows="3"
                      class="w-full p-3 border border-gray-200 rounded-xl focus:border-purple-500 focus:ring focus:ring-purple-100
                             transition-all duration-300 hover:shadow-sm"
                      placeholder="Écrivez votre réponse ici...">{{ $userResult->reponse ?? '' }}</textarea>
        </div>
    @endif

    <div class="mt-6">
        <button type="submit" 
                class="btn bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-3 px-6 w-full
                       shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
            <i class="fas fa-paper-plane me-2"></i> Valider la réponse
        </button>
    </div>
</form>