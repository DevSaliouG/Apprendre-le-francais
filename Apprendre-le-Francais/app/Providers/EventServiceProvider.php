<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use App\Listeners\SendAdminNotification;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\BadgeEarned::class => [
            SendAdminNotification::class,
        ],
        \App\Events\NewUserRegistered::class => [
            SendAdminNotification::class,
        ],
        \App\Events\LevelUp::class => [
            SendAdminNotification::class,
        ],
        \App\Events\ExerciseCompleted::class => [
            SendAdminNotification::class,
        ],
    ];
}