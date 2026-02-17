<script>
  import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
  import EventCard from '../../Components/Event/EventCard.svelte';
  import { usePage } from '@inertiajs/svelte';

  let { upcomingEvents = [], recentTickets = [], stats = {}, recommendedEvents = [] } = $props();

  const page = usePage();
  const auth = $derived(page?.props?.auth || { user: null });

  function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    }).format(date);
  }

  function getGreeting() {
    const hour = new Date().getHours();
    if (hour < 12) return '¡Buenos días';
    if (hour < 19) return '¡Buenas tardes';
    return '¡Buenas noches';
  }
</script>

<svelte:head>
  <title>Dashboard - EventHub</title>
</svelte:head>

<DashboardLayout>
  <!-- Welcome Section -->
  <div class="row mb-4">
    <div class="col-12">
      <div class="welcome-card">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <h2 class="fw-bold mb-2">{getGreeting()}, {auth?.user?.name}! 👋</h2>
            <p class="text-muted mb-0">
              Aquí está un resumen de tus eventos y actividades recientes
            </p>
          </div>
          <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
            <a href="/eventos" class="btn btn-primary">
              <i class="ti ti-search me-2"></i>
              Explorar Eventos
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <!-- Registered Events -->
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted mb-1 small">Próximos Eventos</p>
              <h3 class="fw-bold mb-0">{stats.registered_events || 0}</h3>
            </div>
            <div class="stat-icon bg-primary-subtle">
              <i class="ti ti-calendar-event text-primary fs-4"></i>
            </div>
          </div>
          <div class="mt-3">
            <span class="badge bg-primary-subtle text-primary">
              <i class="ti ti-trending-up me-1"></i>
              Activos
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Active Tickets -->
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted mb-1 small">Tickets Activos</p>
              <h3 class="fw-bold mb-0">{stats.active_tickets || 0}</h3>
            </div>
            <div class="stat-icon bg-success-subtle">
              <i class="ti ti-ticket text-success fs-4"></i>
            </div>
          </div>
          <div class="mt-3">
            <a href="/mis-tickets" class="text-success text-decoration-none small">
              Ver tickets <i class="ti ti-arrow-right"></i>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Attended Events -->
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted mb-1 small">Eventos Asistidos</p>
              <h3 class="fw-bold mb-0">{stats.attended_events || 0}</h3>
            </div>
            <div class="stat-icon bg-info-subtle">
              <i class="ti ti-check-circle text-info fs-4"></i>
            </div>
          </div>
          <div class="mt-3">
            <span class="badge bg-info-subtle text-info">
              <i class="ti ti-history me-1"></i>
              Completados
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- Cancelled Events -->
    <div class="col-lg-3 col-md-6">
      <div class="card stat-card border-0 shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between">
            <div>
              <p class="text-muted mb-1 small">Cancelados</p>
              <h3 class="fw-bold mb-0">{stats.cancelled_events || 0}</h3>
            </div>
            <div class="stat-icon bg-warning-subtle">
              <i class="ti ti-x-circle text-warning fs-4"></i>
            </div>
          </div>
          <div class="mt-3">
            <span class="badge bg-warning-subtle text-warning">
              <i class="ti ti-alert-triangle me-1"></i>
              Historial
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Left Column -->
    <div class="col-lg-8">
      <!-- Upcoming Events -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0">
              <i class="ti ti-calendar me-2 text-primary"></i>
              Mis Próximos Eventos
            </h5>
            <a href="/mis-eventos" class="text-primary text-decoration-none small">
              Ver todos <i class="ti ti-arrow-right"></i>
            </a>
          </div>

          {#if upcomingEvents.length > 0}
            <div class="row g-3">
              {#each upcomingEvents as event}
                <div class="col-md-6">
                  <EventCard {event} />
                </div>
              {/each}
            </div>
          {:else}
            <div class="empty-state text-center py-5">
              <i class="ti ti-calendar-off fs-1 text-muted mb-3"></i>
              <h6 class="text-muted">No tienes eventos próximos</h6>
              <p class="text-muted small mb-3">
                Explora nuestra selección de eventos y encuentra el perfecto para ti
              </p>
              <a href="/eventos" class="btn btn-primary">
                <i class="ti ti-search me-2"></i>
                Explorar Eventos
              </a>
            </div>
          {/if}
        </div>
      </div>

      <!-- Recent Activity / Tickets -->
      {#if recentTickets.length > 0}
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-bold mb-4">
              <i class="ti ti-history me-2 text-info"></i>
              Actividad Reciente
            </h5>

            <div class="activity-list">
              {#each recentTickets as ticket}
                <div class="activity-item">
                  <div class="activity-icon bg-success-subtle">
                    <i class="ti ti-check text-success"></i>
                  </div>
                  <div class="activity-content flex-grow-1">
                    <h6 class="mb-1">{ticket.title}</h6>
                    <p class="text-muted small mb-0">
                      <i class="ti ti-calendar-check me-1"></i>
                      Asististe el {formatDate(ticket.start_date)}
                    </p>
                  </div>
                  <div class="activity-meta">
                    <span class="badge bg-success-subtle text-success">Completado</span>
                  </div>
                </div>
              {/each}
            </div>
          </div>
        </div>
      {/if}
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
      <!-- Quick Actions -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
          <h5 class="fw-bold mb-4">
            <i class="ti ti-bolt me-2 text-warning"></i>
            Acciones Rápidas
          </h5>

          <div class="d-grid gap-2">
            <a href="/eventos" class="btn btn-outline-primary">
              <i class="ti ti-search me-2"></i>
              Buscar Eventos
            </a>
            <a href="/mis-tickets" class="btn btn-outline-success">
              <i class="ti ti-ticket me-2"></i>
              Mis Tickets
            </a>
            <a href="/mis-eventos" class="btn btn-outline-info">
              <i class="ti ti-calendar me-2"></i>
              Mis Eventos
            </a>
            <a href="/perfil" class="btn btn-outline-secondary">
              <i class="ti ti-user me-2"></i>
              Mi Perfil
            </a>
          </div>
        </div>
      </div>

      <!-- Recommended Events -->
      {#if recommendedEvents.length > 0}
        <div class="card border-0 shadow-sm">
          <div class="card-body">
            <h5 class="fw-bold mb-4">
              <i class="ti ti-sparkles me-2 text-warning"></i>
              Recomendados para ti
            </h5>

            <div class="recommended-list">
              {#each recommendedEvents as event}
                <a href="/eventos/{event.slug}" class="recommended-item text-decoration-none">
                  <div class="d-flex gap-3">
                    <img
                      src={event.featured_image || '/assets/images/products/s1.jpg'}
                      alt={event.title}
                      class="recommended-image"
                    />
                    <div class="flex-grow-1">
                      <h6 class="mb-1 text-dark">{event.title}</h6>
                      <p class="text-muted small mb-1">
                        <i class="ti ti-calendar me-1"></i>
                        {formatDate(event.start_date)}
                      </p>
                      <span class="badge bg-primary-subtle text-primary small">
                        {event.category?.name || 'Sin categoría'}
                      </span>
                    </div>
                  </div>
                </a>
              {/each}
            </div>

            <div class="text-center mt-3">
              <a href="/eventos" class="text-primary text-decoration-none small">
                Ver más recomendaciones <i class="ti ti-arrow-right"></i>
              </a>
            </div>
          </div>
        </div>
      {/if}
    </div>
  </div>
</DashboardLayout>

<style>
  /* Welcome Card */
  .welcome-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 16px;
    padding: 2rem;
    color: white;
  }

  /* Stat Cards */
  .stat-card {
    border-radius: 12px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
  }

  .stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Activity List */
  .activity-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .activity-item {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: background 0.3s ease;
  }

  .activity-item:hover {
    background: #e9ecef;
  }

  .activity-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .activity-content h6 {
    font-size: 0.95rem;
    font-weight: 600;
  }

  /* Recommended List */
  .recommended-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .recommended-item {
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    transition: all 0.3s ease;
    display: block;
  }

  .recommended-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
  }

  .recommended-image {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 10px;
    flex-shrink: 0;
  }

  /* Empty State */
  .empty-state i {
    display: block;
  }

  /* Card Styling */
  .card {
    border-radius: 12px;
  }

  /* Responsive */
  @media (max-width: 991px) {
    .welcome-card {
      padding: 1.5rem;
    }

    .stat-icon {
      width: 48px;
      height: 48px;
    }
  }
</style>
