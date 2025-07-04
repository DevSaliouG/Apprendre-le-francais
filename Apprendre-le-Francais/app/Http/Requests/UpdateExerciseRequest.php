<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UpdateExerciseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $exercise = $this->route('exercise');
        
        return [
            'lesson_id' => 'required|exists:lessons,id',
            'type' => ['required', Rule::in(['écrit', 'oral'])],
            'difficulty' => 'required|integer|min:1|max:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'lesson_id.required' => 'La leçon associée est obligatoire.',
            'lesson_id.exists' => 'La leçon sélectionnée est invalide.',
            'type.required' => 'Le type d\'exercice est obligatoire.',
            'type.in' => 'Le type d\'exercice doit être "écrit" ou "oral".',
            'difficulty.required' => 'La difficulté est obligatoire.',
            'difficulty.integer' => 'La difficulté doit être un nombre entier.',
            'difficulty.min' => 'La difficulté doit être au moins 1.',
            'difficulty.max' => 'La difficulté ne peut pas dépasser 5.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Si on met à jour sans changer le fichier audio, conservez l'ancien chemin
        if (!$this->hasFile('audio_path')) {
            $this->merge([
                'audio_path' => $this->route('exercise')->audio_path
            ]);
        }
    }
}