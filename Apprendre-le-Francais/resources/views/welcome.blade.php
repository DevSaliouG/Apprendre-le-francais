@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<!-- Hero Section -->
<section class="hero-section py-5">
    <div class="container">
        <div class="row align-items-center">
            <!-- Colonne de gauche : Image -->
            <div class="col-md-6 mb-4 mb-md-0">
                <div class="hero-image position-relative">
                    <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?q=80&w=1000" 
                         alt="Apprentissage interactif" 
                         class="img-fluid rounded-4 shadow-lg">
                    <div class="decoration-circle bg-primary position-absolute top-0 start-0 translate-middle"></div>
                    <div class="decoration-circle bg-info position-absolute bottom-0 end-0 translate-middle"></div>
                </div>
            </div>
            
            <!-- Colonne de droite : Texte et bouton -->
            <div class="col-md-6">
                <div class="ps-md-4">
                    <h1 class="display-4 fw-bold mb-3">Maîtrisez le français avec facilité</h1>
                    <p class="lead text-secondary mb-4">
                        Une plateforme d'apprentissage innovante conçue pour vous aider à progresser rapidement 
                        et efficacement en français, quel que soit votre niveau initial.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#" class="btn btn-primary btn-lg px-4 py-3 fw-bold">
                            <i class="fas fa-play-circle me-2"></i>Commencer maintenant
                        </a>
                        <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-3 fw-bold">
                            <i class="fas fa-info-circle me-2"></i>Découvrir
                        </a>
                    </div>
                    
                    <!-- Statistiques -->
                    <div class="mt-5 pt-3">
                        <div class="row">
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="fw-bold text-primary">12K+</h3>
                                    <p class="mb-0 text-muted">Étudiants</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="fw-bold text-primary">98%</h3>
                                    <p class="mb-0 text-muted">Satisfaction</p>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <h3 class="fw-bold text-primary">240+</h3>
                                    <p class="mb-0 text-muted">Leçons</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-5 bg-light mt-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Notre méthode révolutionnaire</h2>
                <p class="lead text-muted">Découvrez pourquoi des milliers d'étudiants nous font confiance</p>
                <div class="divider mx-auto bg-primary"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <!-- Feature 1 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-primary-light text-primary mx-auto mb-4">
                            <i class="fas fa-book fa-2x"></i>
                        </div>
                        <h3 class="h4 fw-bold">Leçons structurées</h3>
                        <p class="text-muted">Des parcours d'apprentissage progressifs adaptés à tous les niveaux, du débutant à l'expert.</p>
                        <a href="#" class="btn btn-link text-primary text-decoration-none">
                            En savoir plus <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Feature 2 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-info-light text-info mx-auto mb-4">
                            <i class="fas fa-headphones fa-2x"></i>
                        </div>
                        <h3 class="h4 fw-bold">Audio natif</h3>
                        <p class="text-muted">Écoutez des locuteurs natifs et améliorez votre compréhension orale et votre prononciation.</p>
                        <a href="#" class="btn btn-link text-primary text-decoration-none">
                            En savoir plus <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Feature 3 -->
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-circle bg-success-light text-success mx-auto mb-4">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h3 class="h4 fw-bold">Suivi intelligent</h3>
                        <p class="text-muted">Visualisez vos progrès en temps réel et recevez des recommandations personnalisées.</p>
                        <a href="#" class="btn btn-link text-primary text-decoration-none">
                            En savoir plus <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Comment ça marche ?</h2>
                <p class="lead text-muted">Apprenez le français en seulement 3 étapes simples</p>
                <div class="divider mx-auto bg-primary"></div>
            </div>
        </div>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-4">
                <div class="step-card p-4 border-start border-3 border-primary">
                    <div class="step-number bg-primary text-white">1</div>
                    <h3 class="h4 fw-bold mt-4">Créez votre compte</h3>
                    <p class="text-muted">Inscrivez-vous gratuitement en moins de 2 minutes et définissez vos objectifs d'apprentissage.</p>
                </div>
                
                <div class="step-card p-4 border-start border-3 border-info mt-4">
                    <div class="step-number bg-info text-white">2</div>
                    <h3 class="h4 fw-bold mt-4">Passez le test de niveau</h3>
                    <p class="text-muted">Notre test adaptatif détermine votre niveau actuel pour un parcours personnalisé.</p>
                </div>
                
                <div class="step-card p-4 border-start border-3 border-success mt-4">
                    <div class="step-number bg-success text-white">3</div>
                    <h3 class="h4 fw-bold mt-4">Commencez à apprendre</h3>
                    <p class="text-muted">Accédez à vos premières leçons et exercices adaptés à votre niveau.</p>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="how-it-works-image position-relative">
                    <img src="https://images.unsplash.com/photo-1584697964358-3e14ca57658b?q=80" 
                         alt="Processus d'apprentissage" 
                         class="img-fluid rounded-4 shadow-lg">
                    <div class="decoration-circle bg-warning position-absolute top-0 start-100 translate-middle"></div>
                    <div class="decoration-circle bg-danger position-absolute bottom-0 start-0 translate-middle"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Ce que disent nos étudiants</h2>
                <p class="lead text-muted">Découvrez les témoignages de ceux qui ont réussi grâce à notre méthode</p>
                <div class="divider mx-auto bg-primary"></div>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/women/44.jpg" 
                             alt="Sophie Martin" 
                             class="rounded-circle me-3" 
                             width="60" 
                             height="60">
                        <div>
                            <h5 class="mb-0 fw-bold">Sophie Martin</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">
                        "J'ai enfin réussi à passer le DELF B2 grâce à cette plateforme. Les exercices d'écoute sont particulièrement efficaces !"
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="testimonial-card p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/men/32.jpg" 
                             alt="Thomas Dubois" 
                             class="rounded-circle me-3" 
                             width="60" 
                             height="60">
                        <div>
                            <h5 class="mb-0 fw-bold">Thomas Dubois</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">
                        "Le suivi de progression m'a permis de voir mes points faibles et de les travailler spécifiquement. Très motivant !"
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="testimonial-card p-4 h-100">
                    <div class="d-flex align-items-center mb-3">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" 
                             alt="Camille Leroy" 
                             class="rounded-circle me-3" 
                             width="60" 
                             height="60">
                        <div>
                            <h5 class="mb-0 fw-bold">Camille Leroy</h5>
                            <div class="text-warning">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <p class="text-muted">
                        "Les leçons sont claires et bien structurées. J'apprécie particulièrement les explications grammaticales simplifiées."
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 bg-primary text-white">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h2 class="display-5 fw-bold mb-4">Prêt à commencer votre voyage linguistique ?</h2>
                <p class="lead mb-5">Rejoignez notre communauté de plus de 12 000 étudiants et maîtrisez le français en quelques mois seulement.</p>
                <a href="#" class="btn btn-light btn-lg px-5 py-3 fw-bold">
                    <i class="fas fa-rocket me-2"></i>Commencer gratuitement
                </a>
                <p class="mt-4 mb-0">7 jours d'essai gratuit - Aucune carte de crédit requise</p>
            </div>
        </div>
    </div>
</section>

<style>
    .hero-section {
        padding: 5rem 0;
        background: linear-gradient(to bottom, #ffffff, #f8f9fa);
    }
    
    .hero-image {
        position: relative;
        z-index: 1;
    }
    
    .decoration-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        opacity: 0.15;
        z-index: 0;
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .divider {
        width: 80px;
        height: 4px;
        border-radius: 2px;
    }
    
    .step-card {
        position: relative;
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
    }
    
    .step-number {
        position: absolute;
        top: -20px;
        left: -20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        font-weight: bold;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
    }
    
    .testimonial-card {
        background: white;
        border-radius: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
        transition: transform 0.3s ease;
    }
    
    .testimonial-card:hover {
        transform: translateY(-5px);
    }
    
    .how-it-works-image {
        position: relative;
        z-index: 1;
    }
    
    @media (max-width: 768px) {
        .hero-section {
            padding: 3rem 0;
        }
        
        .display-4 {
            font-size: 2.5rem;
        }
    }
</style>
@endsection