<aside class="sidebar">
  <div class="sidebar-header">
    <h2 class="sidebar-title">
      <i class="fas fa-compass"></i>
      <span>Navigation</span>
    </h2>
  </div>
  
  <div class="nav flex-column nav-pills">
    @if(auth()->user()->is_admin)
      <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}">
        <i class="fas fa-tachometer-alt"></i>
        <span>Dashboard Admin</span>
      </a>
      <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Utilisateurs</span>
      </a>
      <a href="{{ route('admin.lessons.index') }}" class="nav-link {{ request()->is('admin/lessons*') ? 'active' : '' }}">
        <i class="fas fa-book"></i>
        <span>Leçons</span>
      </a>
      <a href="{{ route('admin.exercises.index') }}" class="nav-link {{ request()->is('admin/exercises*') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i>
        <span>Exercices</span>
      </a>
      <a href="{{-- {{ route('admin.badges.index') }} --}}" class="nav-link {{ request()->is('admin/badges*') ? 'active' : '' }}">
        <i class="fas fa-trophy"></i>
        <span>Badges</span>
      </a>
      <a href="{{ route('admin.questions.index') }}" class="nav-link {{ request()->is('admin/questions*') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i>
        <span>Questions</span>
      </a>
      
    @else
      <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Accueil</span>
      </a>
      <a href="{{ route('lessons.index') }}" class="nav-link {{ request()->is('lessons*') ? 'active' : '' }}">
        <i class="fas fa-graduation-cap"></i>
        <span>Mes Leçons</span>
        <span class="badge bg-primary rounded-pill ms-auto">{{ auth()->user()->unlocked_lessons ?? '0' }}/{{ auth()->user()->total_lessons ?? '0' }}</span>
      </a>
      <a href="{{ route('exercises.index') }}" class="nav-link {{ request()->is('exercises*') ? 'active' : '' }}">
        <i class="fas fa-pen"></i>
        <span>Exercices</span>
        <span class="badge bg-success rounded-pill ms-auto">{{ auth()->user()->completed_exercises ?? '0' }}/{{ auth()->user()->total_exercises ?? '0' }}</span>
      </a>
      <a href="{{ route('badges.index') }}" class="nav-link {{ request()->is('badges*') ? 'active' : '' }}">
        <i class="fas fa-trophy"></i>
        <span>Mes Badges</span>
        <span class="badge bg-warning rounded-pill ms-auto">{{ auth()->user()->badge_count ?? '0' }}</span>
      </a>
      <a href="{{ route('streak.index') }}" class="nav-link {{ request()->is('streak*') ? 'active' : '' }} position-relative">
    <i class="fas fa-fire"></i>
    <span>Ma Série</span>
    <span class="badge bg-danger rounded-pill ms-auto">
        {{ auth()->user()->learning_streak->current_streak ?? '0' }} jours
    </span>
    @if(auth()->user()->learning_streak->current_streak >= 3)
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-warning pulse-animation">
            <i class="fas fa-bolt"></i>
        </span>
    @endif
</a>
      <a href="{{-- {{ route('progress.index') }} --}}" class="nav-link {{ request()->is('progress*') ? 'active' : '' }}">
        <i class="fas fa-chart-line"></i>
        <span>Ma Progression</span>
      </a>
    @endif
  </div>
  
  @unless(auth()->user()->is_admin)
    <div class="mt-5">
      <div class="d-flex align-items-center justify-content-between mb-3">
        <h6 class="fw-medium mb-0">Votre niveau actuel</h6>
        <span class="badge bg-primary">{{ auth()->user()->level->name ?? 'A1' }}</span>
      </div>
      
      <div class="progress mb-4" style="height: 8px; background: rgba(255, 255, 255, 0.1);">
        <div class="progress-bar" role="progressbar" style="width: {{ auth()->user()->progress ?? '35' }}%; background: linear-gradient(90deg, var(--tertiary), var(--success));" 
             aria-valuenow="{{ auth()->user()->progress ?? '35' }}" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
      
      <div class="card" style="background: rgba(38, 38, 58, 0.6); border: 1px solid rgba(255, 255, 255, 0.1);">
        <div class="card-body p-3">
          <div class="d-flex align-items-center">
            <div class="bg-light p-3 rounded-circle me-3" style="background: rgba(78, 205, 196, 0.1) !important;">
              <i class="fas fa-gift text-primary fs-4" style="color: var(--tertiary) !important;"></i>
            </div>
            <div>
              <p class="mb-0 fw-medium">Prochain badge</p>
              <h6 class="mb-0">{{ auth()->user()->next_badge ?? 'Étudiant assidu' }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endunless
</aside>

@section('styles')
<style>
    .pulse-animation {
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
        50% { transform: translate(-50%, -50%) scale(1.2); opacity: 0.7; }
        100% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
    }
    .nav-link.active .badge {
        background-color: white !important;
        color: var(--primary);
    }
</style>
@endsection