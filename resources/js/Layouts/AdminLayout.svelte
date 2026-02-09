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
            <span class="hide-menu">Panel Admin</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/dashboard" aria-expanded="false">
              <span><i class="ti ti-layout-dashboard"></i></span>
              <span class="hide-menu">Dashboard</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Gestión</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/usuarios" aria-expanded="false">
              <span><i class="ti ti-users"></i></span>
              <span class="hide-menu">Usuarios</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/eventos" aria-expanded="false">
              <span><i class="ti ti-calendar"></i></span>
              <span class="hide-menu">Eventos</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/categorias" aria-expanded="false">
              <span><i class="ti ti-category"></i></span>
              <span class="hide-menu">Categorías</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Seguridad</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/roles" aria-expanded="false">
              <span><i class="ti ti-shield"></i></span>
              <span class="hide-menu">Roles</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/permisos" aria-expanded="false">
              <span><i class="ti ti-lock"></i></span>
              <span class="hide-menu">Permisos</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Reportes</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/reportes" aria-expanded="false">
              <span><i class="ti ti-chart-bar"></i></span>
              <span class="hide-menu">Estadísticas</span>
            </a>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/activity-log" aria-expanded="false">
              <span><i class="ti ti-history"></i></span>
              <span class="hide-menu">Activity Log</span>
            </a>
          </li>

          <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Configuración</span>
          </li>

          <li class="sidebar-item">
            <a class="sidebar-link" href="/admin/configuracion" aria-expanded="false">
              <span><i class="ti ti-settings"></i></span>
              <span class="hide-menu">Sistema</span>
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
                  <h5 class="mb-0 fs-5 fw-semibold">Alertas del Sistema</h5>
                </div>
                <div class="message-body" data-simplebar>
                  <a href="javascript:void(0)" class="py-6 px-7 d-flex align-items-center dropdown-item">
                    <span class="me-3">
                      <div class="bg-light-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="ti ti-alert-triangle text-danger fs-6"></i>
                      </div>
                    </span>
                    <div class="w-75 d-inline-block v-middle">
                      <h6 class="mb-1 fw-semibold">Evento reportado</h6>
                      <span class="d-block">Requiere revisión</span>
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
                  <div class="mx-3 mt-2 mb-3 bg-light-primary rounded p-3">
                    <p class="mb-0 fs-2 fw-semibold text-dark">{auth?.user?.name}</p>
                    <span class="badge bg-primary mt-1">Administrador</span>
                  </div>
                  <a href="/profile" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-user fs-6"></i>
                    <p class="mb-0 fs-3">Mi Perfil</p>
                  </a>
                  <a href="/" class="d-flex align-items-center gap-2 dropdown-item">
                    <i class="ti ti-home fs-6"></i>
                    <p class="mb-0 fs-3">Ver sitio público</p>
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
