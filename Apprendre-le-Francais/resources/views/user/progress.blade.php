@extends('layouts.app')

@section('title', 'Ma Progression')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Ma Progression</h1>
        <div>
            <div class="btn-group">
                <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-calendar me-2"></i> {{ now()->format('F Y') }}
                </button>
                <ul class="dropdown-menu">
                    @foreach(range(1, 3) as $month)
                    <li><a class="dropdown-item" href="#">{{ now()->subMonths($month)->format('F Y') }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Niveau actuel</h6>
                            <h2 class="mb-0">{{ $user->level->name }}</h2>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a class="text-white small stretched-link" href="#levels">Voir les détails</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Série actuelle</h6>
                            <h2 class="mb-0">{{ $streak }} jours</h2>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-fire fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a class="text-white small stretched-link" href="#streak">Continuer la série</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Points XP</h6>
                            <h2 class="mb-0">{{ $xpPoints }}</h2>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a class="text-white small stretched-link" href="#xp">Voir l'historique</a>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Objectifs mensuels</h6>
                            <h2 class="mb-0">{{ $monthlyGoals }}%</h2>
                        </div>
                        <div class="icon-circle">
                            <i class="fas fa-bullseye fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0">
                    <a class="text-dark small stretched-link" href="#goals">Définir des objectifs</a>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4">
        <div class="card-header">
            <i class="fas fa-chart-line me-2"></i> Progression hebdomadaire
        </div>
        <div class="card-body">
            <canvas id="progressChart" height="100"></canvas>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="content-card mb-4">
                <div class="card-header">
                    <i class="fas fa-tasks me-2"></i> Objectifs complétés
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($completedGoals as $goal)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1">{{ $goal['name'] }}</h6>
                                    <small class="text-muted">Complété le {{ $goal['completed_at'] }}</small>
                                </div>
                                <span class="badge bg-success p-2">
                                    +{{ $goal['xp'] }} XP
                                </span>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    
                    @if(count($completedGoals) === 0)
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Aucun objectif complété ce mois-ci</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="content-card mb-4">
                <div class="card-header">
                    <i class="fas fa-award me-2"></i> Badges obtenus
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($badges as $badge)
                        <div class="col-4 col-md-3 mb-3">
                            <div class="badge-card text-center">
                                <div class="badge-icon mx-auto mb-2">
                                    <i class="fas fa-{{ $badge['icon'] }} fa-2x"></i>
                                </div>
                                <h6 class="mb-0 small">{{ $badge['name'] }}</h6>
                                <small class="text-muted">{{ $badge['date'] }}</small>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if(count($badges) === 0)
                    <div class="text-center py-4">
                        <i class="fas fa-star fa-3x text-muted mb-3"></i>
                        <p class="mb-0">Aucun badge obtenu pour le moment</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('progressChart').getContext('2d');
        const progressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartData['labels']),
                datasets: [{
                    label: 'Points XP gagnés',
                    data: @json($chartData['data']),
                    backgroundColor: 'rgba(67, 97, 238, 0.1)',
                    borderColor: '#4361ee',
                    borderWidth: 3,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#4361ee',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        padding: 10,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    });
</script>

<style>
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .badge-card {
        padding: 10px;
        border-radius: 10px;
        background-color: rgba(67, 97, 238, 0.05);
        transition: transform 0.3s;
    }
    
    .badge-card:hover {
        transform: translateY(-5px);
    }
    
    .badge-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #4361ee, #3a0ca3);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }
</style>
@endsection