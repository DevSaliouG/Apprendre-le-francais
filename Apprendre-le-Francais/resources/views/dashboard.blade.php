@extends('layouts.app')

@section('content')
    
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Bonjour, {{ auth()->user()->prenom }}!</h1>
        <p class="text-gray-600 mt-2">Continuez votre apprentissage du français</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Carte: Niveau actuel -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-500">
            <h2 class="text-xl font-semibold mb-2">Niveau Actuel</h2>
            <p class="text-2xl font-bold text-blue-500">{{ $currentLevel->name }}</p>
            <p class="text-gray-600 mt-1">{{ $currentLevel->description }}</p>
        </div>

        <!-- Carte: Streak -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-yellow-500">
            <h2 class="text-xl font-semibold mb-2">Streak</h2>
            <p class="text-2xl font-bold text-yellow-500">{{ $stats['streak'] }} jours</p>
            <p class="text-gray-600 mt-1">Votre série actuelle</p>
        </div>

        <!-- Carte: Badges -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-500">
            <h2 class="text-xl font-semibold mb-2">Badges</h2>
            <p class="text-2xl font-bold text-purple-500">{{ $stats['badges'] }}</p>
            <p class="text-gray-600 mt-1">Badges obtenus</p>
        </div>

        <!-- Carte: Leçons complétées -->
        <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-500">
            <h2 class="text-xl font-semibold mb-2">Progression</h2>
            <p class="text-2xl font-bold text-green-500">{{ $stats['completed_lessons'] }} leçons</p>
            <p class="text-gray-600 mt-1">Terminées avec succès</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Section: Progression -->
        <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
            <h2 class="text-2xl font-semibold mb-6">Votre Progression</h2>
            
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-lg font-medium">Compétences Écrites</span>
                        <span class="text-lg font-bold text-blue-600">{{ $stats['writing'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $stats['writing'] }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-lg font-medium">Compétences Orales</span>
                        <span class="text-lg font-bold text-green-600">{{ $stats['speaking'] }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-green-600 h-4 rounded-full" style="width: {{ $stats['speaking'] }}%"></div>
                    </div>
                </div>
                
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-lg font-medium">Compréhension</span>
                        <span class="text-lg font-bold text-purple-600">{{ ($stats['writing'] + $stats['speaking']) / 2 }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-4">
                        <div class="bg-purple-600 h-4 rounded-full" style="width: {{ ($stats['writing'] + $stats['speaking']) / 2 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section: Leçon recommandée -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Leçon Recommandée</h2>
            
            @if($recommendedLesson)
            <div class="border rounded-lg overflow-hidden">
                <div class="bg-blue-100 p-4">
                    <h3 class="text-xl font-bold">{{ $recommendedLesson->title }}</h3>
                    <p class="text-gray-700">{{ Str::limit($recommendedLesson->content, 100) }}</p>
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">
                            {{ $recommendedLesson->exercises->count() }} exercices
                        </span>
                        <a href="{{ route('lessons.show', $recommendedLesson) }}" 
                           class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                            Commencer
                        </a>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-8 text-gray-500">
                <p>Toutes les leçons sont complétées!</p>
                <p class="mt-2">Bravo pour votre progression.</p>
            </div>
            @endif
        </div>
    </div>
         <!-- Section Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Graphique 1: Évolution du streak -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Évolution de votre streak</h2>
                <span class="text-sm text-gray-500">30 derniers jours</span>
            </div>
            <div class="relative h-72">
                <canvas id="streakChart" aria-label="Évolution de votre série d'apprentissage" role="img"></canvas>
                <div class="absolute inset-0 flex items-center justify-center" id="streakLoader">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>

        <!-- Graphique 2: Répartition des badges -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Répartition de vos badges</h2>
            <div class="relative h-72">
                <canvas id="badgesChart" aria-label="Répartition des badges obtenus" role="img"></canvas>
                <div class="absolute inset-0 flex items-center justify-center" id="badgesLoader">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-purple-500"></div>
                </div>
            </div>
        </div>

        <!-- Graphique 3: Leçons par semaine -->
        <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Leçons complétées par semaine</h2>
                <span class="text-sm text-gray-500">8 dernières semaines</span>
            </div>
            <div class="relative h-72">
                <canvas id="lessonsChart" aria-label="Leçons complétées par semaine" role="img"></canvas>
                <div class="absolute inset-0 flex items-center justify-center" id="lessonsLoader">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-green-500"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nouvelle Section: Compétences -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Graphique 4: Progression par compétence -->
        <div class="bg-white rounded-lg shadow-lg p-6 lg:col-span-2">
            <h2 class="text-xl font-semibold mb-4">Progression par compétence</h2>
            <div class="relative h-80">
                <canvas id="skillsChart" aria-label="Progression par compétence" role="img"></canvas>
                <div class="absolute inset-0 flex items-center justify-center" id="skillsLoader">
                    <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-orange-500"></div>
                </div>
            </div>
        </div>
    <!-- Section: Activités récentes -->
    <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
        <h2 class="text-2xl font-semibold mb-6">Activités Récentes</h2>
            
        <div class="space-y-4">
            @forelse($activities as $activity)
            <div class="flex items-start border-b pb-4">
                <div class="mr-4 mt-1 text-blue-500">
                    @switch($activity->activity_type)
                        @case('login')
                            <i class="fas fa-sign-in-alt fa-lg"></i>
                            @break
                        @case('lesson_completed')
                            <i class="fas fa-book fa-lg"></i>
                            @break
                        @case('exercise_completed')
                            <i class="fas fa-check-circle fa-lg"></i>
                            @break
                        @case('badge_earned')
                            <i class="fas fa-trophy fa-lg"></i>
                            @break
                        @default
                            <i class="fas fa-history fa-lg"></i>
                    @endswitch
                </div>
                <div>
                    <p class="font-medium">
                        {{ $activity->getActivityDescription() }}
                    </p>
                    <p class="text-gray-500 text-sm">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-4">Aucune activité récente</p>
            @endforelse
        </div>
        
        @if($activities->hasMorePages())
        <div class="mt-6 text-center">
            
            <a href="{{ route('activities') }}" class="text-blue-500 hover:underline">
                Voir toutes les activités
            </a>
        </div>
        @endif
    </div>      
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données des graphiques
    const chartData = @json($chartData);

    // Options communes
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    font: {
                        size: 12,
                        family: "'Inter', sans-serif"
                    },
                    padding: 15,
                    usePointStyle: true
                }
            },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                padding: 12,
                titleFont: {
                    size: 14,
                    weight: '600'
                },
                bodyFont: {
                    size: 13
                },
                callbacks: {
                    label: function(context) {
                        return `${context.dataset.label}: ${context.parsed.y || context.parsed}`;
                    }
                }
            }
        }
    };

    // Fonction pour masquer les loaders
    const hideLoader = (id) => {
        document.getElementById(id).style.display = 'none';
    };

    // 1. Graphique de streak (line)
    new Chart(document.getElementById('streakChart'), {
        type: 'line',
        data: {
            labels: chartData.streak.labels,
            datasets: [{
                label: 'Votre série',
                data: chartData.streak.data,
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                borderWidth: 3,
                tension: 0.3,
                fill: true,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    title: { 
                        display: true,
                        text: 'Jours consécutifs',
                        font: { size: 12 }
                    }
                },
                x: {
                    grid: { display: false },
                    title: { 
                        display: true,
                        text: 'Dates',
                        font: { size: 12 }
                    }
                }
            }
        }
    });
    hideLoader('streakLoader');

    // 2. Graphique de badges (doughnut)
    new Chart(document.getElementById('badgesChart'), {
        type: 'doughnut',
        data: {
            labels: chartData.badges.labels,
            datasets: [{
                data: chartData.badges.data,
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899'
                ],
                borderWidth: 0,
                hoverOffset: 15
            }]
        },
        options: {
            ...commonOptions,
            cutout: '70%',
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.label}: ${context.parsed} badges`;
                        }
                    }
                }
            }
        }
    });
    hideLoader('badgesLoader');

    // 3. Graphique de leçons (bar)
    new Chart(document.getElementById('lessonsChart'), {
        type: 'bar',
        data: {
            labels: chartData.lessons.labels,
            datasets: [{
                label: 'Leçons complétées',
                data: chartData.lessons.data,
                backgroundColor: '#10B981',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    title: { 
                        display: true,
                        text: 'Nombre de leçons',
                        font: { size: 12 }
                    }
                },
                x: {
                    grid: { display: false },
                    title: { 
                        display: true,
                        text: 'Semaines',
                        font: { size: 12 }
                    }
                }
            }
        }
    });
    hideLoader('lessonsLoader');

    // 4. Graphique de compétences (radar)
    new Chart(document.getElementById('skillsChart'), {
        type: 'radar',
        data: {
            labels: chartData.skills.labels,
            datasets: [{
                label: 'Vos compétences',
                data: chartData.skills.data,
                backgroundColor: 'rgba(59, 130, 246, 0.2)',
                borderColor: '#3B82F6',
                borderWidth: 2,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#fff',
                pointHoverRadius: 6
            }]
        },
        options: {
            ...commonOptions,
            scales: {
                r: {
                    angleLines: { color: 'rgba(0, 0, 0, 0.1)' },
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    suggestedMin: 0,
                    suggestedMax: 100,
                    ticks: {
                        stepSize: 20,
                        backdropColor: 'transparent'
                    },
                    pointLabels: {
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                }
            }
        }
    });
    hideLoader('skillsLoader');
});
</script>
@endpush