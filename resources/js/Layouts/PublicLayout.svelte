<script>
  import { router, usePage } from '@inertiajs/svelte';

  const page = usePage();
  const auth = $derived(page?.props?.auth || { user: null });

  let mobileMenuOpen = $state(false);

  function toggleMobileMenu() {
    mobileMenuOpen = !mobileMenuOpen;
  }

  function logout() {
    router.post('/logout');
  }
</script>

<div class="page-wrapper" id="main-wrapper">

  <!-- Navbar Start -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
      <a class="navbar-brand" href="/">
        <img src="/assets/images/logos/logo.svg" alt="Logo" height="40" />
      </a>

      <button class="navbar-toggler" type="button" onclick={toggleMobileMenu} aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse {mobileMenuOpen ? 'show' : ''}" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" href="/">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/eventos">Eventos</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/eventos/buscar">Buscar</a>
          </li>

          {#if auth?.user}
            <!-- User logged in -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="javascript:void(0)" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <img src={auth.user.avatar || '/assets/images/profile/user-1.jpg'} alt="User" width="30" height="30" class="rounded-circle me-2">
                {auth.user.name}
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="/dashboard"><i class="ti ti-layout-dashboard me-2"></i>Dashboard</a></li>
                <li><a class="dropdown-item" href="/mis-eventos"><i class="ti ti-calendar me-2"></i>Mis Eventos</a></li>
                <li><a class="dropdown-item" href="/mis-tickets"><i class="ti ti-ticket me-2"></i>Mis Tickets</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="/profile"><i class="ti ti-user me-2"></i>Perfil</a></li>
                <li><button class="dropdown-item" onclick={logout}><i class="ti ti-logout me-2"></i>Cerrar Sesión</button></li>
              </ul>
            </li>
          {:else}
            <!-- Guest -->
            <li class="nav-item">
              <a class="nav-link" href="/login">Iniciar Sesión</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-primary ms-2" href="/register">Registrarse</a>
            </li>
          {/if}
        </ul>
      </div>
    </div>
  </nav>
  <!-- Navbar End -->

  <!-- Main Content -->
  <main style="margin-top: 70px; min-height: calc(100vh - 300px);">
    <slot />
  </main>

  <!-- Footer Start -->
  <footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4 mb-lg-0">
          <h5 class="mb-3">Event Management</h5>
          <p class="text-white-50">
            Plataforma completa para la gestión de eventos. Crea, gestiona y participa en eventos de manera fácil y segura.
          </p>
          <div class="d-flex gap-3 mt-3">
            <a href="#" class="text-white-50 hover-primary"><i class="ti ti-brand-facebook fs-5"></i></a>
            <a href="#" class="text-white-50 hover-primary"><i class="ti ti-brand-twitter fs-5"></i></a>
            <a href="#" class="text-white-50 hover-primary"><i class="ti ti-brand-instagram fs-5"></i></a>
            <a href="#" class="text-white-50 hover-primary"><i class="ti ti-brand-linkedin fs-5"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
          <h6 class="mb-3">Enlaces</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="/" class="text-white-50 text-decoration-none">Inicio</a></li>
            <li class="mb-2"><a href="/eventos" class="text-white-50 text-decoration-none">Eventos</a></li>
            <li class="mb-2"><a href="/eventos/buscar" class="text-white-50 text-decoration-none">Buscar</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
          <h6 class="mb-3">Recursos</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Blog</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Ayuda</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">FAQs</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-4 mb-4 mb-lg-0">
          <h6 class="mb-3">Legal</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Términos</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Privacidad</a></li>
            <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Cookies</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-12">
          <h6 class="mb-3">Contacto</h6>
          <ul class="list-unstyled">
            <li class="mb-2"><a href="mailto:info@events.com" class="text-white-50 text-decoration-none"><i class="ti ti-mail me-2"></i>info@events.com</a></li>
            <li class="mb-2"><span class="text-white-50"><i class="ti ti-phone me-2"></i>+57 300 123 4567</span></li>
          </ul>
        </div>
      </div>

      <hr class="border-white-50 my-4">

      <div class="row">
        <div class="col-12 text-center">
          <p class="text-white-50 mb-0">
            &copy; {new Date().getFullYear()} Event Management. Todos los derechos reservados.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- Footer End -->

</div>

<style>
  .hover-primary:hover {
    color: var(--bs-primary) !important;
  }
</style>
