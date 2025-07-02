@props([
    'value' => 0,
    'color' => 'from-purple-500 to-indigo-500',
    'height' => 'h-2',
    'class' => ''
])

@php
    // Assurer que la valeur est entre 0 et 100
    $value = min(100, max(0, (int)$value));
@endphp

<div class="w-full bg-gray-200 rounded-full overflow-hidden {{ $height }} {{ $class }}">
    <div 
        class="bg-gradient-to-r {{ $color }} {{ $height }} rounded-full progress-fill"
        style="width: {{ $value }}%;"
        role="progressbar"
        aria-valuenow="{{ $value }}"
        aria-valuemin="0"
        aria-valuemax="100"
    ></div>
</div>