@if(!isset($question))
    <input type="hidden" name="exercise_id" value="{{ $exercise->id }}">
@endif

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="texte">
        Texte de la question
    </label>
    <textarea name="texte" id="texte" required rows="3"
        class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $question->texte ?? old('texte') }}</textarea>
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Options de réponse
    </label>
    @for($i = 0; $i < 4; $i++)
    <div class="flex items-center mb-2">
        <input type="radio" name="reponse_correcte" value="{{ $i }}" 
               {{ (isset($question) && $question->reponse_correcte == $i) ? 'checked' : '' }}
               class="mr-2">
        <input type="text" name="choix[]" 
               value="{{ isset($question) ? $question->choix[$i] ?? '' : old('choix.'.$i) }}" 
               placeholder="Option {{ $i+1 }}" required
               class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    @endfor
</div>

<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2" for="fichier_audio">
        Fichier audio complémentaire
    </label>
    <input type="file" name="fichier_audio" id="fichier_audio"
           class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
</div>