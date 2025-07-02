<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Question;

class FixQuestionChoices extends Command
{
    protected $signature = 'fix:question-choices';
    protected $description = 'Fix malformed question choices';

    public function handle()
    {
        Question::chunk(200, function ($questions) {
            foreach ($questions as $question) {
                if ($question->format_reponse === 'choix_multiple') {
                    // Corriger les chaînes mal formatées
                    if (is_string($question->choix) && 
                        preg_match('/^\[".*"\]$/', $question->choix)) {
                        $question->choix = json_decode($question->choix, true);
                        $question->save();
                    }
                }
            }
        });
        
        $this->info('Question choices fixed successfully!');
    }
}