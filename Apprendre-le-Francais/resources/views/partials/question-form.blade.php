<div class="question-card border p-4 mb-4 rounded bg-gray-50">
    @if($question)
        <input type="hidden" name="questions[{{ $question->id }}][id]" value="{{ $question->id }}">
    @else
        <input type="hidden" name="questions[__index__][new]" value="1">
    @endif
    
    <div class="flex justify-between items-start mb-3">
        <h3 class="font-medium">Question</h3>
        <button type="button" onclick="removeQuestion(this)" class="text-red-500">× Supprimer</button>
    </div>
    
    <div class="mb-3">
        <label class="block mb-1">Texte de la question</label>
        <textarea name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][texte]" 
                  class="w-full p-2 border rounded" required
        >{{ $question->texte ?? '' }}</textarea>
    </div>
    
    <div class="mb-3">
        <label class="block mb-1">Choix (un par ligne)</label>
        <textarea name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][choix]" 
                  class="w-full p-2 border rounded"
        >@isset($question){{ implode("\n", $question->choix) }}@endisset</textarea>
    </div>
    
    <div class="mb-3">
        <label class="block mb-1">Réponse correcte</label>
        <input type="text" name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][reponse_correcte]" 
               value="{{ $question->reponse_correcte ?? '' }}" 
               class="w-full p-2 border rounded" required>
    </div>
    
    <div class="mb-3">
        <label class="block mb-1">Fichier audio</label>
        <input type="file" name="questions[@isset($question){{ $question->id }}@else __index__ @endisset][fichier_audio]" 
               class="w-full p-2 border rounded">
    </div>
</div>