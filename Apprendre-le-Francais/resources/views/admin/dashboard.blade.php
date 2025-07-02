@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="container-fluid px-4">
    <!-- Header avec notifications -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Tableau de bord administrateur</h1>
        <div class="d-flex align-items-center">
            <select class="form-select form-select-sm me-3">
                <option>7 derniers jours</option>
                <option selected>30 derniers jours</option>
                <option>90 derniers jours</option>
                <option>Tout</option>
            </select>
            
            <!-- Notifications -->
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle position-relative" type="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell"></i>
                    @if($unreadNotifications->count() > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ $unreadNotifications->count() }}
                    </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown">
                    @forelse($unreadNotifications as $notification)
                    <li>
                        <a class="dropdown-item" href="{{ $notification->data['url'] }}">
                            <div class="d-flex">
                                <div class="me-2">
                                    <i class="{{ $notification->data['icon'] }} text-primary"></i>
                                </div>
                                <div>
                                    {{ $notification->data['message'] }}
                                    <div class="text-muted small">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li><a class="dropdown-item" href="#">Aucune notification</a></li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Cartes statistiques -->
   <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-5 border-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-primary text-uppercase mb-1">Utilisateurs</div>
                            <div class="h5 mb-0 fw-bold text-gray-800">{{ $userlist }}</div>
                            <div class="mt-2">
                                <span class="text-success fw-bold"><i class="fas fa-arrow-up me-1"></i>12.5%</span>
                                <span class="text-muted small">depuis le mois dernier</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-5 border-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-success text-uppercase mb-1">Niveaux</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"> {{ $levels }}</div>
                            <div class="mt-2">
                                <span class="text-muted small">3 niveaux ajoutés cette année</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-5 border-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-info text-uppercase mb-1">Leçons</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"> {{ $lessons }}</div>
                            <div class="mt-2">
                                <span class="text-success fw-bold"><i class="fas fa-arrow-up me-1"></i>8.2%</span>
                                <span class="text-muted small">depuis le mois dernier</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-start border-5 border-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs fw-bold text-warning text-uppercase mb-1">Exercices</div>
                            <div class="h5 mb-0 fw-bold text-gray-800"> {{ $exercises }}</div>
                            <div class="mt-2">
                                <span class="text-success fw-bold"><i class="fas fa-arrow-up me-1"></i>15.3%</span>
                                <span class="text-muted small">depuis le mois dernier</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section graphiques -->
    <div class="row">
        <!-- Nouveaux utilisateurs par semaine -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Nouveaux utilisateurs (8 semaines)</h6>
                </div>
                <div class="card-body">
                    <canvas id="newUsersChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Répartition des badges -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Répartition des badges</h6>
                </div>
                <div class="card-body">
                    <canvas id="badgeDistributionChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Activité par niveau -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Activité par niveau</h6>
                </div>
                <div class="card-body">
                    <canvas id="activityByLevelChart" height="250"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Exercices réussis -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 fw-bold text-primary">Exercices réussis (30 jours)</h6>
                </div>
                <div class="card-body">
                    <canvas id="highScoreChart" height="250"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Tableau des utilisateurs -->
   {{--  <div class="card shadow mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Utilisateurs</h6>
            <div>
                <form class="d-flex">
                    <select class="form-select form-select-sm me-2">
                        <option>Tous les niveaux</option>
                        @foreach($levelsList as $level)
                        <option value="{{ $level->id }}">{{ $level->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control form-control-sm me-2" placeholder="Rechercher...">
                    <button class="btn btn-sm btn-primary">Filtrer</button>
                </form>
            </div>
        </div>
       {{--  <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Niveau</th>
                            <th>Inscrit le</th>
                            <th>Badges</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->prenom }} {{ $user->nom }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->level->name ?? '-' }}</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td>{{ $user->badges->count() }}</td>
                            <td>
                                <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Voir
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div> --}}
    </div> --}}
</div>

<!-- Chart.js Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Graphique nouveaux utilisateurs
        new Chart(document.getElementById('newUsersChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($newUsersPerWeek->keys()) !!},
                datasets: [{
                    label: 'Nouveaux utilisateurs',
                    data: {!! json_encode($newUsersPerWeek->values()) !!},
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Graphique répartition badges
        new Chart(document.getElementById('badgeDistributionChart'), {
            type: 'pie',
            data: {
                labels: {!! json_encode($badgeDistribution->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($badgeDistribution->pluck('users_count')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)',
                        'rgba(255, 159, 64, 0.5)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Graphique activité par niveau
        new Chart(document.getElementById('activityByLevelChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($activityByLevel->pluck('name')) !!},
                datasets: [{
                    label: 'Utilisateurs actifs',
                    data: {!! json_encode($activityByLevel->pluck('users_count')) !!},
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true
            }
        });

        // Graphique exercices réussis
        new Chart(document.getElementById('highScoreChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($highScoreExercises->pluck('date')) !!},
                datasets: [{
                    label: 'Exercices réussis ≥ 85%',
                    data: {!! json_encode($highScoreExercises->pluck('count')) !!},
                    fill: false,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
@endsection