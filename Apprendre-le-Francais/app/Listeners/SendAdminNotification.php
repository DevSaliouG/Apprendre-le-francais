<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\AdminNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminNotification implements ShouldQueue
{
    public function handle($event)
    {
        $admin = User::where('is_admin', true)->first();
        
        if ($admin) {
            $admin->notify(new AdminNotification($event));
        }
    }
}