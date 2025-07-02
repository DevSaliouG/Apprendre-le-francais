<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExerciseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'lesson_id' => 'required|exists:lessons,id',
            'type' => 'required|in:écrit,oral',
            'difficulty' => 'required|integer|min:1|max:5',
            'audio_path' => 'nullable|file|mimes:mp3,wav|max:2048',
        ];
    }
}