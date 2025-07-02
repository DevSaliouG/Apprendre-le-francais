<?php

namespace Database\Seeders;

use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\Level;
use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FullContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = json_decode(file_get_contents(__DIR__.'/data/content.json'), true);
    
    // Insérer les niveaux
    foreach ($data['levels'] as $level) {
        Level::create($level);
    }
    
    // Insérer les leçons
    foreach ($data['lessons'] as $lesson) {
        Lesson::create($lesson);
    }
    
    // Insérer les exercices
    foreach ($data['exercises'] as $exercise) {
        Exercise::create($exercise);
    }
    
    // Insérer les questions
    foreach ($data['questions'] as $question) {
        Question::create($question);
    }
    }
}
