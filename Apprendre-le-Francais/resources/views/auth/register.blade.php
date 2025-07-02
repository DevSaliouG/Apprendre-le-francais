@extends('layouts.app')

@section('content')
<div class="min-vh-100 d-flex align-items-center" style="background: linear-gradient(135deg, rgba(108, 99, 255, 0.05), rgba(255, 101, 132, 0.05));">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 rounded-3 overflow-hidden">
                    <!-- Section d'en-tête avec animation -->
                    <div class="card-header py-4 position-relative" style="background: linear-gradient(135deg, var(--primary), var(--primary-light));">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-user-plus fa-3x text-white me-3"></i>
                            <h1 class="h2 mb-0 text-white">{{ __("Créer votre compte") }}</h1>
                        </div>
                        
                        <!-- Animation d'indicateur de progression -->
                        <div class="progress-indicator mt-3">
                            <div class="step active"></div>
                            <div class="step"></div>
                            <div class="step"></div>
                        </div>
                        
                        <!-- Effet de fond animé -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
                            <div class="bubble" style="--size: 3rem; --distance: 6rem; --position: 10%; --time: 15s;"></div>
                            <div class="bubble" style="--size: 2rem; --distance: 8rem; --position: 30%; --time: 12s;"></div>
                            <div class="bubble" style="--size: 4rem; --distance: 5rem; --position: 70%; --time: 18s;"></div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('register') }}" id="registrationForm">
                            @csrf
                            
                            <div class="row g-4">
                                <!-- Prénom -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input id="prenom" type="text" class="form-control @error('prenom') is-invalid @enderror"
                                                   name="prenom" value="{{ old('prenom') }}" required autocomplete="given-name" autofocus
                                                   placeholder="{{ __('Prénom') }}">
                                            <label for="prenom" class="form-label">
                                                {{ __('Prénom') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('prenom')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Nom -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user-tag text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input id="nom" type="text" class="form-control @error('nom') is-invalid @enderror"
                                                   name="nom" value="{{ old('nom') }}" required autocomplete="family-name"
                                                   placeholder="{{ __('Nom') }}">
                                            <label for="nom" class="form-label">
                                                {{ __('Nom') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('nom')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Adresse -->
                                <div class="col-12">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-map-marker-alt text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input id="adresse" type="text" class="form-control @error('adresse') is-invalid @enderror"
                                                   name="adresse" value="{{ old('adresse') }}" required autocomplete="street-address"
                                                   placeholder="{{ __('Adresse complète') }}">
                                            <label for="adresse" class="form-label">
                                                {{ __('Adresse complète') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('adresse')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="form-text ms-5 mt-1">Ex: 123 Rue de l'Université, Bambey</div>
                                </div>

                                <!-- Date de naissance -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-birthday-cake text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input id="dateNaiss" type="date" class="form-control @error('dateNaiss') is-invalid @enderror"
                                                   name="dateNaiss" value="{{ old('dateNaiss') }}" required
                                                   placeholder="{{ __('Date de naissance') }}">
                                            <label for="dateNaiss" class="form-label">
                                                {{ __('Date de naissance') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('dateNaiss')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                                   name="email" value="{{ old('email') }}" required autocomplete="email"
                                                   placeholder="{{ __('Adresse Email') }}">
                                            <label for="email" class="form-label">
                                                {{ __('Adresse Email') }}
                                            </label>
                                        </div>
                                    </div>
                                    @error('email')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <!-- Mot de passe -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1 position-relative">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                                   name="password" required autocomplete="new-password"
                                                   placeholder="{{ __('Mot de passe') }}">
                                            <label for="password" class="form-label">
                                                {{ __('Mot de passe') }}
                                            </label>
                                            <span class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3">
                                                <i class="fas fa-eye toggle-password" data-target="password"></i>
                                            </span>
                                        </div>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block mt-2">
                                            <i class="fas fa-exclamation-circle me-2"></i> {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="password-strength mt-2 ms-5">
                                        <div class="progress" style="height: 5px;">
                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                        </div>
                                        <small class="strength-text text-muted">Faible</small>
                                    </div>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <div class="form-floating flex-grow-1 position-relative">
                                            <input id="password-confirm" type="password" class="form-control"
                                                   name="password_confirmation" required autocomplete="new-password"
                                                   placeholder="{{ __('Confirmer le mot de passe') }}">
                                            <label for="password-confirm" class="form-label">
                                                {{ __('Confirmer le mot de passe') }}
                                            </label>
                                            <span class="password-toggle position-absolute end-0 top-50 translate-middle-y me-3">
                                                <i class="fas fa-eye toggle-password" data-target="password-confirm"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="password-match mt-2 ms-5">
                                        <small class="match-text text-muted">
                                            <i class="fas fa-check-circle text-success d-none"></i>
                                            <i class="fas fa-times-circle text-danger d-none"></i>
                                            <span class="message">Les mots de passe doivent correspondre</span>
                                        </small>
                                    </div>
                                </div>

                                <!-- Conditions d'utilisation -->
                                <div class="col-12">
                                    <div class="form-check ms-4">
                                        <input class="form-check-input" type="checkbox" id="terms" required>
                                        <label class="form-check-label small" for="terms">
                                            J'accepte les <a href="#" class="text-primary">conditions d'utilisation</a> et la 
                                            <a href="#" class="text-primary">politique de confidentialité</a>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Bouton de soumission -->
                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow position-relative overflow-hidden">
                                    <span class="position-relative z-index-1 submit-text">{{ __("Créer mon compte") }}</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                    <span class="btn-overlay position-absolute top-0 start-0 w-100 h-100 bg-white opacity-20"></span>
                                </button>
                            </div>

                            <!-- Alternative login -->
                            <div class="text-center mt-4 pt-3 border-top">
                                <p class="mb-2">{{ __('Déjà inscrit?') }}</p>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-sign-in-alt me-2"></i> {{ __('Connectez-vous') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card-header {
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .bubble {
        position: absolute;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 50%;
        width: var(--size);
        height: var(--size);
        bottom: 0;
        left: var(--position);
        animation: bubble var(--time) linear infinite;
    }
    
    @keyframes bubble {
        0% {
            transform: translateY(0);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(calc(-1 * var(--distance)));
            opacity: 0;
        }
    }
    
    .progress-indicator {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 10px;
        z-index: 2;
        position: relative;
    }
    
    .progress-indicator .step {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: rgba(255,255,255,0.3);
        transition: all 0.3s ease;
    }
    
    .progress-indicator .step.active {
        background-color: white;
        transform: scale(1.4);
    }
    
    .input-group {
        margin-bottom: 1.5rem;
    }
    
    .input-group-text {
        transition: all 0.3s ease;
    }
    
    .form-control:focus + .input-group-text {
        background-color: #e0e7ff;
    }
    
    .password-toggle {
        cursor: pointer;
        z-index: 5;
        color: #6c757d;
        transition: color 0.3s ease;
    }
    
    .password-toggle:hover {
        color: var(--primary);
    }
    
    .password-strength .progress-bar {
        transition: width 0.5s ease, background-color 0.5s ease;
    }
    
    .password-match .fa-check-circle,
    .password-match .fa-times-circle {
        transition: opacity 0.3s ease;
    }
    
    .btn-overlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .btn:hover .btn-overlay {
        opacity: 0.2;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const target = this.getAttribute('data-target');
                const input = document.getElementById(target);
                const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                input.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });

        // Password strength indicator
        const passwordInput = document.getElementById('password');
        const strengthBar = document.querySelector('.password-strength .progress-bar');
        const strengthText = document.querySelector('.strength-text');
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            
            // Longueur minimale
            if (password.length >= 8) strength += 25;
            
            // Contient des lettres minuscules et majuscules
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength += 25;
            
            // Contient des chiffres
            if (/\d/.test(password)) strength += 25;
            
            // Contient des caractères spéciaux
            if (/[^A-Za-z0-9]/.test(password)) strength += 25;
            
            // Mise à jour de l'UI
            strengthBar.style.width = strength + '%';
            
            // Couleur et texte en fonction de la force
            if (strength < 50) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Faible';
                strengthText.className = 'strength-text text-danger';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Moyen';
                strengthText.className = 'strength-text text-warning';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Fort';
                strengthText.className = 'strength-text text-success';
            }
        });

        // Password match indicator
        const confirmPassword = document.getElementById('password-confirm');
        const passwordMatch = document.querySelector('.password-match');
        
        function checkPasswordMatch() {
            const match = passwordInput.value === confirmPassword.value;
            const checkIcon = passwordMatch.querySelector('.fa-check-circle');
            const timesIcon = passwordMatch.querySelector('.fa-times-circle');
            const message = passwordMatch.querySelector('.message');
            
            if (passwordInput.value && confirmPassword.value) {
                if (match) {
                    checkIcon.classList.remove('d-none');
                    timesIcon.classList.add('d-none');
                    message.textContent = 'Les mots de passe correspondent';
                    message.className = 'message text-success';
                } else {
                    checkIcon.classList.add('d-none');
                    timesIcon.classList.remove('d-none');
                    message.textContent = 'Les mots de passe ne correspondent pas';
                    message.className = 'message text-danger';
                }
            } else {
                checkIcon.classList.add('d-none');
                timesIcon.classList.add('d-none');
                message.textContent = 'Les mots de passe doivent correspondre';
                message.className = 'message text-muted';
            }
        }
        
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPassword.addEventListener('input', checkPasswordMatch);

        // Form submission animation
        const form = document.getElementById('registrationForm');
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            const submitText = submitBtn.querySelector('.submit-text');
            const spinner = submitBtn.querySelector('.spinner-border');
            
            submitText.classList.add('d-none');
            spinner.classList.remove('d-none');
            submitBtn.disabled = true;
            
            // Animation d'indicateur de progression
            const steps = document.querySelectorAll('.progress-indicator .step');
            let i = 0;
            const interval = setInterval(() => {
                if (i < steps.length) {
                    steps[i].classList.add('active');
                    i++;
                } else {
                    clearInterval(interval);
                }
            }, 300);
        });
    });
</script>
@endpush