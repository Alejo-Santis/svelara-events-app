<script>
  import { router, usePage } from '@inertiajs/svelte';

  const page = usePage();

  const auth = $derived(page?.props?.auth || { user: null });

  // Sidebar toggle state
  let sidebarOpen = $state(true);

  function toggleSidebar() {
    sidebarOpen = !sidebarOpen;
  }

  function logout() {
    router.post('/logout');
  }
</script>

<div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6"
     data-sidebartype={sidebarOpen ? 'full' : 'mini-sidebar'} data-sidebar-position="fixed" data-header-position="fixed">

  <!-- Sidebar Start -->
  <aside class="left-sidebar">
    <div>
      <div class="brand-logo d-flex align-items-center justify-content-between">
        <a href="/dashboard" class="text-nowrap logo-img d-flex align-items-center gap-2" style="text-decoration: none;">
          <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
            <i class="ti ti-calendar-event" style="font-size: 1.5rem;"></i>
          </div>
          <span style="font-size: 1.25rem; font-weight: 700; background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">EventHub</span>
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer"  onclick={toggleSidebar}>
          <i class="ti ti-x fs-6"></i>
        </div>
      </div>

      <!-- Sidebar navigation -->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Inicio</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/dashboard" aria-expanded="false">
              <span><i class="ti ti-layout-dashboard"></i></span>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/mis-eventos" aria-expanded="false">
              <span><i class="ti ti-calendar-event"></i></span>
              <span class="hide-menu">Mis Eventos</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/mis-tickets" aria-expanded="false">
              <span><i class="ti ti-ticket"></i></span>
              <span class="hide-menu">Mis Tickets</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Explorar</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/eventos" aria-expanded="false">
              <span><i class="ti ti-search"></i></span>
              <span class="hide-menu">Buscar Eventos</span>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>
  <!-- Sidebar End -->

  <!-- Main wrapper -->
  <div class="body-wrapper">

    <!-- Header Start -->
    <header class="app-header">
      <nav class="navbar navbar-expand-lg navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item d-block d-xl-none">
            <a class="nav-link sidebartoggler" onclick={toggleSidebar} href="javascript:void(0)">
              <i class="ti ti-menu-2" style="font-size: 1.5rem; color: #2c3e50;"></i>
            </a>
          </li>
        </ul>

        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
          <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

            <!-- Notifications Bell -->
            <li class="nav-item me-2">
              <a class="nav-link position-relative" href="/configuracion/notificaciones" style="color: #2c3e50;">
                <i class="ti ti-bell" style="font-size: 1.3rem;"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                  0
                </span>
              </a>
            </li>

            <li class="nav-item me-3">
              <span class="nav-link" style="color: #2c3e50; font-weight: 500;">
                {auth?.user?.name || 'Usuario'}
              </span>
            </li>

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src={auth?.user?.avatar_url || '/assets/images/profile/user-1.jpg'} alt="User" width="35" height="35" class="rounded-circle">
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2" style="min-width: 200px;">
                <div class="message-body">
                  <a href="/profile" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-user fs-6"></i>
                    <p class="mb-0 fs-3">Mi Perfil</p>
                  </a>
                  <a href="/configuracion/notificaciones" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-bell fs-6"></i>
                    <p class="mb-0 fs-3">Notificaciones</p>
                  </a>
                  <div class="px-3 mt-2">
                    <button onclick={logout} class="btn btn-outline-primary d-block w-100" style="white-space: nowrap; padding: 0.5rem 1rem;">
                      Cerrar Sesión
                    </button>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <!-- Header End -->

    <!-- Content -->
    <div class="container-fluid">
      <slot />
    </div>

  </div>
</div>
