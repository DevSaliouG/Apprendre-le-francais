@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Votre progression quotidienne</h4>
                        @if($streak->current_streak > 0)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-fire"></i> {{ $streak->current_streak }} jours consécutifs
                            </span>
                        @endif
                    </div>
                </div>
                
                <div class="card-body">
                    <!-- Messages de notification -->
                    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if (session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

                    <!-- Statistiques du streak -->
                    <div class="row mb-4 text-center">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded h-100">
                                <h5 class="text-muted">Série actuel</h5>
                                <h3 class="text-primary">{{ $streak->current_streak }} jours</h3>
                                <small class="text-muted">Jour(s) consécutif(s)</small>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded h-100">
                                <h5 class="text-muted">Votre record</h5>
                                <h3 class="text-success">{{ $streak->longest_streak }} jours</h3>
                                <small class="text-muted">Meilleure série</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 bg-light rounded h-100">
                                <h5 class="text-muted">Dernière activité</h5>
                                <h3>{{ $streak->last_activity_date ? \Carbon\Carbon::parse($streak->last_activity_date)->format('d/m/Y') : 'Aucune' }}</h3>
                                <small class="text-muted">Date de la dernière connexion</small>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de récompense -->
                       <div class="text-center mb-4">
        <form action="{{ route('streak.claim') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-warning btn-lg px-4 py-2"
                @if($streak->last_activity_date && \Carbon\Carbon::parse($streak->last_activity_date)->isToday())
                    disabled
                @endif>
                <i class="fas fa-gift me-2"></i> 
                Réclamer la récompense du jour
            </button>
            
            @if($streak->last_activity_date && \Carbon\Carbon::parse($streak->last_activity_date)->isToday())
                <p class="text-danger mt-2">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Vous avez déjà réclamé votre récompense aujourd'hui
                </p>
            @else
                <p class="text-muted mt-2">
                    Revenez chaque jour pour augmenter votre streak et gagner plus de points !
                </p>
            @endif
        </form>
    </div>


                    <!-- Calendrier des activités -->
                   <!-- Calendrier des activités -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5>Vos 6 dernières semaines</h5>
                        <div class="calendar-legend">
                            <span class="me-3"><span class="legend-dot bg-success"></span> Actif</span>
                            <span class="me-3"><span class="legend-dot bg-secondary"></span> Inactif</span>
                            <span><span class="legend-dot border-warning"></span> Aujourd'hui</span>
                        </div>
                    </div>
                    
                    <!-- En-tête des jours -->
                    <div class="calendar-header d-flex mb-2">
                        @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $day)
                            <div class="flex-fill text-center small fw-bold">{{ $day }}</div>
                        @endforeach
                    </div>
                    
                    <!-- Grille de calendrier -->
                    <div class="calendar-grid">
                        @foreach(array_chunk($calendar, 7) as $week)
                            <div class="calendar-row d-flex">
                                @foreach($week as $day)
                                    <div class="calendar-day flex-fill text-center p-2 
                                        {{ !$day['in_range'] ? 'text-muted' : '' }}
                                        {{ $day['active'] ? 'bg-success text-white' : 'bg-light' }}
                                        {{ $day['today'] ? 'border-warning border-2' : '' }}"
                                        data-bs-toggle="tooltip" 
                                        title="{{ $day['date'] }} - {{ $day['active'] ? 'Activité enregistrée' : 'Aucune activité' }}">
                                        <div class="day-number">{{ $day['day'] }}</div>
                                        @if($day['active'])
                                            <i class="fas fa-check check-icon small"></i>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Paliers de récompenses -->
                    <div class="mt-5">
                        <h5 class="mb-4">Vos prochains paliers</h5>
                        <div class="row">
                            @foreach($milestones as $milestone)
                                <div class="col-md-4 mb-3">
                                    <div class="card border-0 shadow-sm h-100 
                                        {{ $streak->current_streak >= $milestone->days_required ? 'border-success' : '' }}">
                                        <div class="card-body text-center">
                                            <div class="mb-3">
                                                <i class="{{ $milestone->badge_icon }} fa-2x 
                                                    {{ $streak->current_streak >= $milestone->days_required 
                                                        ? 'text-success' : 'text-secondary' }}"></i>
                                            </div>
                                            <h5 class="card-title">{{ $milestone->badge_name }}</h5>
                                            <p class="card-text small">
                                                {{ $milestone->days_required }} jours consécutifs
                                            </p>
                                            <div class="progress mt-2" style="height: 8px;">
                                                @php
                                                    $progress = min(100, ($streak->current_streak / $milestone->days_required) * 100);
                                                @endphp
                                                <div class="progress-bar bg-success" 
                                                    role="progressbar" 
                                                    style="width: {{ $progress }}%"
                                                    aria-valuenow="{{ $progress }}" 
                                                    aria-valuemin="0" 
                                                    aria-valuemax="100"></div>
                                            </div>
                                            @if($streak->current_streak >= $milestone->days_required)
                                                <span class="badge bg-success mt-2">
                                                    <i class="fas fa-check"></i> Obtenu
                                                </span>
                                            @else
                                                <span class="text-muted small mt-2">
                                                    {{ $milestone->days_required - $streak->current_streak }} jours restants
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .calendar-grid {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .calendar-row {
        display: flex;
        gap: 4px;
    }
    
    .calendar-day {
        aspect-ratio: 1;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        position: relative;
        transition: all 0.2s;
    }
    
    .calendar-day:hover {
        transform: scale(1.05);
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    
    .legend-dot {
        display: inline-block;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 5px;
    }
    
    .check-icon {
        position: absolute;
        bottom: 5px;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Active les tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        
        // Popup de relance
        @if(session('streak_reminder'))
            Swal.fire({
                title: 'Votre streak est en danger !',
                text: '{{ session("streak_reminder") }}',
                icon: 'warning',
                confirmButtonText: 'Reprendre maintenant',
                backdrop: true
            });
        @endif
    });
</script>
@endsection