<div class="card shadow-lg border-0 rounded-xl overflow-hidden">
    <div class="card-header bg-gradient-to-r from-purple-50 to-indigo-50 border-0 py-4">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0 text-gray-800 font-bold">{{ $exercise->lesson->title }}</h4>
            <span class="badge rounded-pill bg-white text-gray-700 border border-gray-200 py-2 px-3">
                <i class="fas fa-question-circle me-1 text-purple-500"></i>
                Exercice en cours
            </span>
        </div>
    </div>

    <div class="card-body bg-gray-50">
        <form id="exercise-form">
            <div class="questions-container">
                @foreach($questions as $index => $question)
                    <div class="question-card mb-5 p-5 bg-white rounded-xl border border-gray-100 shadow-sm" 
                         data-question-id="{{ $question->id }}">
                        <h5 class="font-bold text-gray-800 mb-3">Question {{ $index+1 }}</h5>
                        <p class="mb-4 text-gray-700">{{ $question->texte }}</p>
                        
                        @if($question->format_reponse === 'choix_multiple')
                            <div class="space-y-3">
                                @foreach($question->choix as $key => $choice)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="answer" 
                                               id="choice-{{ $question->id }}-{{ $key }}"
                                               value="{{ $choice }}">
                                        <label class="form-check-label text-gray-700" for="choice-{{ $question->id }}-{{ $key }}">
                                            {{ $choice }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="form-group">
                                <input type="text" class="form-control rounded-xl py-3 px-4 border-gray-300" 
                                       name="answer">
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
            
            <div class="text-center mt-6">
                <button type="submit" class="btn bg-gradient-to-r from-purple-500 to-indigo-500 text-white rounded-xl py-3 px-6 shadow-md hover:shadow-lg transition">
                    Soumettre mes réponses <i class="fas fa-paper-plane ms-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>