@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container">
    <div class="hero-section text-center py-5 mb-5">
        <h1 class="display-4 fw-bold mb-3">Apprenez le français efficacement</h1>
        <p class="lead mb-4">Une plateforme interactive pour maîtriser la langue française à votre rythme</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#" class="btn btn-primary btn-lg px-4 py-2">
                <i class="fas fa-play-circle me-2"></i>Commencer
            </a>
            <a href="#features" class="btn btn-outline-primary btn-lg px-4 py-2">
                <i class="fas fa-info-circle me-2"></i>Découvrir
            </a>
        </div>
    </div>

    <div id="features" class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="content-card h-100 text-center p-4">
                <div class="icon-circle bg-primary mx-auto mb-4">
                    <i class="fas fa-book fa-2x text-white"></i>
                </div>
                <h3>Leçons structurées</h3>
                <p>Des cours progressifs du niveau débutant à avancé avec explications claires et exemples concrets.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="content-card h-100 text-center p-4">
                <div class="icon-circle bg-success mx-auto mb-4">
                    <i class="fas fa-pencil-alt fa-2x text-white"></i>
                </div>
                <h3>Exercices interactifs</h3>
                <p>Quiz, textes à trous et exercices d'écoute pour pratiquer et renforcer vos compétences.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="content-card h-100 text-center p-4">
                <div class="icon-circle bg-info mx-auto mb-4">
                    <i class="fas fa-chart-line fa-2x text-white"></i>
                </div>
                <h3>Suivi de progression</h3>
                <p>Visualisez vos améliorations et obtenez des recommandations personnalisées.</p>
            </div>
        </div>
    </div>

    <div class="content-card mb-5">
        <div class="row g-0">
            <div class="col-md-6 d-flex align-items-center">
                <div class="p-4 p-lg-5">
                    <h2 class="mb-3">Apprentissage adaptatif</h2>
                    <p class="lead">Notre système s'adapte à votre niveau et à votre rythme d'apprentissage.</p>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Contenu personnalisé</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Recommandations intelligentes</li>
                        <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Retours immédiats</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-6">
                <div class="learning-img h-100"></div>
            </div>
        </div>
    </div>
</div>

<style>
    .hero-section {
        background: linear-gradient(135deg, rgba(67, 97, 238, 0.1), rgba(63, 55, 201, 0.15));
        border-radius: 20px;
        padding: 3rem 2rem;
        margin-top: 1rem;
    }
    
    .icon-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .learning-img {
        background: url('https://images.unsplash.com/photo-1501504905252-473c47e087f8?q=80') center/cover;
        border-radius: 0 15px 15px 0;
    }
    
    @media (max-width: 768px) {
        .learning-img {
            min-height: 300px;
            border-radius: 0 0 15px 15px;
        }
    }
</style>
@endsection