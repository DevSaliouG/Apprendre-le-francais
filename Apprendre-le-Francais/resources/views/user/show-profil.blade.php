@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <!-- Colonne de gauche - Profil -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
                <!-- En-tête du profil -->
                <div class="profile-header bg-gradient-primary py-5 text-center text-white">
                    <div class="avatar mx-auto mb-3">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" 
                                 class="rounded-circle img-thumbnail" 
                                 width="120" 
                                 alt="Photo de profil">
                        @else
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                 style="width: 120px; height: 120px;">
                                <i class="fas fa-user text-primary fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <h2 class="h4 mb-0">{{ $user->prenom }} {{ $user->nom }}</h2>
                    <p class="mb-1">{{ $user->email }}</p>
                    <div class="badge bg-white text-primary mt-2">
                        <i class="fas fa-trophy me-1"></i> Niveau {{ $user->level->code }}
                    </div>
                </div>
                
                <!-- Détails du profil -->
                <div class="card-body">
                    <div class="d-flex justify-content-center mb-4">
                        <a href="{{ route('profil.edit') }}" class="btn btn-outline-primary rounded-pill px-4">
                            <i class="fas fa-edit me-2"></i> Modifier le profil
                        </a>
                    </div>
                    
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-calendar-alt me-3 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Date de naissance</small>
                                <span>{{ $user->dateNaiss ? $user->dateNaiss->format('d/m/Y') : 'Non renseignée' }}</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-map-marker-alt me-3 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Adresse</small>
                                <span>{{ $user->adresse ?? 'Non renseignée' }}</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-user-clock me-3 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Membre depuis</small>
                                <span>{{ $user->created_at->format('d/m/Y') }}</span>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-sync-alt me-3 text-primary"></i>
                            <div>
                                <small class="text-muted d-block">Dernière mise à jour</small>
                                <span>{{ $user->updated_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
           <!-- Statistiques rapides -->
<div class="card shadow-sm border-0 rounded-3 mt-4">
    <div class="card-header bg-white border-0 py-3">
        <h3 class="h6 mb-0">Votre progression</h3>
    </div>
    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded p-2 me-3">
                <i class="fas fa-check-circle fa-lg"></i>
            </div>
            <div>
                <div class="h5 mb-0">{{ $stats['success_rate'] }}%</div>
                <small class="text-muted">Taux de réussite</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center mb-3">
            <div class="bg-success bg-opacity-10 text-success rounded p-2 me-3">
                <i class="fas fa-fire fa-lg"></i>
            </div>
            <div>
                <div class="h5 mb-0">{{ $stats['streak'] }} jours</div>
                <small class="text-muted">Série actuelle</small>
            </div>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="bg-info bg-opacity-10 text-info rounded p-2 me-3">
                <i class="fas fa-medal fa-lg"></i>
            </div>
            <div>
                <div class="h5 mb-0">{{ count($badges) }} badges</div>
                <small class="text-muted">Récompenses obtenues</small>
            </div>
        </div>
    </div>
</div>
        </div>
        
        <!-- Colonne de droite - Activités et statistiques -->
        <div class="col-lg-8">
            <!-- Progression générale -->
           <div class="card shadow-sm border-0 rounded-3 mb-4">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
        <h3 class="h6 mb-0">Votre parcours d'apprentissage</h3>
        <a href="#" class="btn btn-sm btn-outline-primary">Voir le détail</a>
    </div>
    <div class="card-body">
        <div class="row text-center mb-4">
            <div class="col-md-4 border-end">
                <div class="h2 mb-0">{{ $stats['completed_lessons'] }}</div>
                <small class="text-muted">Leçons complétées</small>
            </div>
            <div class="col-md-4 border-end">
                <div class="h2 mb-0">{{ $stats['success_exercises'] }}</div>
                <small class="text-muted">Exercices réussis</small>
            </div>
            <div class="col-md-4">
                <div class="h2 mb-0">{{ $stats['points'] }}</div>
                <small class="text-muted">Points gagnés</small>
            </div>
        </div>
        
        <div class="mb-4">
            <div class="d-flex justify-content-between mb-2">
                <span class="small">Progression vers le niveau {{ $nextLevel }}</span>
                <span class="small">{{ $stats['success_rate'] }}%</span>
            </div>
            <div class="progress" style="height: 12px;">
                <div class="progress-bar bg-gradient-primary" 
                     role="progressbar" 
                     style="width: {{ $stats['success_rate'] }}%"
                     aria-valuenow="{{ $stats['success_rate'] }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                </div>
            </div>
        </div>
    </div>
</div>
            
            <!-- Badges obtenus -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h3 class="h6 mb-0">Vos récompenses</h3>
                    <a href="#" class="btn btn-sm btn-outline-primary">Voir toutes</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($badges as $badge)
                        <div class="col-4 col-md-3 col-lg-2 text-center">
                            <div class="badge-item position-relative">
                                <div class="bg-{{ $badge['color'] }} bg-opacity-10 rounded-circle p-3 d-inline-block">
                                    <i class="fas fa-{{ $badge['icon'] }} fa-2x text-{{ $badge['color'] }}"></i>
                                </div>
                                <span class="d-block mt-2 small">{{ $badge['name'] }}</span>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
                                    <i class="fas fa-check"></i>
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Activités récentes -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center py-3">
                    <h3 class="h6 mb-0">Activité récente</h3>
                    <a href="#" class="btn btn-sm btn-outline-primary">Voir l'historique</a>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($activities as $activity)
                        <li class="list-group-item">
                            <div class="d-flex">
                                <div class="bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} rounded p-2 me-3">
                                    <i class="fas fa-{{ $activity['icon'] }} fa-lg"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $activity['title'] }}</h6>
                                    <small class="text-muted">{{ $activity['date'] }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ $activity['color'] }} rounded-pill">
                                        +{{ $activity['points'] }} pts
                                    </span>
                                    <div class="small text-muted mt-1">{{ $activity['time'] }}</div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-header {
        position: relative;
        overflow: hidden;
    }
    
    .profile-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        transform: rotate(30deg);
    }
    
    .badge-item {
        transition: transform 0.3s ease;
    }
    
    .badge-item:hover {
        transform: translateY(-5px);
    }
    
    .progress {
        border-radius: 100px;
        overflow: visible;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0d6efd, #0b5ed7);
    }
    
    .list-group-item {
        transition: background-color 0.3s ease;
        border-left: 0;
        border-right: 0;
    }
    
    .list-group-item:first-child {
        border-top: 0;
    }
    
    .list-group-item:last-child {
        border-bottom: 0;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection