@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0">Modifier votre profil</h2>
                </div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('profil.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Photo de profil</label>
                                <div class="avatar-upload mb-3">
                                    <div class="avatar-preview rounded-circle bg-light border" 
                                         style="width: 120px; height: 120px;">
                                        @if($user->avatar)
                                            <img src="{{ asset('storage/'.$user->avatar) }}" 
                                                 class="img-fluid rounded-circle">
                                        @else
                                            <i class="fas fa-user fa-3x text-muted position-absolute top-50 start-50 translate-middle"></i>
                                        @endif
                                    </div>
                                    <div class="mt-3">
                                        <input type="file" class="form-control" id="avatar" name="avatar">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6 d-flex align-items-center">
                                <div class="w-100">
                                    <div class="mb-3">
                                        <span class="badge bg-primary">Niveau {{ $user->level->code }}</span>
                                    </div>
                                    <div class="progress" style="height: 10px;">
                                        <div class="progress-bar bg-success" style="width: 65%"></div>
                                    </div>
                                    <small class="text-muted">Progression vers le niveau suivant</small>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <!-- Prénom -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('prenom') is-invalid @enderror" 
                                           id="prenom" name="prenom" 
                                           value="{{ old('prenom', $user->prenom) }}" required>
                                    <label for="prenom">Prénom</label>
                                    @error('prenom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Nom -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" name="nom" 
                                           value="{{ old('nom', $user->nom) }}" required>
                                    <label for="nom">Nom</label>
                                    @error('nom')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" 
                                           value="{{ old('email', $user->email) }}" required>
                                    <label for="email">Adresse Email</label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Date de naissance -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="date" class="form-control @error('dateNaiss') is-invalid @enderror" 
                                           id="dateNaiss" name="dateNaiss" 
                                           value="{{ old('dateNaiss', $user->dateNaiss) }}">
                                    <label for="dateNaiss">Date de naissance</label>
                                    @error('dateNaiss')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Adresse -->
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" class="form-control @error('adresse') is-invalid @enderror" 
                                           id="adresse" name="adresse" 
                                           value="{{ old('adresse', $user->adresse) }}">
                                    <label for="adresse">Adresse</label>
                                    @error('adresse')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Section mot de passe -->
                            <div class="col-12 mt-4">
                                <div class="card border-0 bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title text-muted mb-4">Changer de mot de passe</h5>
                                        
                                        <!-- Mot de passe actuel -->
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Mot de passe actuel</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                                       id="current_password" name="current_password">
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @error('current_password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row g-3">
                                            <!-- Nouveau mot de passe -->
                                            <div class="col-md-6">
                                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" 
                                                           id="new_password" name="new_password">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    @error('new_password')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-text">Minimum 8 caractères</div>
                                            </div>
                                            
                                            <!-- Confirmation -->
                                            <div class="col-md-6">
                                                <label for="new_password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" 
                                                           id="new_password_confirmation" name="new_password_confirmation">
                                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bouton de soumission -->
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                    <i class="fas fa-save me-2"></i> Mettre à jour le profil
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-upload {
        position: relative;
        max-width: 120px;
    }
    
    .avatar-preview {
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px dashed #dee2e6;
    }
    
    .toggle-password {
        cursor: pointer;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const type = input.type === 'password' ? 'text' : 'password';
                input.type = type;
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        });
    });
</script>
@endsection