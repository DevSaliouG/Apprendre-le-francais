<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FrançaisFacile</title>
   {{--  
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Inclure Axios -->
    <!-- Inclure Font Awesome -->
   
     --}}
{{--      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
 --}}      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
         <script src="//unpkg.com/axios/dist/axios.min.js"></script>
   {{--  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> --}}
    <script src="//unpkg.com/alpinejs" defer></script>

  
     @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Floating background elements -->
    <div class="floating-element floating-element-1"></div>
    <div class="floating-element floating-element-2"></div>
    <div class="floating-element floating-element-3"></div>

    <!-- Header -->
    @include('layouts.partials.navbar')

    <!-- Main Layout -->
    <div class="layout-container">
        @auth
            <!-- Sidebar Overlay -->
            <div class="overlay"></div>

            <!-- Sidebar -->
            @include('layouts.partials.sidebar')
        @endauth

        <!-- Main Content -->
        <main class="main-content">
            <div class="content-container">
                @yield('content')
                @stack('scripts')   
            </div>
        </main>

        @auth
            @include('components.streak-popup')
        @endauth
    </div>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const overlay = document.querySelector('.overlay');
            const sidebarToggle = document.querySelector('.sidebar-toggle');

            if (sidebarToggle) {
                // Toggle sidebar on mobile
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    overlay.classList.toggle('active');
                });

                // Close sidebar when clicking outside
                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    overlay.classList.remove('active');
                });
            }

            // Floating elements animation
            const floatingElements = document.querySelectorAll('.floating-element');

            floatingElements.forEach((el, index) => {
                // Random starting position
                let x = Math.random() * 20 - 10;
                let y = Math.random() * 20 - 10;
                let xSpeed = (Math.random() - 0.5) * 0.2;
                let ySpeed = (Math.random() - 0.5) * 0.2;

                function animate() {
                    x += xSpeed;
                    y += ySpeed;

                    // Bounce off boundaries
                    if (x <= -10 || x >= 10) xSpeed *= -1;
                    if (y <= -10 || y >= 10) ySpeed *= -1;

                    el.style.transform = `translate(${x}px, ${y}px)`;
                    requestAnimationFrame(animate);
                }
                var tooltipTriggerList = [].slice.call(document.querySelectorAll(
                    '[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })

                animate();
            });

            function addQuestion() {
                const index = Date.now();
                const template = `
    <div class="question-card">
        <input type="hidden" name="questions[${index}][new]" value="1">
        <!-- ... champs question ... -->
        <button type="button" onclick="this.closest('.question-card').remove()">Supprimer</button>
    </div>`;

                document.getElementById('questions-container').insertAdjacentHTML('beforeend', template);
            }
        });
    </script>
</body>

</html>