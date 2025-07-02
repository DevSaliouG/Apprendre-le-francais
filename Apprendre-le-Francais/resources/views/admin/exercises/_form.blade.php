<div class="mb-6">
    <label class="block text-gray-700 font-medium mb-2" for="lesson_id">
        Leçon associée
    </label>
    <select name="lesson_id" id="lesson_id" required
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light">
        @foreach($lessons as $lesson)
        <option value="{{ $lesson->id }}" 
            {{ isset($exercise) && $exercise->lesson_id === $lesson->id ? 'selected' : '' }}>
            {{ $lesson->title }}
        </option>
        @endforeach
    </select>
</div>

<div class="mb-6">
    <label class="block text-gray-700 font-medium mb-2" for="type">
        Type d'exercice
    </label>
    <select name="type" id="type" required
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light">
        <option value="écrit" {{ isset($exercise) && $exercise->type === 'écrit' ? 'selected' : '' }}>Écrit</option>
        <option value="oral" {{ isset($exercise) && $exercise->type === 'oral' ? 'selected' : '' }}>Oral</option>
    </select>
</div>

<div class="mb-6">
    <label class="block text-gray-700 font-medium mb-2" for="difficulty">
        Difficulté (1-5)
    </label>
    <input type="number" name="difficulty" id="difficulty" min="1" max="5" 
           value="{{ $exercise->difficulty ?? old('difficulty') }}" required
           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light">
</div>

<div class="mb-6">
    <label class="block text-gray-700 font-medium mb-2" for="audio_path">
        Fichier audio (uniquement pour exercices oraux)
    </label>
    <input type="file" name="audio_path" id="audio_path"
           class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-light">
</div>