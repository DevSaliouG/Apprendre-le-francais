<header class="main-header">
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      @auth
        <button class="sidebar-toggle d-lg-none me-2">
          <i class="fas fa-bars"></i>
        </button>
      @endauth
      <a class="navbar-brand" href="{{ route('home') }}">
        <i class="fas fa-book-open"></i>
        <span>FrançaisFacile</span>
      </a>
      
      <div class="d-flex align-items-center">
        @auth
          <!-- Streak Indicator -->
          <div class="d-none d-md-flex align-items-center me-4 bg-white bg-opacity-10 px-3 py-1 rounded-pill position-relative streak-indicator">
            <i class="fas fa-fire text-warning me-2"></i>
            <span class="fw-medium">{{ auth()->user()->learning_streak->current_streak ?? '0' }} jours</span>
            @if(auth()->user()->learning_streak->current_streak > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success streak-tooltip" 
                      data-bs-toggle="tooltip" 
                      title="Votre série actuelle - Record: {{ auth()->user()->learning_streak->longest_streak ?? '0' }} jours">
                    +{{ auth()->user()->learning_streak->current_streak }}
                </span>
            @endif
          </div>
          
          <!-- Notification Dropdown - Remplacé par le nouveau composant -->
          <div class="me-3 relative" style="z-index: 1050;">
              <x-notification-bell />
          </div>
        @endif
        
        <ul class="navbar-nav ms-auto">
          @auth
            <li class="nav-item dropdown d-none d-md-block">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->prenom }}+{{ auth()->user()->nom }}&background=random&color=fff" class="user-avatar me-2">
                <span>{{ auth()->user()->prenom }}</span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="fas fa-bell me-2"></i>Notifications</a></li>
                {{-- <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Paramètres</a></li> --}}
                @if(auth()->user()->is_admin)
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-lock me-2"></i>Administration</a></li>
                @endif
                <li><hr class="dropdown-divider"></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                  </form>
                </li>
              </ul>
            </li>
            
            <!-- Mobile menu for authenticated users -->
            <li class="nav-item dropdown d-md-none">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->prenom }}+{{ auth()->user()->nom }}&background=random&color=fff" class="user-avatar">
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="fas fa-home me-2"></i>Accueil</a></li>
                @if(auth()->user()->is_admin)
                  <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i>Dashboard Admin</a></li>
                @else
                  <li><a class="dropdown-item" href="{{ route('lessons.index') }}"><i class="fas fa-graduation-cap me-2"></i>Mes Leçons</a></li>
                  <li><a class="dropdown-item" href="{{ route('exercises.index') }}"><i class="fas fa-pen me-2"></i>Exercices</a></li>
                  <li><a class="dropdown-item" href="{{ route('badges.index') }}"><i class="fas fa-trophy me-2"></i>Mes Badges</a></li>
                @endif
                <li><a class="dropdown-item" href="{{ route('notifications.index') }}"><i class="fas fa-bell me-2"></i>Notifications</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i>Profil</a></li>
                <li>
                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt me-2"></i>Déconnexion</button>
                  </form>
                </li>
              </ul>
            </li>
          @else
            <li class="nav-item d-none d-md-block">
              <a class="btn btn-outline-light me-2" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i>Connexion</a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a class="btn btn-light text-primary fw-medium" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i>Inscription</a>
            </li>
            
            <!-- Mobile menu for guests -->
            <li class="nav-item dropdown d-md-none">
              <a class="nav-link" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle fs-4"></i>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow">
                <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-2"></i>Connexion</a></li>
                <li><a class="dropdown-item" href="{{ route('register') }}"><i class="fas fa-user-plus me-2"></i>Inscription</a></li>
              </ul>
            </li>
          @endauth
        </ul>
      </div>
    </div>
  </nav>
</header>