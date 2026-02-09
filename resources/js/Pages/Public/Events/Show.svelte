<script>
  import PublicLayout from '../../../Layouts/PublicLayout.svelte';
  import { router, usePage } from '@inertiajs/svelte';

  let { event = {}, canRegister = true, isRegistered = false } = $props();

  const page = usePage();
  
  const auth = $derived(page?.props?.auth || { user: null });

  function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      weekday: 'long',
      day: '2-digit',
      month: 'long',
      year: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  }

  function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: event.currency || 'COP'
    }).format(price);
  }

  function handleRegister() {
    if (!auth?.user) {
      router.get('/login', {}, {
        onSuccess: () => {
          router.get(`/eventos/${event.slug}/registrar`);
        }
      });
    } else {
      router.post(`/eventos/${event.slug}/registrar`);
    }
  }

  function handleCancelRegistration() {
    if (confirm('¿Estás seguro de que deseas cancelar tu registro?')) {
      router.delete(`/eventos/${event.slug}/cancelar`);
    }
  }

  const spotsLeft = event.capacity ? event.capacity - (event.attendees_count || 0) : null;
  const spotsPercentage = event.capacity ? ((event.attendees_count || 0) / event.capacity) * 100 : 0;
</script>

<PublicLayout>
  <div class="container py-5">
    <div class="row">
      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- Event Image -->
        <div class="card mb-4">
          <img
            src={event.featured_image || '/assets/images/products/s1.jpg'}
            class="card-img-top"
            alt={event.title}
            style="height: 400px; object-fit: cover;"
          />
        </div>

        <!-- Event Details -->
        <div class="card mb-4">
          <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-primary">{event.category?.name || 'Sin categoría'}</span>
              {#if !event.is_public}
                <span class="badge bg-warning text-dark">
                  <i class="ti ti-lock"></i> Evento Privado
                </span>
              {/if}
              {#if event.status === 'published'}
                <span class="badge bg-success">Publicado</span>
              {:else if event.status === 'cancelled'}
                <span class="badge bg-danger">Cancelado</span>
              {/if}
            </div>

            <h1 class="fw-bold mb-3">{event.title}</h1>

            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="d-flex align-items-center gap-2">
                <img
                  src={event.organizer?.avatar || '/assets/images/profile/user-1.jpg'}
                  alt="Organizer"
                  class="rounded-circle"
                  width="40"
                  height="40"
                />
                <div>
                  <p class="mb-0 fw-semibold">{event.organizer?.name || 'Organizador'}</p>
                  <p class="mb-0 text-muted fs-2">Organizador</p>
                </div>
              </div>
            </div>

            <hr>

            <h5 class="fw-semibold mb-3">Descripción</h5>
            <div class="mb-4">
              {@html event.description || '<p>Sin descripción disponible</p>'}
            </div>

            {#if event.tags && event.tags.length > 0}
              <div class="mb-3">
                <h6 class="fw-semibold mb-2">Etiquetas:</h6>
                <div class="d-flex gap-2 flex-wrap">
                  {#each event.tags as tag}
                    <span class="badge bg-light text-dark border">{tag.name}</span>
                  {/each}
                </div>
              </div>
            {/if}
          </div>
        </div>
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Registration Card -->
        <div class="card sticky-top" style="top: 20px;">
          <div class="card-body">
            {#if event.is_free}
              <h3 class="fw-bold text-success mb-3">GRATIS</h3>
            {:else}
              <h3 class="fw-bold mb-3">{formatPrice(event.price)}</h3>
            {/if}

            {#if event.status === 'cancelled'}
              <div class="alert alert-danger">
                <i class="ti ti-alert-circle me-2"></i>
                Este evento ha sido cancelado
              </div>
            {:else if isRegistered}
              <div class="alert alert-success">
                <i class="ti ti-check me-2"></i>
                Ya estás registrado en este evento
              </div>
              <button onclick={handleCancelRegistration} class="btn btn-outline-danger w-100 mb-3">
                <i class="ti ti-x me-2"></i>
                Cancelar Registro
              </button>
              <a href="/mis-tickets" class="btn btn-primary w-100">
                <i class="ti ti-ticket me-2"></i>
                Ver mi Ticket
              </a>
            {:else if !canRegister}
              <div class="alert alert-warning">
                <i class="ti ti-alert-triangle me-2"></i>
                No hay cupos disponibles
              </div>
            {:else}
              <button onclick={handleRegister} class="btn btn-primary w-100 mb-3">
                <i class="ti ti-ticket me-2"></i>
                {event.is_free ? 'Registrarse Gratis' : 'Comprar Entrada'}
              </button>
            {/if}

            <hr>

            <!-- Event Info -->
            <div class="mb-3">
              <h6 class="fw-semibold mb-2">
                <i class="ti ti-calendar me-2"></i>
                Fecha y Hora
              </h6>
              <p class="text-muted mb-1">{formatDate(event.start_date)}</p>
              {#if event.end_date}
                <p class="text-muted mb-0">Hasta: {formatDate(event.end_date)}</p>
              {/if}
            </div>

            <div class="mb-3">
              <h6 class="fw-semibold mb-2">
                <i class="ti ti-map-pin me-2"></i>
                Ubicación
              </h6>
              {#if event.is_online}
                <p class="text-muted mb-0">
                  <i class="ti ti-device-laptop me-1"></i>
                  Evento en línea
                </p>
              {:else}
                <p class="text-muted mb-1">{event.venue_name || 'Por definir'}</p>
                <p class="text-muted mb-0 fs-2">{event.location || 'Dirección por confirmar'}</p>
              {/if}
            </div>

            {#if event.capacity}
              <div class="mb-3">
                <h6 class="fw-semibold mb-2">
                  <i class="ti ti-users me-2"></i>
                  Asistentes
                </h6>
                <div class="progress mb-2" style="height: 8px;">
                  <div
                    class="progress-bar"
                    role="progressbar"
                    style="width: {spotsPercentage}%"
                    aria-valuenow={event.attendees_count}
                    aria-valuemin="0"
                    aria-valuemax={event.capacity}
                  ></div>
                </div>
                <p class="text-muted mb-0">
                  {event.attendees_count || 0} de {event.capacity} registrados
                  {#if spotsLeft !== null && spotsLeft > 0}
                    <span class="text-success">({spotsLeft} cupos disponibles)</span>
                  {/if}
                </p>
              </div>
            {/if}

            {#if event.registration_deadline}
              <div class="mb-3">
                <h6 class="fw-semibold mb-2">
                  <i class="ti ti-clock me-2"></i>
                  Fecha límite de registro
                </h6>
                <p class="text-muted mb-0">{formatDate(event.registration_deadline)}</p>
              </div>
            {/if}

            <!-- Share -->
            <hr>
            <h6 class="fw-semibold mb-2">Compartir</h6>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary btn-sm">
                <i class="ti ti-brand-facebook"></i>
              </button>
              <button class="btn btn-outline-info btn-sm">
                <i class="ti ti-brand-twitter"></i>
              </button>
              <button class="btn btn-outline-danger btn-sm">
                <i class="ti ti-brand-linkedin"></i>
              </button>
              <button class="btn btn-outline-secondary btn-sm">
                <i class="ti ti-link"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</PublicLayout>
