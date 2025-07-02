@component('mail::message')
# Bonjour {{ $user->name }},

Vous avez manqué une journée hier et votre streak de **{{ $user->learningStreak->current_streak }} jours** est en danger !

Revenez aujourd'hui pour sauver votre série et continuer votre progression.

@component('mail::button', ['url' => route('dashboard')])
Reprendre maintenant
@endcomponent

Ne laissez pas votre effort s'arrêter ! 💪

L'équipe de {{ config('app.name') }}
@endcomponent