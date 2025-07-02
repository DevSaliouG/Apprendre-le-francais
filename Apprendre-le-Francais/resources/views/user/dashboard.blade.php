{{-- resources/views/user/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Section d'accueil -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 mb-0">Bonjour, {{ $user->prenom }}!</h1>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                @if($currentLevel)
                    <span class="badge bg-primary fs-6 py-2">
                        <i class="fas fa-layer-group me-1"></i>Niveau: {{ $currentLevel->name }}
                    </span>
                @else
                    <span class="badge bg-warning fs-6 py-2">
                        <i class="fas fa-exclamation-circle me-1"></i>
                        Vous n'avez pas encore de niveau attribué
                    </span>
                    <a href="{{ route('test') }}" class="btn btn-sm btn-outline-secondary">
                        Passer le test de niveau
                    </a>
                @endif
                
                <div class="d-flex align-items-center text-dark">
                    <i class="fas fa-fire text-danger me-1"></i>
                    <span class="fw-medium">{{ $stats['streak'] }} jours consécutifs</span>
                </div>
            </div>
        </div>
        <div class="d-flex align-items-center">
            <div class="me-3">
                <div class="fs-5 fw-bold">{{ $stats['xp'] }}</div>
                <div class="text-muted small">Points XP</div>
            </div>
            <div class="avatar avatar-lg bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-star text-primary fs-4"></i>
            </div>
        </div>
    </div>

    <!-- Section Statistiques -->
    <div class="row g-4 mb-4">
        <!-- Badges -->
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Badges</h5>
                            <h3 class="card-text fw-bold">{{ $stats['badges'] }}</h3>
                        </div>
                        <div class="avatar bg-secondary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-medal text-secondary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fas fa-trophy text-warning me-1"></i>
                            <span>{{ $stats['badges'] > 0 ? round($stats['badges']/5*100) : 0 }}% de collection</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Taux de réussite -->
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Taux de réussite</h5>
                            <h3 class="card-text fw-bold">{{ $stats['successRate'] }}%</h3>
                        </div>
                        <div class="avatar bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-line text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        @if($stats['successRate'] >= 80)
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i> Excellent travail!
                            </span>
                        @elseif($stats['successRate'] >= 60)
                            <span class="badge bg-primary">
                                <i class="fas fa-thumbs-up me-1"></i> Continue comme ça!
                            </span>
                        @else
                            <span class="badge bg-warning">
                                <i class="fas fa-lightbulb me-1"></i> Tu peux t'améliorer!
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Objectif quotidien -->
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Objectif quotidien</h5>
                            <h3 class="card-text fw-bold">
                                {{ $stats['dailyGoal']['completed'] }}/{{ $stats['dailyGoal']['goal'] }}
                            </h3>
                        </div>
                        <div class="avatar bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-bullseye text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" 
                                 style="width: {{ $stats['dailyGoal']['percentage'] }}%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1 text-xs text-muted">
                            <span>Progression</span>
                            <span>{{ $stats['dailyGoal']['percentage'] }}%</span>
                        </div>
                        @if($stats['dailyGoal']['completed'] >= $stats['dailyGoal']['goal'])
                            <div class="mt-2 text-success fw-medium d-flex align-items-center">
                                <i class="fas fa-check-circle me-1"></i> Objectif atteint!
                            </div>
                        @else
                            <div class="mt-2 text-warning fw-medium d-flex align-items-center">
                                <i class="fas fa-running me-1"></i> Continue, il te reste {{ $stats['dailyGoal']['goal'] - $stats['dailyGoal']['completed'] }} activités
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Progression niveau -->
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 border-start border-4 border-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="card-title text-muted">Progression du niveau</h5>
                            <h3 class="card-text fw-bold">{{ $stats['levelProgress'] }}%</h3>
                        </div>
                        <div class="avatar bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                            <i class="fas fa-chart-pie text-info fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-info" style="width: {{ $stats['levelProgress'] }}%"></div>
                        </div>
                        <div class="mt-2 small text-muted">
                            @if($nextLevel)
                                Vers {{ $nextLevel->name }}
                            @else
                                Niveau maximum atteint
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Graphiques -->
    <div class="row g-4 mb-4">
        <!-- Graphique 1: Streak sur 30 jours -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Votre série d'apprentissage</h5>
                    <span class="badge bg-primary">30 jours</span>
                </div>
                <div class="card-body">
                    <canvas id="streakChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique 2: Badges par type -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Répartition de vos badges</h5>
                    <span class="badge bg-secondary">{{ $stats['badges'] }} badges</span>
                </div>
                <div class="card-body">
                    <canvas id="badgesChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique 3: Leçons par semaine -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Leçons complétées</h5>
                    <span class="badge bg-success">8 semaines</span>
                </div>
                <div class="card-body">
                    <canvas id="lessonsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique 4: Progression par compétence -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Compétences linguistiques</h5>
                    <span class="badge bg-warning">Maîtrise</span>
                </div>
                <div class="card-body">
                    <canvas id="skillsChart" height="250"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique 5: Activité hebdomadaire -->
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activité hebdomadaire</h5>
                    <span class="badge bg-primary">Cette semaine</span>
                </div>
                <div class="card-body">
                    <canvas id="weeklyActivityChart" height="150"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Dernières activités et exercices -->
    <div class="row g-4 mb-4">
        <!-- Dernières activités -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Activités récentes</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Voir tout</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($activities as $activity)
                            <div class="list-group-item border-0 px-0 py-3">
                                <div class="d-flex">
                                    <div class="avatar avatar-md bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3">
                                        @if($activity->activity_type == 'lesson_completed')
                                            <i class="fas fa-book"></i>
                                        @elseif($activity->activity_type == 'exercise_completed')
                                            <i class="fas fa-pen"></i>
                                        @elseif($activity->activity_type == 'level_up')
                                            <i class="fas fa-trophy"></i>
                                        @else
                                            <i class="fas fa-check-circle"></i>
                                        @endif
                                    </div>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1">
                                            @if($activity->activity_type == 'lesson_completed')
                                                Leçon complétée
                                            @elseif($activity->activity_type == 'exercise_completed')
                                                Exercice terminé
                                            @elseif($activity->activity_type == 'level_up')
                                                Niveau supérieur atteint!
                                            @else
                                                Activité enregistrée
                                            @endif
                                        </h6>
                                        <small class="text-muted">
                                            <i class="far fa-clock me-1"></i>
                                            {{ $activity->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Aucune activité récente</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Derniers exercices -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nouveaux exercices</h5>
                    <a href="#" class="btn btn-sm btn-outline-primary">Explorer</a>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($latestExercises as $exercise)
                            <div class="list-group-item border-0 px-0 py-3 border-start border-3 border-primary">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $exercise->lesson->title ?? 'Exercice' }}</h6>
                                        <div class="d-flex gap-2 mt-2">
                                            <span class="badge bg-light text-dark">
                                                @if($exercise->type == 'oral')
                                                    <i class="fas fa-microphone me-1"></i> Oral
                                                @else
                                                    <i class="fas fa-pen me-1"></i> Écrit
                                                @endif
                                            </span>
                                            <span class="badge bg-light text-dark">
                                                <i class="fas fa-star text-warning me-1"></i>
                                                {{ $exercise->getDifficultyStarsAttribute() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div>
                                        <a href="#" class="btn btn-sm btn-primary">
                                            Commencer
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="fas fa-book-open fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Aucun nouvel exercice disponible</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progression vers le prochain niveau -->
    <div class="card mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Progression vers le prochain niveau</h5>
            @if($nextLevel)
                <span class="badge bg-secondary fs-6 py-2">
                    {{ $nextLevel->name }}
                </span>
            @endif
        </div>
        <div class="card-body">
            <div class="mb-4">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Niveau actuel: {{ $currentLevel->name ?? 'Non défini' }}</span>
                    <span class="fw-bold">{{ $stats['levelProgress'] }}%</span>
                </div>
                <div class="progress" style="height: 1.5rem;">
                    <div class="progress-bar bg-primary progress-bar-striped progress-bar-animated" 
                         style="width: {{ $stats['levelProgress'] }}%"></div>
                </div>
            </div>
            
            @if($nextLevel)
                <div class="text-center py-3">
                    <p class="text-muted mb-3">
                        @if($stats['levelProgress'] < 30)
                            Vous débutez votre parcours dans ce niveau, continuez comme ça!
                        @elseif($stats['levelProgress'] < 60)
                            Bon travail! Vous avez accompli la moitié du chemin.
                        @elseif($stats['levelProgress'] < 90)
                            Plus que quelques leçons avant le niveau suivant!
                        @else
                            Vous êtes presque au niveau supérieur, un dernier effort!
                        @endif
                    </p>
                    <a href="#" class="btn btn-lg btn-primary px-4">
                        <i class="fas fa-arrow-right me-2"></i>
                        Continuer mon apprentissage
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-trophy text-warning fs-1 mb-3"></i>
                    <h5 class="fw-bold">Félicitations!</h5>
                    <p class="text-muted">Vous avez atteint le niveau maximum.</p>
                    <p class="text-muted">Continuez à pratiquer pour consolider vos connaissances.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Données passées depuis le contrôleur
        const chartData = @json($chartData);
        
        // Graphique 1: Streak (line)
        const streakCtx = document.getElementById('streakChart');
        if (streakCtx) {
            new Chart(streakCtx, {
                type: 'line',
                data: {
                    labels: chartData.streak.labels,
                    datasets: [{
                        label: 'Série d\'apprentissage',
                        data: chartData.streak.data,
                        borderColor: '#8B5CF6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { drawBorder: false }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
        
        // Graphique 2: Badges (doughnut)
        const badgesCtx = document.getElementById('badgesChart');
        if (badgesCtx) {
            new Chart(badgesCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.badges.labels,
                    datasets: [{
                        data: chartData.badges.data,
                        backgroundColor: ['#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { padding: 20, font: { size: 11 }, usePointStyle: true }
                        }
                    }
                }
            });
        }
        
        // Graphique 3: Leçons par semaine (bar)
        const lessonsCtx = document.getElementById('lessonsChart');
        if (lessonsCtx) {
            new Chart(lessonsCtx, {
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
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { drawBorder: false }, ticks: { stepSize: 1 } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
        
        // Graphique 4: Compétences (radar)
        const skillsCtx = document.getElementById('skillsChart');
        if (skillsCtx) {
            new Chart(skillsCtx, {
                type: 'radar',
                data: {
                    labels: chartData.skills.labels,
                    datasets: [{
                        label: 'Maîtrise',
                        data: chartData.skills.data,
                        fill: true,
                        backgroundColor: 'rgba(139, 92, 246, 0.2)',
                        borderColor: '#8B5CF6',
                        pointBackgroundColor: '#8B5CF6',
                        pointBorderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            min: 0,
                            max: 100,
                            ticks: { stepSize: 20, display: false },
                            pointLabels: { font: { size: 11 } },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' }
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        }
        
        // Graphique 5: Activité hebdomadaire (bar horizontal)
        const weeklyCtx = document.getElementById('weeklyActivityChart');
        if (weeklyCtx) {
            new Chart(weeklyCtx, {
                type: 'bar',
                data: {
                    labels: chartData.weeklyActivity.labels,
                    datasets: [{
                        label: 'Activités',
                        data: chartData.weeklyActivity.data,
                        backgroundColor: '#8B5CF6',
                        borderRadius: 4,
                        borderSkipped: false
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, grid: { drawBorder: false }, ticks: { stepSize: 1 } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }
    });
</script>
@endpush