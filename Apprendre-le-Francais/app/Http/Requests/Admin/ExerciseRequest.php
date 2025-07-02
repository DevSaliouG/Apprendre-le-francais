<?php 

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ExerciseRequest extends FormRequest
{
    public function rules()
    {
        return [
            'lesson_id' => 'required|exists:lessons,id',
            'type' => 'required|in:écrit,oral',
            'difficulty' => 'required|integer|between:1,5',
            'audio_path' => 'nullable|file|mimes:mp3,wav|max:2048'
        ];
    }
}