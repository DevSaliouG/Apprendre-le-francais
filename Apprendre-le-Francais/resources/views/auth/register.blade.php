@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center bg-light py-4">
    <div class="row w-100 shadow-lg rounded-4 overflow-hidden bg-white animate__animated animate__fadeIn" style="max-width: 1200px;">
        
        <!-- Colonne Animation -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-center align-items-center p-5 position-relative text-white" style="background: linear-gradient(135deg, #6c63ff, #ff6584);">
            <div class="text-center z-2 animate__animated animate__fadeInUp">
                <div class="avatar-pulse mb-4">
                    <i class="fas fa-user-plus fa-3x"></i>
                </div>
                <h2 class="fw-bold display-5">Créer votre compte</h2>
                <p class="lead mt-3">Rejoignez notre communauté pour profiter de nos services.</p>
            </div>
            
            <!-- Bulles animées améliorées -->
            <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden z-1">
                @for ($i = 1; $i <= 12; $i++)
                    <div class="bubble" style="
                        --size: {{ rand(30, 80) }}px;
                        --distance: {{ rand(50, 120) }}vh;
                        --position: {{ rand(5, 95) }}%;
                        --time: {{ rand(15, 30) }}s;
                        --delay: {{ rand(0, 15) }}s;
                        --opacity: {{ rand(2, 8) / 10 }};
                    "></div>
                @endfor
            </div>
            
            <!-- Ondes animées -->
            <div class="wave wave-1"></div>
            <div class="wave wave-2"></div>
            <div class="wave wave-3"></div>
        </div>

        <!-- Colonne Formulaire -->
        <div class="col-md-6 p-4 p-md-5">
            <div class="animate__animated animate__fadeIn animate__delay-1s">
                <h3 class="mb-4 text-center text-primary position-relative py-3">
                    <i class="fas fa-user-plus me-2"></i>Inscription
                    <div class="title-underline"></div>
                </h3>
                
                <form method="POST" action="{{ route('register') }}" id="registerForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Prénom -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control floating-input @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" value="{{ old('prenom') }}" required 
                                           placeholder="Prénom" autocomplete="given-name">
                                    <label for="prenom">Prénom</label>
                                </div>
                            </div>
                            @error('prenom')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                        </div>

                        <!-- Nom -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-user text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control floating-input @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" value="{{ old('nom') }}" required 
                                           placeholder="Nom" autocomplete="family-name">
                                    <label for="nom">Nom</label>
                                </div>
                            </div>
                            @error('nom')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                        </div>

                        <!-- Adresse -->
                        <div class="col-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-home text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="text" class="form-control floating-input @error('adresse') is-invalid @enderror" 
                                           id="adresse" name="adresse" value="{{ old('adresse') }}" required 
                                           placeholder="Adresse" autocomplete="street-address">
                                    <label for="adresse">Adresse complète</label>
                                </div>
                            </div>
                            @error('adresse')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                        </div>

                        <!-- Date de naissance -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-calendar-day text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="date" class="form-control floating-input @error('dateNaiss') is-invalid @enderror" 
                                           id="dateNaiss" name="dateNaiss" value="{{ old('dateNaiss') }}" required>
                                    <label for="dateNaiss">Date de naissance</label>
                                </div>
                            </div>
                            @error('dateNaiss')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-envelope text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1">
                                    <input type="email" class="form-control floating-input @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required 
                                           placeholder="Email" autocomplete="email">
                                    <label for="email">Adresse Email</label>
                                </div>
                            </div>
                            @error('email')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                        </div>

                        <!-- Mot de passe -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1 position-relative">
                                    <input type="password" class="form-control floating-input @error('password') is-invalid @enderror" 
                                           id="password" name="password" required 
                                           placeholder="Mot de passe" autocomplete="new-password">
                                    <label for="password">Mot de passe</label>
                                    <button type="button" class="btn btn-link text-muted position-absolute top-50 end-0 translate-middle-y me-2 toggle-password" 
                                            data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')<div class="invalid-feedback d-block animate__animated animate__shakeX">{{ $message }}</div>@enderror
                            
                            <div class="password-strength mt-2">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <small class="strength-text text-muted"></small>
                            </div>
                        </div>

                        <!-- Confirmation mot de passe -->
                        <div class="col-md-6">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-transparent border-end-0">
                                    <i class="fas fa-lock text-muted"></i>
                                </span>
                                <div class="form-floating flex-grow-1 position-relative">
                                    <input type="password" class="form-control floating-input" 
                                           id="password-confirm" name="password_confirmation" required 
                                           placeholder="Confirmation" autocomplete="new-password">
                                    <label for="password-confirm">Confirmer le mot de passe</label>
                                    <button type="button" class="btn btn-link text-muted position-absolute top-50 end-0 translate-middle-y me-2 toggle-password" 
                                            data-target="password-confirm">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="col-12 mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label small" for="terms">
                                    J'accepte les <a href="#" class="text-decoration-none">Conditions</a> et la <a href="#" class="text-decoration-none">Politique de confidentialité</a>
                                </label>
                            </div>
                        </div>

                        <!-- Bouton -->
                        <div class="col-12 d-grid mt-3">
                            <button class="btn btn-primary btn-lg btn-hover-glow" type="submit" id="submitBtn">
                                <span class="submit-text">Créer mon compte</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </div>

                        <!-- Lien vers connexion -->
                        <div class="text-center mt-4 animate__animated animate__fadeIn animate__delay-1s">
                            <p class="mb-2">Déjà inscrit ?</p>
                            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-hover-scale">
                                <i class="fas fa-sign-in-alt me-1"></i> Connexion
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    /* Animation de bulles */
    .bubble {
        position: absolute;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        width: var(--size);
        height: var(--size);
        bottom: -10%;
        left: var(--position);
        animation: bubble-float var(--time) linear infinite;
        animation-delay: var(--delay);
        opacity: var(--opacity);
        filter: blur(2px);
    }

    @keyframes bubble-float {
        0% { 
            transform: translateY(0) scale(1); 
            opacity: 0; 
        }
        10% { 
            opacity: var(--opacity); 
        }
        90% { 
            opacity: 0.2; 
        }
        100% { 
            transform: translateY(calc(-1 * var(--distance))) scale(0.2); 
            opacity: 0; 
        }
    }
    
    /* Ondes animées */
    .wave {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100px;
        background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 1200 120" xmlns="http://www.w3.org/2000/svg"><path d="M0 0v46.29c47.79 22.2 103.59 32.17 158 28 70.36-5.37 136.33-33.31 206.8-37.5 73.84-4.36 147.54 16.88 218.2 35.26 69.27 18 138.3 24.88 209.4 13.08 36.15-6 69.85-17.84 104.45-29.34C989.49 25 1113-14.29 1200 52.47V0z" fill="rgba(255,255,255,0.1)"/></svg>');
        background-size: 1200px 100px;
        opacity: 0.5;
        z-index: 0;
    }
    
    .wave-1 {
        animation: wave 25s linear infinite;
        bottom: -20px;
    }
    
    .wave-2 {
        animation: wave 18s linear reverse infinite;
        bottom: -25px;
        opacity: 0.3;
    }
    
    .wave-3 {
        animation: wave 12s linear infinite;
        bottom: -30px;
        opacity: 0.2;
    }
    
    @keyframes wave {
        0% { background-position-x: 0; }
        100% { background-position-x: 1200px; }
    }
    
    /* Avatar pulsant */
    .avatar-pulse {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        position: relative;
        animation: pulse 3s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4); }
        70% { box-shadow: 0 0 0 20px rgba(255, 255, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 255, 255, 0); }
    }
    
    /* Soulignement animé */
    .title-underline {
        position: absolute;
        bottom: 10px;
        left: 25%;
        width: 50%;
        height: 3px;
        background: linear-gradient(90deg, transparent, #6c63ff, transparent);
        transform: scaleX(0);
        transform-origin: center;
        animation: underline 1.5s forwards;
        animation-delay: 0.5s;
    }
    
    @keyframes underline {
        to { transform: scaleX(1); }
    }
    
    /* Boutons animés */
    .btn-hover-glow {
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
    }
    
    .btn-hover-glow:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(108, 99, 255, 0.3);
    }
    
    .btn-hover-scale {
        transition: all 0.3s;
    }
    
    .btn-hover-scale:hover {
        transform: scale(1.05);
    }
    
    /* Input group amélioré */
    .input-group-text {
        transition: all 0.3s;
    }
    
    .floating-input:focus + .input-group-text {
        color: #6c63ff !important;
        transform: translateY(-2px);
    }
    
    /* Barre de progression */
    .password-strength {
        margin-top: 8px;
        opacity: 0;
        height: 0;
        overflow: hidden;
        transition: all 0.3s;
    }
    
    .password-strength.show {
        opacity: 1;
        height: auto;
    }
    
    /* Labels flottants améliorés */
    .form-floating > label {
        left: 50px;
        transition: all 0.3s;
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        transform: scale(0.85) translateY(-0.8rem) translateX(-0.5rem);
        background: white;
        padding: 0 5px;
        color: #6c63ff;
    }
    
    /* Effet de focus sur les inputs */
    .floating-input:focus {
        border-color: #6c63ff;
        box-shadow: 0 0 0 0.25rem rgba(108, 99, 255, 0.25);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/zxcvbn/4.4.2/zxcvbn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const target = this.dataset.target;
                const input = document.getElementById(target);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                }
            });
        });
        
        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthContainer = document.querySelector('.password-strength');
        const strengthBar = document.querySelector('.progress-bar');
        const strengthText = document.querySelector('.strength-text');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                if (password.length > 0) {
                    // Show strength container
                    strengthContainer.classList.add('show');
                    
                    // Analyze password strength
                    const result = zxcvbn(password);
                    const strength = result.score;
                    const width = (strength + 1) * 25;
                    
                    // Update progress bar
                    strengthBar.style.width = width + '%';
                    
                    // Update text and color
                    const strengthLabels = ['Très faible', 'Faible', 'Moyen', 'Fort', 'Très fort'];
                    const strengthColors = ['bg-danger', 'bg-warning', 'bg-info', 'bg-primary', 'bg-success'];
                    
                    strengthText.textContent = 'Sécurité: ' + strengthLabels[strength];
                    
                    // Reset classes and add new color
                    strengthBar.className = 'progress-bar';
                    strengthBar.classList.add(strengthColors[strength]);
                } else {
                    // Hide strength container
                    strengthContainer.classList.remove('show');
                    strengthBar.style.width = '0%';
                }
            });
        }
        
        // Form submission animation
        const form = document.getElementById('registerForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = document.getElementById('submitBtn');
                const submitText = submitBtn.querySelector('.submit-text');
                const spinner = submitBtn.querySelector('.spinner-border');
                
                submitBtn.disabled = true;
                submitText.textContent = 'Création en cours...';
                spinner.classList.remove('d-none');
            });
        }
        
        // Animate inputs on page load
        const inputs = document.querySelectorAll('.floating-input');
        inputs.forEach((input, index) => {
            input.style.opacity = '0';
            input.style.transform = 'translateY(10px)';
            
            setTimeout(() => {
                input.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                input.style.opacity = '1';
                input.style.transform = 'translateY(0)';
            }, 300 + (index * 100));
        });
    });
</script>
@endpush