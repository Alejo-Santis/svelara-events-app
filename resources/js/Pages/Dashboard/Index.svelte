<script>
  import DashboardLayout from '../../Layouts/DashboardLayout.svelte';
  import EventCard from '../../Components/Event/EventCard.svelte';

  let {
    upcomingEvents = [],
    recentTickets = [],
    stats = {
      registered_events: 0,
      active_tickets: 0,
      attended_events: 0
    }
  } = $props();
</script>

<DashboardLayout>
  <div class="row">
    <div class="col-12">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fw-bold mb-0">Bienvenido a tu Dashboard</h2>
        <a href="/eventos" class="btn btn-primary">
          <i class="ti ti-search me-2"></i>
          Buscar Eventos
        </a>
      </div>
    </div>
  </div>

  <!-- Stats Cards -->
  <div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-4">
      <div class="card overflow-hidden rounded-2">
        <div class="position-relative">
          <div class="bg-primary rounded-2">
            <div class="row align-items-center">
              <div class="col-8">
                <div class="text-white text-center pb-4 px-3">
                  <div class="fs-7 fw-semibold text-nowrap">Eventos</div>
                  <div class="fs-7 fw-semibold text-nowrap">Registrados</div>
                </div>
              </div>
              <div class="col-4">
                <div class="fs-6 fw-bold text-white text-center">
                  {stats.registered_events}
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pt-3 p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="fw-semibold fs-4 mb-0">{stats.registered_events}</h6>
                <span class="fs-2 d-block text-muted">Total de eventos</span>
              </div>
              <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="ti ti-calendar-event fs-6 text-primary"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-4">
      <div class="card overflow-hidden rounded-2">
        <div class="position-relative">
          <div class="bg-success rounded-2">
            <div class="row align-items-center">
              <div class="col-8">
                <div class="text-white text-center pb-4 px-3">
                  <div class="fs-7 fw-semibold text-nowrap">Tickets</div>
                  <div class="fs-7 fw-semibold text-nowrap">Activos</div>
                </div>
              </div>
              <div class="col-4">
                <div class="fs-6 fw-bold text-white text-center">
                  {stats.active_tickets}
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pt-3 p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="fw-semibold fs-4 mb-0">{stats.active_tickets}</h6>
                <span class="fs-2 d-block text-muted">Tickets disponibles</span>
              </div>
              <div class="bg-light-success rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="ti ti-ticket fs-6 text-success"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-xl-4">
      <div class="card overflow-hidden rounded-2">
        <div class="position-relative">
          <div class="bg-warning rounded-2">
            <div class="row align-items-center">
              <div class="col-8">
                <div class="text-white text-center pb-4 px-3">
                  <div class="fs-7 fw-semibold text-nowrap">Eventos</div>
                  <div class="fs-7 fw-semibold text-nowrap">Asistidos</div>
                </div>
              </div>
              <div class="col-4">
                <div class="fs-6 fw-bold text-white text-center">
                  {stats.attended_events}
                </div>
              </div>
            </div>
          </div>
          <div class="card-body pt-3 p-4">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <h6 class="fw-semibold fs-4 mb-0">{stats.attended_events}</h6>
                <span class="fs-2 d-block text-muted">Eventos completados</span>
              </div>
              <div class="bg-light-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="ti ti-check fs-6 text-warning"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Upcoming Events -->
  {#if upcomingEvents.length > 0}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="card-title fw-semibold mb-0">Próximos Eventos</h5>
              <a href="/mis-eventos" class="btn btn-sm btn-primary">Ver todos</a>
            </div>
            <div class="row g-4">
              {#each upcomingEvents.slice(0, 3) as event}
                <div class="col-md-6 col-lg-4">
                  <EventCard {event} showActions={false} />
                </div>
              {/each}
            </div>
          </div>
        </div>
      </div>
    </div>
  {:else}
    <div class="row mb-4">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center py-5">
            <i class="ti ti-calendar-off fs-1 text-muted mb-3 d-block"></i>
            <h5 class="fw-semibold mb-2">No tienes eventos próximos</h5>
            <p class="text-muted mb-4">Explora y regístrate en eventos que te interesen</p>
            <a href="/eventos" class="btn btn-primary">
              <i class="ti ti-search me-2"></i>
              Buscar Eventos
            </a>
          </div>
        </div>
      </div>
    </div>
  {/if}

  <!-- Recent Tickets -->
  {#if recentTickets.length > 0}
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-4">
              <h5 class="card-title fw-semibold mb-0">Tickets Recientes</h5>
              <a href="/mis-tickets" class="btn btn-sm btn-primary">Ver todos</a>
            </div>
            <div class="table-responsive">
              <table class="table table-hover text-nowrap mb-0 align-middle">
                <thead>
                  <tr>
                    <th>Evento</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  {#each recentTickets.slice(0, 5) as ticket}
                    <tr>
                      <td>
                        <div class="d-flex align-items-center gap-2">
                          <img src={ticket.event.featured_image || '/assets/images/products/s1.jpg'} width="48" height="48" class="rounded" alt="" />
                          <div>
                            <h6 class="fw-semibold mb-0">{ticket.event.title}</h6>
                            <span class="fs-2 text-muted">{ticket.ticket_number}</span>
                          </div>
                        </div>
                      </td>
                      <td>
                        <span class="fs-3">{new Date(ticket.event.start_date).toLocaleDateString('es-CO')}</span>
                      </td>
                      <td>
                        {#if ticket.status === 'active'}
                          <span class="badge bg-success">Activo</span>
                        {:else if ticket.status === 'used'}
                          <span class="badge bg-info">Usado</span>
                        {:else if ticket.status === 'cancelled'}
                          <span class="badge bg-danger">Cancelado</span>
                        {/if}
                      </td>
                      <td>
                        <a href="/mis-tickets/{ticket.id}" class="btn btn-sm btn-primary">
                          Ver Ticket
                        </a>
                      </td>
                    </tr>
                  {/each}
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  {/if}
</DashboardLayout>
