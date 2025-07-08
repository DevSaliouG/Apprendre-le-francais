<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #8a84ff;
            --primary-light: #a29dff;
            --primary-dark: #6c63ff;
            --accent: #ff6b6b;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #94a3b8;
            --light-gray: #e2e8f0;
        }

        .footer-link {
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            padding-bottom: 2px;
        }

        .footer-link:hover {
            color: var(--primary) !important;
            transform: translateX(5px);
        }

        .footer-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }

        .footer-link:hover::after {
            width: 100%;
        }

        .social-icon {
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: rgba(138, 132, 255, 0.1);
            color: var(--primary) !important;
        }

        .social-icon:hover {
            transform: translateY(-5px) scale(1.1);
            background-color: var(--primary);
            color: white !important;
        }

        .bg-dark-footer {
            background-color: var(--dark);
            background-image: linear-gradient(to bottom, #1e293b, #1a2436);
        }

        .text-gray {
            color: var(--gray);
        }
        
        .newsletter-container {
            background: rgba(138, 132, 255, 0.1);
            border: 1px solid rgba(138, 132, 255, 0.2);
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }
        
        .newsletter-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border: none;
            transition: all 0.3s ease;
        }
        
        .newsletter-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(138, 132, 255, 0.4);
        }
        
        .feature-icon {
            background: rgba(138, 132, 255, 0.1);
            color: var(--primary);
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }
        
        .feature:hover .feature-icon {
            background: var(--primary);
            color: white;
            transform: rotate(5deg) scale(1.1);
        }
        
        .download-badge {
            display: inline-block;
            background: rgba(138, 132, 255, 0.1);
            border-radius: 12px;
            padding: 12px 20px;
            transition: all 0.3s ease;
        }
        
        .download-badge:hover {
            background: rgba(138, 132, 255, 0.2);
            transform: translateY(-3px);
        }
        
        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            opacity: 0.2;
        }
        
        .feature-title {
            position: relative;
            display: inline-block;
            padding-bottom: 5px;
        }
        
        .feature-title::after {
            content: '';
            position: absolute;
            width: 40px;
            height: 2px;
            background: var(--primary);
            bottom: 0;
            left: 0;
        }
    </style>
</head>
    <!-- Footer -->
    <footer class="bg-dark-footer text-light pt-5 pb-4 px-4">
        <div class="container">
            <!-- Features Section -->
            <div class="row g-5 mb-5">
                <div class="col-md-4 feature">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <h4 class="feature-title fw-semibold">Compréhension Écrite</h4>
                        <p class="mt-3 text-gray">Améliorez votre lecture avec des textes adaptés et des exercices interactifs.</p>
                    </div>
                </div>
                
                <div class="col-md-4 feature">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-headphones"></i>
                        </div>
                        <h4 class="feature-title fw-semibold">Compréhension Orale</h4>
                        <p class="mt-3 text-gray">Écoutez des dialogues authentiques et testez votre compréhension audio.</p>
                    </div>
                </div>
                
                <div class="col-md-4 feature">
                    <div class="text-center">
                        <div class="feature-icon mx-auto">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="feature-title fw-semibold">Suivi des Progrès</h4>
                        <p class="mt-3 text-gray">Visualisez vos améliorations avec notre système de suivi intelligent.</p>
                    </div>
                </div>
            </div>
            
            <div class="footer-divider my-5"></div>
            
            <div class="row gy-5">
                <!-- Brand Column -->
                <div class="col-md-6 col-lg-3">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-language fa-2x me-2" style="color: #8a84ff;"></i>
                        <h5 class="fw-bold" style="color: #8a84ff;">FrançaisFacile</h5>
                    </div>
                    <p class="text-gray">La plateforme la plus efficace pour apprendre le français à votre rythme.</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                    </div>
                    
                    <div class="mt-4">
                        <h6 class="fw-semibold mb-3">Téléchargez notre app</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="#" class="download-badge">
                                <i class="fab fa-apple me-2"></i> App Store
                            </a>
                            <a href="#" class="download-badge">
                                <i class="fab fa-google-play me-2"></i> Play Store
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Resources -->
                <div class="col-md-6 col-lg-3">
                    <h6 class="fw-semibold mb-3" style="color: #8a84ff;">Ressources</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Cours de français</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Exercices interactifs</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Grammaire</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Vocabulaire</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Conjugaison</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Examens blancs</a></li>
                    </ul>
                </div>

                <!-- About -->
                <div class="col-md-6 col-lg-3">
                    <h6 class="fw-semibold mb-3" style="color: #8a84ff;">À propos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Notre équipe</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Méthodologie</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Témoignages</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Blog pédagogique</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Contact</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Carrières</a></li>
                    </ul>
                </div>

                <!-- Legal -->
                <div class="col-md-6 col-lg-3">
                    <h6 class="fw-semibold mb-3" style="color: #8a84ff;">Légal</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Conditions d'utilisation</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Politique de confidentialité</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Mentions légales</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">Cookies</a></li>
                        <li class="mb-2"><a href="#" class="text-gray text-decoration-none footer-link">RGPD</a></li>
                    </ul>
                </div>
            </div>

            <!-- Newsletter -->
            <div class="newsletter-container p-4 p-lg-5 mt-5 text-center">
                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <h5 class="fw-bold mb-2">Abonnez-vous à notre newsletter</h5>
                        <p class="text-gray mb-3">Recevez des conseils, ressources et exercices pour améliorer votre français chaque semaine.</p>
                        <form class="row g-2 justify-content-center">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="email" class="form-control bg-transparent text-light border-end-0" placeholder="Votre email" required>
                                    <span class="input-group-text bg-transparent border-start-0">
                                        <i class="fas fa-envelope text-gray"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn newsletter-btn w-100 py-2">S'abonner</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="footer-divider my-4"></div>

            <!-- Bottom bar -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-3">
                <p class="mb-2 mb-md-0 text-gray small">
                    &copy; <span id="year"></span> <strong style="color: #8a84ff;">FrançaisFacile</strong>. Tous droits réservés.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="text-gray text-decoration-none small footer-link">Accessibilité</a>
                    <a href="#" class="text-gray text-decoration-none small footer-link">Plan du site</a>
                    <a href="#" class="text-gray text-decoration-none small footer-link">FAQ</a>
                    <a href="#" class="text-gray text-decoration-none small footer-link">Support</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Set current year
        document.getElementById("year").textContent = new Date().getFullYear();
        
        // Animation pour les éléments de fonctionnalités
        document.querySelectorAll('.feature').forEach(feature => {
            feature.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            feature.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });
    </script>