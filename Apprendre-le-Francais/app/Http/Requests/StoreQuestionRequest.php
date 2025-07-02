<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'texte' => 'required|string|max:255',
            'choix' => 'required|array|min:2',
            'choix.*' => 'required|string|max:255',
            'reponse_correcte' => 'required|integer|min:0|max:3',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
        ];
    }
}