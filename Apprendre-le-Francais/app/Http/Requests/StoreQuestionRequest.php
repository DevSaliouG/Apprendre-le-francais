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
    $newFormat = $this->input('format_reponse', 'choix_multiple');
    
    $rules = [
        'texte' => 'required|string|max:255',
        'format_reponse' => 'required|in:choix_multiple,texte_libre,audio',
        'fichier_audio' => 'nullable|file|mimes:mp3,wav|max:2048',
    ];

    if ($newFormat === 'choix_multiple') {
        $rules['choix'] = 'required|array|min:2';
        $rules['choix.*'] = 'required|string|max:255';
        $rules['reponse_correcte'] = 'required|integer|min:0|max:3';
    } else {
        $rules['reponse_correcte'] = 'required|string|max:255';
    }

    return $rules;
}
}