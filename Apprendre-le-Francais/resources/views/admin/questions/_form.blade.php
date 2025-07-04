<div class="card shadow-sm mb-4">
    <div class="card-body">
        @if (!isset($question))
            <input type="hidden" name="exercise_id" value="{{ $exercise->id }}">
        @endif

        <!-- Champ caché pour l'ancien format (seulement en édition) -->
        @if(isset($question))
            <input type="hidden" name="old_format" value="{{ $question->format_reponse }}">
        @endif

        <div class="mb-4">
            <label class="form-label fw-bold">Format de réponse</label>
            <select name="format_reponse" id="format_reponse" class="form-select" required>
                <option value="choix_multiple" {{ (isset($question) && $question->format_reponse === 'choix_multiple') ? 'selected' : '' }}>Choix multiple</option>
                <option value="texte_libre" {{ (isset($question) && $question->format_reponse === 'texte_libre') ? 'selected' : '' }}>Texte libre</option>
                <option value="audio" {{ (isset($question) && $question->format_reponse === 'audio') ? 'selected' : '' }}>Réponse audio</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold">Texte de la question</label>
            <textarea name="texte" id="texte" required rows="3" class="form-control">{{ $question->texte ?? old('texte') }}</textarea>
        </div>

        <!-- Section Options (visible seulement pour choix multiple) -->
        <div id="options-section" class="{{ (!isset($question) || $question->format_reponse === 'choix_multiple') ? '' : 'd-none' }}">
            <label class="form-label fw-bold">Options de réponse</label>
            @for ($i = 0; $i < 4; $i++)
                <div class="input-group mb-2">
                    <div class="input-group-text">
                        <input type="radio" name="reponse_correcte" value="{{ $i }}"
                            {{ (isset($question) && $question->reponse_correcte == $i) ? 'checked' : '' }}
                            class="form-check-input mt-0">
                    </div>
                    <input type="text" name="choix[]"
                        value="{{ 
                            isset($question) && $question->format_reponse === 'choix_multiple' 
                                ? ($question->choix[$i] ?? '') 
                                : (old('choix.' . $i) ?? '') 
                        }}"
                        placeholder="Option {{ $i + 1 }}" 
                        class="form-control">
                </div>
            @endfor
        </div>

        <!-- Section Réponse texte (visible seulement pour texte libre) -->
        <div id="texte-section" class="{{ (isset($question) && $question->format_reponse === 'texte_libre') ? '' : 'd-none' }}">
            <div class="mb-4">
                <label class="form-label fw-bold">Réponse correcte</label>
                  <input type="text" name="reponse_correcte"
                    value="{{ isset($question) && $question->format_reponse === 'texte_libre' ? $question->reponse_correcte : old('reponse_correcte') }}"
                    class="form-control" placeholder="La réponse attendue">
            </div>
        </div>

        <!-- Section Audio (visible seulement pour réponse audio) -->
        <div id="audio-section" class="{{ (isset($question) && $question->format_reponse === 'audio') ? '' : 'd-none' }}">
            <div class="mb-4">
                <label class="form-label fw-bold">Réponse correcte (transcription)</label>
                <input type="text" name="reponse_correcte"
                    value="{{ 
                        (isset($question) && $question->format_reponse === 'audio')
                            ? $question->reponse_correcte 
                            : old('reponse_correcte') 
                    }}"
                    class="form-control" placeholder="Transcription attendue">
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold">Fichier audio complémentaire</label>
                <input type="file" name="fichier_audio" id="fichier_audio" class="form-control">
                
                @if (isset($question) && $question->fichier_audio)
                    <div class="mt-2">
                        <audio controls class="mt-2">
                            <source src="{{ Storage::url($question->fichier_audio) }}" type="audio/mpeg">
                        </audio>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_audio" id="remove_audio">
                            <label class="form-check-label text-danger" for="remove_audio">
                                Supprimer le fichier audio
                            </label>
                        </div>
                        <input type="hidden" name="existing_audio" value="{{ $question->fichier_audio }}">
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const formatSelect = document.getElementById('format_reponse');
        const optionsSection = document.getElementById('options-section');
        const texteSection = document.getElementById('texte-section');
        const audioSection = document.getElementById('audio-section');

        function disableInputs(section, enable) {
            const inputs = section.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = !enable;
            });
        }

        function toggleSections() {
            const selectedFormat = formatSelect.value;

            disableInputs(optionsSection, false);
            disableInputs(texteSection, false);
            disableInputs(audioSection, false);
            optionsSection.classList.add('d-none');
            texteSection.classList.add('d-none');
            audioSection.classList.add('d-none');

            if (selectedFormat === 'choix_multiple') {
                optionsSection.classList.remove('d-none');
                disableInputs(optionsSection, true);
            } else if (selectedFormat === 'texte_libre') {
                texteSection.classList.remove('d-none');
                disableInputs(texteSection, true);
            } else if (selectedFormat === 'audio') {
                audioSection.classList.remove('d-none');
                disableInputs(audioSection, true);
            }
        }

        formatSelect.addEventListener('change', toggleSections);
        toggleSections();
    });
</script>
@endpush