<script>
  import PublicLayout from '../../Layouts/PublicLayout.svelte';
  import EventCard from '../../Components/Event/EventCard.svelte';
  import { router } from '@inertiajs/svelte';

  let { featuredEvents = [], upcomingEvents = [], categories = [] } = $props();
</script>

<PublicLayout>
  <!-- Hero Section -->
  <section class="hero-gradient position-relative overflow-hidden">
    <div class="container py-5">
      <div class="row align-items-center min-vh-75">
        <div class="col-lg-6 py-5">
          <div class="badge bg-light-primary text-primary px-3 py-2 rounded-pill mb-3 fade-in">
            <i class="ti ti-sparkles me-1"></i>
            Plataforma #1 de Eventos
          </div>
          <h1 class="display-3 fw-bold mb-4 text-dark fade-in-up">
            Descubre Eventos que
            <span class="text-gradient">Transforman</span>
          </h1>
          <p class="fs-5 text-muted mb-4 lh-lg fade-in-up" style="animation-delay: 0.1s;">
            Conecta con comunidades vibrantes, aprende de expertos y vive experiencias
            memorables. Todo en un solo lugar.
          </p>
          <div class="d-flex flex-wrap gap-3 mb-4 fade-in-up" style="animation-delay: 0.2s;">
            <a href="/eventos" class="btn btn-primary btn-lg px-4 shadow-sm hover-lift">
              <i class="ti ti-search me-2"></i>
              Explorar Eventos
            </a>
            <a href="/register" class="btn btn-outline-dark btn-lg px-4 hover-lift">
              Comenzar Gratis
              <i class="ti ti-arrow-right ms-2"></i>
            </a>
          </div>
          <div class="d-flex align-items-center gap-4 text-muted fade-in-up" style="animation-delay: 0.3s;">
            <div class="d-flex align-items-center">
              <i class="ti ti-check-circle text-success me-2"></i>
              <small>Gratis para comenzar</small>
            </div>
            <div class="d-flex align-items-center">
              <i class="ti ti-check-circle text-success me-2"></i>
              <small>Sin tarjeta de crédito</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 d-none d-lg-block fade-in-right">
          <div class="position-relative">
            <img src="/assets/images/backgrounds/event-hero.svg" alt="Events" class="img-fluid floating" />
          </div>
        </div>
      </div>
    </div>

    <!-- Decorative shapes -->
    <div class="hero-shape-1"></div>
    <div class="hero-shape-2"></div>
  </section>

  <!-- Featured Events Section -->
  {#if featuredEvents.length > 0}
    <section class="py-5 py-lg-6 bg-light">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
              Destacados
            </span>
            <h2 class="display-6 fw-bold mb-3">Eventos Más Populares</h2>
            <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
              Únete a miles de personas en los eventos más esperados del mes
            </p>
          </div>
        </div>
        <div class="row g-4">
          {#each featuredEvents as event}
            <div class="col-md-6 col-lg-4 fade-in-up">
              <EventCard {event} />
            </div>
          {/each}
        </div>
        <div class="row mt-5">
          <div class="col-12 text-center">
            <a href="/eventos" class="btn btn-outline-primary btn-lg px-5 hover-lift">
              Ver Todos los Eventos
              <i class="ti ti-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
      </div>
    </section>
  {/if}

  <!-- Categories Section -->
  {#if categories.length > 0}
    <section class="py-5 py-lg-6">
      <div class="container">
        <div class="row mb-5">
          <div class="col-12 text-center">
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
              Categorías
            </span>
            <h2 class="display-6 fw-bold mb-3">Explora por Intereses</h2>
            <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
              Descubre eventos organizados por tus temas favoritos
            </p>
          </div>
        </div>
        <div class="row g-4">
          {#each categories.slice(0, 6) as category}
            <div class="col-md-6 col-lg-4 fade-in-up">
              <a href="/eventos?category_id={category.id}" class="text-decoration-none">
                <div class="category-card card border-0 shadow-sm h-100 hover-lift">
                  <div class="card-body text-center p-4">
                    <div class="category-icon-wrapper mx-auto mb-3" style="background: {category.color}15;">
                      <i class="ti {category.icon || 'ti-calendar'} fs-1" style="color: {category.color}"></i>
                    </div>
                    <h5 class="card-title fw-bold mb-2">{category.name}</h5>
                    <p class="text-muted small mb-3">{category.description}</p>
                    <div class="d-flex align-items-center justify-content-center gap-2 text-muted">
                      <i class="ti ti-calendar-event fs-5"></i>
                      <span class="small">{category.events_count || 0} eventos</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          {/each}
        </div>
      </div>
    </section>
  {/if}

  <!-- Upcoming Events Section -->
  {#if upcomingEvents.length > 0}
    <section class="py-5 py-lg-6 bg-light">
      <div class="container">
        <div class="row mb-5 align-items-end">
          <div class="col-lg-8">
            <span class="badge bg-warning-subtle text-warning px-3 py-2 rounded-pill mb-3">
              Próximamente
            </span>
            <h2 class="display-6 fw-bold mb-3">No Te Pierdas Estos Eventos</h2>
            <p class="text-muted fs-5">
              Los eventos más esperados están a punto de comenzar
            </p>
          </div>
          <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="/eventos" class="btn btn-outline-dark btn-lg px-4 hover-lift">
              Ver Calendario Completo
              <i class="ti ti-arrow-right ms-2"></i>
            </a>
          </div>
        </div>
        <div class="row g-4">
          {#each upcomingEvents as event}
            <div class="col-md-6 col-lg-4 fade-in-up">
              <EventCard {event} />
            </div>
          {/each}
        </div>
      </div>
    </section>
  {/if}

  <!-- CTA Section -->
  <section class="cta-gradient position-relative overflow-hidden py-5 py-lg-6">
    <div class="container position-relative">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8">
          <div class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill mb-4">
            <i class="ti ti-rocket me-1"></i>
            Para Organizadores
          </div>
          <h2 class="display-5 fw-bold mb-4 text-white">
            Lleva tu Evento al<br />Siguiente Nivel
          </h2>
          <p class="lead mb-5 text-white opacity-90 mx-auto" style="max-width: 550px;">
            Únete a cientos de organizadores que confían en nuestra plataforma.
            Herramientas profesionales, soporte dedicado, resultados garantizados.
          </p>
          <div class="d-flex flex-wrap justify-content-center gap-3">
            <a href="/register" class="btn btn-light btn-lg px-5 shadow hover-lift">
              <i class="ti ti-user-plus me-2"></i>
              Crear Cuenta Gratis
            </a>
            <a href="/eventos" class="btn btn-outline-light btn-lg px-5 hover-lift">
              Ver Ejemplos
            </a>
          </div>
          <div class="mt-4">
            <small class="text-white opacity-75">
              <i class="ti ti-check me-1"></i> Gratis para comenzar
              <span class="mx-2">•</span>
              <i class="ti ti-check me-1"></i> Sin comisiones ocultas
              <span class="mx-2">•</span>
              <i class="ti ti-check me-1"></i> Soporte 24/7
            </small>
          </div>
        </div>
      </div>
    </div>

    <!-- Decorative elements -->
    <div class="cta-shape-1"></div>
    <div class="cta-shape-2"></div>
  </section>

  <!-- Features Section -->
  <section class="py-5 py-lg-6">
    <div class="container">
      <div class="row mb-5">
        <div class="col-12 text-center">
          <span class="badge bg-info-subtle text-info px-3 py-2 rounded-pill mb-3">
            Características
          </span>
          <h2 class="display-6 fw-bold mb-3">Todo lo que Necesitas</h2>
          <p class="text-muted fs-5 mx-auto" style="max-width: 600px;">
            Herramientas profesionales para crear experiencias memorables
          </p>
        </div>
      </div>
      <div class="row g-4 g-lg-5">
        <div class="col-md-6 col-lg-3 fade-in-up">
          <div class="feature-card text-center h-100">
            <div class="feature-icon-wrapper bg-primary-subtle mb-4 mx-auto">
              <i class="ti ti-calendar-event text-primary"></i>
            </div>
            <h5 class="fw-bold mb-3">Fácil de Usar</h5>
            <p class="text-muted mb-0">
              Interfaz intuitiva para crear y gestionar eventos en minutos, sin complicaciones
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 fade-in-up" style="animation-delay: 0.1s;">
          <div class="feature-card text-center h-100">
            <div class="feature-icon-wrapper bg-success-subtle mb-4 mx-auto">
              <i class="ti ti-ticket text-success"></i>
            </div>
            <h5 class="fw-bold mb-3">Tickets QR</h5>
            <p class="text-muted mb-0">
              Sistema seguro de tickets digitales con códigos QR para acceso rápido
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 fade-in-up" style="animation-delay: 0.2s;">
          <div class="feature-card text-center h-100">
            <div class="feature-icon-wrapper bg-warning-subtle mb-4 mx-auto">
              <i class="ti ti-credit-card text-warning"></i>
            </div>
            <h5 class="fw-bold mb-3">Pagos Seguros</h5>
            <p class="text-muted mb-0">
              Múltiples métodos de pago integrados con la máxima seguridad
            </p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 fade-in-up" style="animation-delay: 0.3s;">
          <div class="feature-card text-center h-100">
            <div class="feature-icon-wrapper bg-danger-subtle mb-4 mx-auto">
              <i class="ti ti-chart-bar text-danger"></i>
            </div>
            <h5 class="fw-bold mb-3">Estadísticas</h5>
            <p class="text-muted mb-0">
              Analytics detallados para analizar el rendimiento de tus eventos
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats-section position-relative overflow-hidden py-5 py-lg-6">
    <div class="container">
      <div class="row mb-5">
        <div class="col-12 text-center">
          <span class="badge bg-white bg-opacity-25 text-white px-3 py-2 rounded-pill mb-3">
            Nuestros Números
          </span>
          <h2 class="display-6 fw-bold mb-3 text-white">
            La Confianza de Miles
          </h2>
          <p class="text-white opacity-90 fs-5 mx-auto" style="max-width: 600px;">
            Únete a la comunidad de eventos más grande de Colombia
          </p>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-6 col-lg-3">
          <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
              <div class="stat-icon bg-primary-subtle mb-3 mx-auto">
                <i class="ti ti-calendar-event text-primary"></i>
              </div>
              <h2 class="display-5 fw-bold mb-2 text-gradient-primary">500+</h2>
              <p class="text-muted mb-0 fw-semibold">Eventos Activos</p>
              <small class="text-muted">Este mes</small>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
              <div class="stat-icon bg-success-subtle mb-3 mx-auto">
                <i class="ti ti-users text-success"></i>
              </div>
              <h2 class="display-5 fw-bold mb-2 text-gradient-success">50K+</h2>
              <p class="text-muted mb-0 fw-semibold">Usuarios</p>
              <small class="text-muted">Registrados</small>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
              <div class="stat-icon bg-warning-subtle mb-3 mx-auto">
                <i class="ti ti-trophy text-warning"></i>
              </div>
              <h2 class="display-5 fw-bold mb-2 text-gradient-warning">100+</h2>
              <p class="text-muted mb-0 fw-semibold">Organizadores</p>
              <small class="text-muted">Profesionales</small>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="stat-card card border-0 shadow-lg h-100">
            <div class="card-body text-center p-4">
              <div class="stat-icon bg-danger-subtle mb-3 mx-auto">
                <i class="ti ti-star text-danger"></i>
              </div>
              <h2 class="display-5 fw-bold mb-2 text-gradient-danger">4.9</h2>
              <p class="text-muted mb-0 fw-semibold">Valoración</p>
              <small class="text-muted">De 5 estrellas</small>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Decorative background -->
    <div class="stats-bg-shape-1"></div>
    <div class="stats-bg-shape-2"></div>
  </section>
</PublicLayout>

<style>
  /* Hero Section */
  .hero-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
  }

  .min-vh-75 {
    min-height: 75vh;
  }

  .text-gradient {
    background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .hero-shape-1 {
    position: absolute;
    top: -50px;
    right: -50px;
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #5D87FF15 0%, #49BEFF15 100%);
    border-radius: 50%;
    z-index: 0;
  }

  .hero-shape-2 {
    position: absolute;
    bottom: -100px;
    left: -100px;
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #13DEB915 0%, #FFAE1F15 100%);
    border-radius: 50%;
    z-index: 0;
  }

  /* CTA Section */
  .cta-gradient {
    background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%);
  }

  .cta-shape-1 {
    position: absolute;
    top: -80px;
    right: -80px;
    width: 300px;
    height: 300px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  .cta-shape-2 {
    position: absolute;
    bottom: -60px;
    left: -60px;
    width: 250px;
    height: 250px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
  }

  /* Animations */
  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInRight {
    from {
      opacity: 0;
      transform: translateX(30px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }

  @keyframes floating {
    0%, 100% {
      transform: translateY(0px);
    }
    50% {
      transform: translateY(-20px);
    }
  }

  .fade-in {
    animation: fadeIn 0.6s ease-out;
  }

  .fade-in-up {
    animation: fadeInUp 0.8s ease-out;
  }

  .fade-in-right {
    animation: fadeInRight 0.8s ease-out;
  }

  .floating {
    animation: floating 3s ease-in-out infinite;
  }

  /* Utility Classes */
  .py-lg-6 {
    padding-top: 5rem !important;
    padding-bottom: 5rem !important;
  }

  /* Feature Cards */
  .feature-card {
    padding: 1rem;
    transition: transform 0.3s ease;
  }

  .feature-icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
  }

  .feature-icon-wrapper i {
    font-size: 2rem;
  }

  .feature-card:hover .feature-icon-wrapper {
    transform: scale(1.1) rotate(5deg);
  }

  /* Category Cards */
  .category-card {
    transition: all 0.3s ease;
    border-radius: 16px;
  }

  .category-icon-wrapper {
    width: 80px;
    height: 80px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
  }

  /* Hover Effects */
  .hover-lift {
    transition: all 0.3s ease;
  }

  .hover-lift:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
  }

  .category-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12) !important;
  }

  /* Stats Section */
  .stats-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    position: relative;
  }

  .stats-bg-shape-1 {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 400px;
    height: 400px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
  }

  .stats-bg-shape-2 {
    position: absolute;
    bottom: -80px;
    left: -80px;
    width: 350px;
    height: 350px;
    background: rgba(255, 255, 255, 0.05);
    border-radius: 50%;
  }

  .stat-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
  }

  .stat-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15) !important;
  }

  .stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.3s ease;
  }

  .stat-icon i {
    font-size: 2rem;
  }

  .stat-card:hover .stat-icon {
    transform: scale(1.15) rotate(5deg);
  }

  .text-gradient-primary {
    background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .text-gradient-success {
    background: linear-gradient(135deg, #13DEB9 0%, #06B6D4 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .text-gradient-warning {
    background: linear-gradient(135deg, #FFAE1F 0%, #FB923C 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .text-gradient-danger {
    background: linear-gradient(135deg, #FA896B 0%, #F43F5E 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .py-lg-6 {
      padding-top: 3rem !important;
      padding-bottom: 3rem !important;
    }

    .hero-shape-1,
    .hero-shape-2,
    .cta-shape-1,
    .cta-shape-2,
    .stats-bg-shape-1,
    .stats-bg-shape-2 {
      display: none;
    }

    .stat-card {
      margin-bottom: 0;
    }
  }

  @media (max-width: 767px) {
    .stat-icon {
      width: 60px;
      height: 60px;
    }

    .stat-icon i {
      font-size: 1.75rem;
    }
  }
</style>
