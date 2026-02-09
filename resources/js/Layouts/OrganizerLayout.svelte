<script>
  import { router, usePage } from '@inertiajs/svelte';

  const page = usePage();
  
  const auth = $derived(page?.props?.auth || { user: null });

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
        <a href="/" class="text-nowrap logo-img">
          <img src="/assets/images/logos/logo.svg" alt="Logo" />
        </a>
        <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" onclick={toggleSidebar}>
          <i class="ti ti-x fs-6"></i>
        </div>
      </div>

      <!-- Sidebar navigation -->
      <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
        <ul id="sidebarnav">
          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Panel Organizador</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/dashboard" aria-expanded="false">
              <span><i class="ti ti-layout-dashboard"></i></span>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Gestión de Eventos</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/eventos" aria-expanded="false">
              <span><i class="ti ti-calendar"></i></span>
              <span class="hide-menu">Mis Eventos</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/eventos/crear" aria-expanded="false">
              <span><i class="ti ti-calendar-plus"></i></span>
              <span class="hide-menu">Crear Evento</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Gestión de Asistentes</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/asistentes" aria-expanded="false">
              <span><i class="ti ti-users"></i></span>
              <span class="hide-menu">Asistentes</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/checkin" aria-expanded="false">
              <span><i class="ti ti-qrcode"></i></span>
              <span class="hide-menu">Check-in</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Reportes</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/organizador/estadisticas" aria-expanded="false">
              <span><i class="ti ti-chart-bar"></i></span>
              <span class="hide-menu">Estadísticas</span>
            </a>
          </li>

          <li class="nav-small-cap mt-4">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Usuario</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/mis-eventos" aria-expanded="false">
              <span><i class="ti ti-ticket"></i></span>
              <span class="hide-menu">Mis Tickets</span>
            </a>
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
              <i class="ti ti-menu-2"></i>
            </a>
          </li>
        </ul>

        <div class="navbar-collapse justify-content-end px-0" id="navbarNav">
          <ul class="navbar-nav flex-row ms-auto align-items-center justify-content-end">

            <!-- Notifications -->
            <li class="nav-item dropdown">
              <a class="nav-link" href="javascript:void(0)" id="drop1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="ti ti-bell-ringing"></i>
                <div class="notification bg-primary rounded-circle"></div>
              </a>
              <div class="dropdown-menu content-dd dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop1">
                <div class="d-flex align-items-center justify-content-between py-3 px-7">
                  <h5 class="mb-0 fs-5 fw-semibold">Notificaciones</h5>
                  <span class="badge bg-primary rounded-4 px-3 py-1 lh-sm">5 nuevas</span>
                </div>
                <div class="message-body" data-simplebar>
                  <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                    <span class="me-3">
                      <img src="/assets/images/profile/user-1.jpg" alt="user" class="rounded-circle" width="48" height="48" />
                    </span>
                    <div class="w-75 d-inline-block v-middle">
                      <h6 class="mb-1 fw-semibold">Nuevo registro en evento</h6>
                      <span class="d-block">Hace 9 minutos</span>
                    </div>
                  </a>
                </div>
                <div class="py-6 px-7 mb-1">
                  <button class="btn btn-outline-primary w-100">Ver todas</button>
                </div>
              </div>
            </li>

            <!-- User Profile Dropdown -->
            <li class="nav-item dropdown">
              <a class="nav-link" href="javascript:void(0)" id="drop2" data-bs-toggle="dropdown" aria-expanded="false">
                <img src={auth?.user?.avatar || '/assets/images/profile/user-1.jpg'} alt="User" width="35" height="35" class="rounded-circle">
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-animate-up" aria-labelledby="drop2">
                <div class="message-body">
                  <a href="/profile" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-user fs-6"></i>
                    <p class="mb-0 fs-3">Mi Perfil</p>
                  </a>
                  <a href="/configuracion/notificaciones" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-bell fs-6"></i>
                    <p class="mb-0 fs-3">Notificaciones</p>
                  </a>
                  <button onclick={logout} class="btn btn-outline-primary mx-3 mt-2 d-block w-100">Cerrar Sesión</button>
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
