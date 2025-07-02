<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function rules()
    {
        return [
            'texte' => 'required|string',
            'choix' => 'required_if:type,choix_multiple|array|min:2',
            'choix.*' => 'required|string',
            'reponse_correcte' => 'required',
            'fichier_audio' => 'nullable|file|mimes:mp3,wav|max:2048'
        ];
    }
}