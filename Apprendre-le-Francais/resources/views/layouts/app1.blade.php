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
    <main class="{{ auth()->check() ? 'main-content' : 'main-content-full' }}">
      <div class="{{ auth()->check() ? 'content-container' : 'guest-content' }}">
       
          @yield('content')     
      </div>
    </main>
  </div>

  <!-- Footer -->
  @include('layouts.partials.footer')