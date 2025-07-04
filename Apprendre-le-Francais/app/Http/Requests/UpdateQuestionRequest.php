<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /*  public function rules()
    {
        $question = $this->route('question');
        
        return [
            'texte' => 'required|string|max:255',
            'choix' => 'required|array|min:2',
            'choix.*' => 'required|string|max:255',
            'reponse_correcte' => 'required|integer|min:0|max:4', // 0-3 pour les 4 options
            'fichier_audio' => [
                'nullable',
                'file',
                'mimes:mp3,wav',
                'max:2048',
                function ($attribute, $value, $fail) use ($question) {
                    if ($question->exercise->type === 'oral' && !$value && !$question->fichier_audio) {
                        $fail('Un fichier audio est requis pour les questions d\'exercices oraux.');
                    }
                },
            ],
        ];
    } */

    public function rules()
    {
        $question = $this->route('question');
        $newFormat = $this->input('format_reponse', $question->format_reponse);

        $rules = [
            'texte' => 'required|string|max:255',
            'format_reponse' => 'required|in:choix_multiple,texte_libre,audio',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
        ];

        // Règles conditionnelles
        if ($newFormat === 'choix_multiple') {
            $rules['choix'] = 'required|array|min:2';
            $rules['choix.*'] = 'required|string|max:255';
            $rules['reponse_correcte'] = 'required|integer|min:0|max:3';
        } else {
            $rules['reponse_correcte'] = 'required|string|max:255';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'texte.required' => 'Le texte de la question est obligatoire.',
            'choix.required' => 'Les options de réponse sont obligatoires.',
            'choix.min' => 'Au moins 2 options de réponse sont nécessaires.',
            'choix.*.required' => 'Chaque option de réponse doit être remplie.',
            'reponse_correcte.required' => 'La réponse correcte doit être sélectionnée.',
            'fichier_audio.mimes' => 'Le fichier audio doit être au format MP3 ou WAV.',
            'fichier_audio.max' => 'Le fichier audio ne doit pas dépasser 2 Mo.',
        ];
    }

    protected function prepareForValidation()
    {
        if (!$this->hasFile('fichier_audio')) {
            $this->merge([
                'fichier_audio' => $this->route('question')->fichier_audio
            ]);
        }
    }
}
