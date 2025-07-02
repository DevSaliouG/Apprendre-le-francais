@props(['status' => 'todo'])

@php
    $config = [
        'todo' => [
            'text' => 'À commencer',
            'color' => 'bg-gray-100 text-gray-800',
            'icon' => 'fas fa-clock',
            'label' => 'Exercice à commencer'
        ],
        'in-progress' => [
            'text' => 'En cours',
            'color' => 'bg-blue-100 text-blue-800',
            'icon' => 'fas fa-spinner',
            'label' => 'Exercice en cours'
        ],
        'completed' => [
            'text' => 'Terminé',
            'color' => 'bg-green-100 text-green-800',
            'icon' => 'fas fa-check-circle',
            'label' => 'Exercice terminé'
        ]
    ];
    
    $current = $config[$status] ?? $config['todo'];
@endphp

<span 
    class="inline-flex items-center {{ $current['color'] }} rounded-full py-1 px-3 text-xs font-medium"
    aria-label="{{ $current['label'] }}"
>
    <i class="{{ $current['icon'] }} me-1" aria-hidden="true"></i>
    {{ $current['text'] }}
</span>