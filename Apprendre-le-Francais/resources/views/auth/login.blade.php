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
                            <i class="fas fa-sign-in-alt fa-3x text-white me-3"></i>
                            <h1 class="h2 mb-0 text-white">{{ __("Connexion") }}</h1>
                        </div>
                        
                        <!-- Effet de fond animé -->
                        <div class="position-absolute top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: 0;">
                            <div class="bubble" style="--size: 3rem; --distance: 6rem; --position: 20%; --time: 14s;"></div>
                            <div class="bubble" style="--size: 2rem; --distance: 8rem; --position: 60%; --time: 16s;"></div>
                            <div class="bubble" style="--size: 4rem; --distance: 5rem; --position: 85%; --time: 12s;"></div>
                        </div>
                    </div>

                    <div class="card-body p-4 p-md-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-primary"></i>
                                    </span>
                                    <div class="form-floating flex-grow-1">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                               name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
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
                            <div class="mb-4">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-lock text-primary"></i>
                                    </span>
                                    <div class="form-floating flex-grow-1 position-relative">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                               name="password" required autocomplete="current-password"
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
                            </div>

                            <!-- Se souvenir de moi -->
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">{{ __('Se souvenir de moi') }}</label>
                            </div>

                            <!-- Bouton de soumission -->
                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill py-3 fw-bold shadow position-relative overflow-hidden">
                                    <span class="position-relative z-index-1">{{ __("Connexion") }}</span>
                                    <span class="btn-overlay position-absolute top-0 start-0 w-100 h-100 bg-white opacity-20"></span>
                                </button>
                            </div>

                            <!-- Liens supplémentaires -->
                            <div class="d-flex justify-content-between mt-3">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link text-decoration-none" href="{{ route('password.request') }}">
                                        <i class="fas fa-key me-1"></i> {{ __('Mot de passe oublié?') }}
                                    </a>
                                @endif
                                <a class="btn btn-link text-decoration-none" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> {{ __("S'inscrire") }}
                                </a>
                            </div>

                            <!-- Séparateur -->
                            <div class="position-relative my-4">
                                <hr>
                                <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted">Ou</span>
                            </div>

                            <!-- Connexion sociale -->
                            <div class="text-center">
                                <p class="mb-3">{{ __("Se connecter avec") }}</p>
                                <div class="d-flex justify-content-center gap-3">
                                    <a href="#" class="btn btn-outline-primary rounded-circle p-3 social-btn">
                                        <i class="fab fa-google"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-primary rounded-circle p-3 social-btn">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-primary rounded-circle p-3 social-btn">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </div>
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
    
    .btn-overlay {
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .btn:hover .btn-overlay {
        opacity: 0.2;
    }
    
    .social-btn {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background: rgba(255, 255, 255, 0.1);
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
    });
</script>
@endpush