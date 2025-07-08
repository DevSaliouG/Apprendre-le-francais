@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, #f0f9ff 0%, #e6f7ff 100%);">
    <div class="container">
        <div class="row justify-content-center align-items-center shadow-lg bg-white rounded-4 overflow-hidden">
            <!-- Colonne gauche (Image ou texte de bienvenue) -->
            <div class="col-md-6 d-none d-md-flex text-white flex-column justify-content-center p-5" style="background: linear-gradient(135deg, #6c63ff 0%, #8a84ff 100%);">
                <div class="text-center">
                    <h1 class="display-5 fw-bold mb-4">FrançaisFacile</h1>
                    <h2 class="h3 mb-4">Maîtrisez le français en vous amusant</h2>
                    
                    <div class="bg-white-20 p-4 rounded-3 mb-4">
                        <h3 class="h4 mb-3">Rejoignez plus de 20 000 apprenants !</h3>
                        <p>Découvrez les témoignages de nos apprenants satisfaits</p>
                    </div>
                    
                    <div class="text-start">
                        <div class="d-flex align-items-start mb-4">
                            <i class="fas fa-book-open fs-3 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 mb-2">Compréhension Écrite</h4>
                                <p class="mb-0">Des exercices interactifs et des textes adaptés à votre niveau</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start mb-4">
                            <i class="fas fa-headphones fs-3 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 mb-2">Compréhension Orale</h4>
                                <p class="mb-0">Écoutez des dialogues authentiques et testez votre compréhension</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-start">
                            <i class="fas fa-chart-line fs-3 me-3 mt-1"></i>
                            <div>
                                <h4 class="h5 mb-2">Suivi Personnalisé</h4>
                                <p class="mb-0">Des recommandations basées sur vos progrès</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-5 pt-3">
                        <p class="fst-italic">"FrançaisFacile a transformé mon apprentissage du français. Les exercices sont ludiques et vraiment efficaces !"</p>
                        <p class="fw-medium">- Serigne Saliou, étudiant</p>
                    </div>
                </div>
            </div>

            <!-- Colonne droite (Formulaire) -->
            <div class="col-md-6 p-5">
                <div class="text-center mb-4">
                    <div class="d-flex justify-content-center mb-3">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                            <i class="fas fa-book fs-2 text-white"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold">{{ __('Connexion à FrancaisFacile') }}</h2>
                    <p class="text-muted mt-2">Améliorez votre compréhension du français dès aujourd'hui</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="form-floating mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                               placeholder="Adresse Email">
                        <label for="email">{{ __('Adresse Email') }}</label>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-floating mb-3 position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password"
                               placeholder="Mot de passe">
                        <label for="password">{{ __('Mot de passe') }}</label>
                        <i class="fas fa-eye position-absolute end-0 top-50 translate-middle-y me-3 toggle-password" data-target="password" style="cursor:pointer;"></i>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">
                                <i class="fas fa-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Se souvenir de moi -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">
                            {{ __('Se souvenir de moi') }}
                        </label>
                    </div>

                    <!-- Bouton -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                            <i class="fas fa-sign-in-alt me-2"></i> {{ __('Commencer à apprendre') }}
                        </button>
                    </div>

                    <!-- Liens supplémentaires -->
                    <div class="d-flex justify-content-between mb-3">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                <i class="fas fa-key me-1"></i> {{ __('Mot de passe oublié?') }}
                            </a>
                        @endif
                        <a class="text-decoration-none" href="{{ route('register') }}">
                            <i class="fas fa-user-plus me-1"></i> {{ __("Créer un compte") }}
                        </a>
                    </div>

                    <!-- Réseaux sociaux -->
                    <div class="text-center mt-4">
                        <div class="position-relative mb-4">
                            <hr>
                            <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">ou continuer avec</span>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="#" class="btn btn-outline-secondary rounded-circle p-2">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="#" class="btn btn-outline-secondary rounded-circle p-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-secondary rounded-circle p-2">
                                <i class="fab fa-apple"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .bg-white-20 {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
    }
    
    .form-control:focus {
        border-color: #8a84ff;
        box-shadow: 0 0 0 0.25rem #8a84ff;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #8a84ff 0%, #8a84ff 100%);
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px #8a84ff;
    }
    
    .toggle-password:hover {
        color: #8a84ff;
    }
</style>
@endpush

@push('scripts')
<script>
    // Toggle password visibility
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function () {
                const target = document.getElementById(this.dataset.target);
                const type = target.type === 'password' ? 'text' : 'password';
                target.type = type;
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
    });
</script>
@endpush