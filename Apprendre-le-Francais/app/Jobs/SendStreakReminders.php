<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Mail\StreakReminderEmail;
use Illuminate\Support\Facades\Mail as FacadesMail;
use Mail;

class SendStreakReminders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $yesterday = now()->subDay();
        
        $users = User::whereHas('learningStreak', function($query) use ($yesterday) {
            $query->where('last_activity_date', $yesterday->format('Y-m-d'))
                  ->where('break_risk_alerted', false);
        })->get();

        foreach ($users as $user) {
            FacadesMail::to($user->email)->send(new StreakReminderEmail($user));
            $user->learningStreak->update(['break_risk_alerted' => true]);
        }
    }
}