<script>
  import PublicLayout from '../../../Layouts/PublicLayout.svelte';
  import EventCard from '../../../Components/Event/EventCard.svelte';
  import { router, usePage } from '@inertiajs/svelte';
  import { onMount } from 'svelte';

  let { event = {}, canRegister = true, isRegistered = false, relatedEvents = [] } = $props();

  const page = usePage();
  const auth = $derived(page?.props?.auth || { user: null });

  // Countdown timer
  let countdown = $state({ days: 0, hours: 0, minutes: 0, seconds: 0 });
  let countdownInterval;

  onMount(() => {
    updateCountdown();
    countdownInterval = setInterval(updateCountdown, 1000);

    return () => clearInterval(countdownInterval);
  });

  function updateCountdown() {
    const now = new Date().getTime();
    const eventDate = new Date(event.start_date).getTime();
    const distance = eventDate - now;

    if (distance < 0) {
      countdown = { days: 0, hours: 0, minutes: 0, seconds: 0 };
      return;
    }

    countdown = {
      days: Math.floor(distance / (1000 * 60 * 60 * 24)),
      hours: Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
      minutes: Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
      seconds: Math.floor((distance % (1000 * 60)) / 1000)
    };
  }

  function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      weekday: 'long',
      day: '2-digit',
      month: 'long',
      year: 'numeric',
    }).format(date);
  }

  function formatTime(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  }

  function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      minimumFractionDigits: 0
    }).format(price);
  }

  function handleRegister() {
    if (!auth?.user) {
      router.visit('/login');
    } else {
      router.post(`/eventos/${event.slug}/registrar`);
    }
  }

  function handleCancelRegistration() {
    if (confirm('¿Estás seguro de que deseas cancelar tu registro?')) {
      router.delete(`/eventos/${event.slug}/cancelar`);
    }
  }

  function shareEvent(platform) {
    const url = window.location.href;
    const text = `¡Mira este evento! ${event.title}`;

    const urls = {
      facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
      twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`,
      linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(url)}`,
      whatsapp: `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`
    };

    if (platform === 'copy') {
      navigator.clipboard.writeText(url);
      alert('¡Enlace copiado al portapapeles!');
    } else {
      window.open(urls[platform], '_blank', 'width=600,height=400');
    }
  }

  const spotsLeft = event.capacity ? event.capacity - (event.attendees_count || 0) : null;
  const spotsPercentage = event.capacity ? ((event.attendees_count || 0) / event.capacity) * 100 : 0;
  const isSoldOut = spotsLeft !== null && spotsLeft <= 0;
  const isAlmostFull = spotsLeft !== null && spotsLeft <= 10 && spotsLeft > 0;
</script>

<PublicLayout>
  <!-- Breadcrumb -->
  <div class="bg-light py-3 border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="/">Inicio</a></li>
          <li class="breadcrumb-item"><a href="/eventos">Eventos</a></li>
          {#if event.category}
            <li class="breadcrumb-item">{event.category.name}</li>
          {/if}
          <li class="breadcrumb-item active">{event.title}</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Hero Section -->
  <section class="event-hero position-relative">
    <div class="event-hero-image" style="background-image: url('{event.featured_image || '/assets/images/products/s1.jpg'}');"></div>
    <div class="event-hero-overlay"></div>

    <div class="container position-relative" style="z-index: 2;">
      <div class="row py-5">
        <div class="col-lg-8">
          <div class="py-5">
            <!-- Category Badge -->
            <div class="mb-3">
              <span class="badge px-3 py-2 fs-6 fw-semibold" style="background: {event.category?.color || '#5D87FF'}20; color: {event.category?.color || '#5D87FF'};">
                <i class="ti {event.category?.icon || 'ti-calendar'} me-2"></i>
                {event.category?.name || 'Sin categoría'}
              </span>
              {#if event.is_online}
                <span class="badge bg-info text-white px-3 py-2 fs-6 fw-semibold ms-2">
                  <i class="ti ti-world me-1"></i>
                  Online
                </span>
              {/if}
              {#if isSoldOut}
                <span class="badge bg-danger text-white px-3 py-2 fs-6 fw-semibold ms-2">
                  <i class="ti ti-ticket-off me-1"></i>
                  Agotado
                </span>
              {:else if isAlmostFull}
                <span class="badge bg-warning text-dark px-3 py-2 fs-6 fw-semibold ms-2">
                  <i class="ti ti-alert-triangle me-1"></i>
                  ¡Últimos cupos!
                </span>
              {/if}
            </div>

            <h1 class="display-4 fw-bold text-white mb-4">{event.title}</h1>

            <!-- Event Meta -->
            <div class="d-flex flex-wrap gap-4 text-white mb-4">
              <div class="d-flex align-items-center">
                <div class="icon-box bg-white bg-opacity-25 rounded-3 p-2 me-3">
                  <i class="ti ti-calendar fs-4"></i>
                </div>
                <div>
                  <div class="fw-semibold">{formatDate(event.start_date)}</div>
                  <small class="opacity-75">{formatTime(event.start_date)}</small>
                </div>
              </div>

              <div class="d-flex align-items-center">
                <div class="icon-box bg-white bg-opacity-25 rounded-3 p-2 me-3">
                  <i class="ti {event.is_online ? 'ti-world' : 'ti-map-pin'} fs-4"></i>
                </div>
                <div>
                  <div class="fw-semibold">
                    {event.is_online ? 'Evento Virtual' : (event.venue_name || 'Ubicación')}
                  </div>
                  {#if !event.is_online && event.location}
                    <small class="opacity-75">{event.location}</small>
                  {/if}
                </div>
              </div>

              {#if event.user}
                <div class="d-flex align-items-center">
                  <div class="icon-box bg-white bg-opacity-25 rounded-3 p-2 me-3">
                    <i class="ti ti-user fs-4"></i>
                  </div>
                  <div>
                    <div class="fw-semibold">Organizado por</div>
                    <small class="opacity-75">{event.user.name}</small>
                  </div>
                </div>
              {/if}
            </div>

            <!-- Price -->
            <div class="mb-4">
              {#if event.is_free}
                <h2 class="display-6 fw-bold text-white mb-0">
                  <i class="ti ti-gift me-2"></i>
                  GRATIS
                </h2>
              {:else}
                <h2 class="display-6 fw-bold text-white mb-0">
                  {formatPrice(event.price)}
                </h2>
              {/if}
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container py-5">
    <div class="row g-4">
      <!-- Main Content -->
      <div class="col-lg-8">
        <!-- Countdown Timer -->
        {#if countdown.days > 0 || countdown.hours > 0}
          <div class="card border-0 shadow-sm mb-4 bg-gradient">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-3 text-center">
                <i class="ti ti-clock me-2"></i>
                ¡El evento comienza en!
              </h5>
              <div class="row g-3 text-center">
                <div class="col-3">
                  <div class="countdown-box">
                    <div class="countdown-number">{countdown.days}</div>
                    <div class="countdown-label">Días</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="countdown-box">
                    <div class="countdown-number">{countdown.hours}</div>
                    <div class="countdown-label">Horas</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="countdown-box">
                    <div class="countdown-number">{countdown.minutes}</div>
                    <div class="countdown-label">Minutos</div>
                  </div>
                </div>
                <div class="col-3">
                  <div class="countdown-box">
                    <div class="countdown-number">{countdown.seconds}</div>
                    <div class="countdown-label">Segundos</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        {/if}

        <!-- Description -->
        <div class="card border-0 shadow-sm mb-4">
          <div class="card-body p-4">
            <h4 class="fw-bold mb-4">
              <i class="ti ti-info-circle me-2 text-primary"></i>
              Sobre el Evento
            </h4>
            <div class="event-description">
              {@html event.description || '<p class="text-muted">Sin descripción disponible</p>'}
            </div>

            {#if event.short_description}
              <div class="alert alert-info bg-info-subtle border-0 mt-4">
                <i class="ti ti-bulb me-2"></i>
                <strong>Destacado:</strong> {event.short_description}
              </div>
            {/if}
          </div>
        </div>

        <!-- Tags -->
        {#if event.tags && event.tags.length > 0}
          <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
              <h5 class="fw-bold mb-3">
                <i class="ti ti-tag me-2 text-primary"></i>
                Etiquetas
              </h5>
              <div class="d-flex gap-2 flex-wrap">
                {#each event.tags as tag}
                  <span class="badge bg-light text-dark border px-3 py-2">
                    <i class="ti ti-hash"></i>
                    {tag.name}
                  </span>
                {/each}
              </div>
            </div>
          </div>
        {/if}
      </div>

      <!-- Sidebar -->
      <div class="col-lg-4">
        <!-- Registration Card -->
        <div class="card border-0 shadow-lg sticky-top" style="top: 20px;">
          <div class="card-body p-4">
            {#if event.status === 'cancelled'}
              <div class="alert alert-danger mb-4">
                <i class="ti ti-alert-circle me-2"></i>
                <strong>Evento Cancelado</strong>
              </div>
            {:else if isRegistered}
              <div class="alert alert-success border-0 mb-4">
                <i class="ti ti-check-circle me-2"></i>
                <strong>¡Ya estás registrado!</strong>
              </div>
              <a href="/mis-tickets" class="btn btn-primary btn-lg w-100 mb-3">
                <i class="ti ti-ticket me-2"></i>
                Ver mi Ticket
              </a>
              <button onclick={handleCancelRegistration} class="btn btn-outline-danger w-100">
                Cancelar Registro
              </button>
            {:else if isSoldOut}
              <div class="alert alert-warning border-0 mb-4">
                <i class="ti ti-alert-triangle me-2"></i>
                <strong>Sin cupos disponibles</strong>
              </div>
            {:else}
              <button onclick={handleRegister} class="btn btn-primary btn-lg w-100 mb-3 py-3">
                <i class="ti ti-ticket me-2"></i>
                {event.is_free ? 'Registrarse Gratis' : 'Comprar Entrada'}
              </button>
              {#if isAlmostFull}
                <div class="alert alert-warning border-0 small py-2">
                  <i class="ti ti-alert-triangle me-1"></i>
                  ¡Solo quedan {spotsLeft} cupos!
                </div>
              {/if}
            {/if}

            <hr>

            <!-- Capacity -->
            {#if event.capacity}
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <h6 class="fw-semibold mb-0">
                    <i class="ti ti-users me-2 text-primary"></i>
                    Asistentes
                  </h6>
                  <span class="badge bg-primary-subtle text-primary">
                    {event.attendees_count || 0}/{event.capacity}
                  </span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div
                    class="progress-bar bg-primary"
                    style="width: {spotsPercentage}%"
                  ></div>
                </div>
                <p class="text-muted small mt-2 mb-0">
                  {spotsLeft > 0 ? `${spotsLeft} cupos disponibles` : 'Evento completo'}
                </p>
              </div>
            {/if}

            <!-- Quick Info -->
            <div class="quick-info">
              <div class="info-item">
                <div class="info-icon bg-primary-subtle">
                  <i class="ti ti-calendar text-primary"></i>
                </div>
                <div class="info-content">
                  <div class="info-label">Inicio</div>
                  <div class="info-value">{formatDate(event.start_date)}</div>
                  <div class="info-detail">{formatTime(event.start_date)}</div>
                </div>
              </div>

              {#if event.end_date}
                <div class="info-item">
                  <div class="info-icon bg-success-subtle">
                    <i class="ti ti-calendar-check text-success"></i>
                  </div>
                  <div class="info-content">
                    <div class="info-label">Finaliza</div>
                    <div class="info-value">{formatDate(event.end_date)}</div>
                    <div class="info-detail">{formatTime(event.end_date)}</div>
                  </div>
                </div>
              {/if}

              <div class="info-item">
                <div class="info-icon {event.is_online ? 'bg-info-subtle' : 'bg-warning-subtle'}">
                  <i class="ti {event.is_online ? 'ti-world text-info' : 'ti-map-pin text-warning'}"></i>
                </div>
                <div class="info-content">
                  <div class="info-label">Ubicación</div>
                  <div class="info-value">
                    {event.is_online ? 'Evento Virtual' : (event.venue_name || 'Por definir')}
                  </div>
                  {#if !event.is_online && event.location}
                    <div class="info-detail">{event.location}</div>
                  {/if}
                </div>
              </div>
            </div>

            <hr>

            <!-- Share -->
            <h6 class="fw-semibold mb-3">
              <i class="ti ti-share me-2"></i>
              Compartir Evento
            </h6>
            <div class="d-flex gap-2">
              <button onclick={() => shareEvent('facebook')} class="btn btn-outline-primary flex-fill">
                <i class="ti ti-brand-facebook"></i>
              </button>
              <button onclick={() => shareEvent('twitter')} class="btn btn-outline-info flex-fill">
                <i class="ti ti-brand-twitter"></i>
              </button>
              <button onclick={() => shareEvent('whatsapp')} class="btn btn-outline-success flex-fill">
                <i class="ti ti-brand-whatsapp"></i>
              </button>
              <button onclick={() => shareEvent('copy')} class="btn btn-outline-secondary flex-fill">
                <i class="ti ti-link"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related Events -->
    {#if relatedEvents && relatedEvents.length > 0}
      <div class="container py-5 border-top">
        <div class="text-center mb-5">
          <h2 class="fw-bold mb-2">Eventos Relacionados</h2>
          <p class="text-muted">Descubre otros eventos que te pueden interesar</p>
        </div>
        <div class="row g-4">
          {#each relatedEvents as relatedEvent}
            <div class="col-lg-4">
              <EventCard event={relatedEvent} />
            </div>
          {/each}
        </div>
        <div class="text-center mt-5">
          <a href="/eventos?category_id={event.category_id}" class="btn btn-outline-primary btn-lg">
            <i class="ti ti-calendar me-2"></i>
            Ver más eventos de {event.category?.name || 'esta categoría'}
          </a>
        </div>
      </div>
    {/if}
  </div>
</PublicLayout>

<style>
  /* Hero Section */
  .event-hero {
    height: 500px;
    overflow: hidden;
  }

  .event-hero-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-size: cover;
    background-position: center;
    filter: blur(3px);
    transform: scale(1.1);
  }

  .event-hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.6) 100%);
  }

  /* Countdown */
  .countdown-box {
    background: white;
    border-radius: 12px;
    padding: 1rem;
  }

  .countdown-number {
    font-size: 2rem;
    font-weight: bold;
    color: var(--bs-primary);
    line-height: 1;
  }

  .countdown-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    margin-top: 0.5rem;
  }

  /* Quick Info */
  .quick-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }

  .info-item {
    display: flex;
    gap: 1rem;
    align-items: start;
  }

  .info-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .info-icon i {
    font-size: 1.5rem;
  }

  .info-content {
    flex: 1;
  }

  .info-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.25rem;
  }

  .info-value {
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.125rem;
  }

  .info-detail {
    font-size: 0.875rem;
    color: #6c757d;
  }

  /* Event Description */
  .event-description :global(p) {
    line-height: 1.8;
    margin-bottom: 1rem;
  }

  .event-description :global(h1),
  .event-description :global(h2),
  .event-description :global(h3),
  .event-description :global(h4),
  .event-description :global(h5),
  .event-description :global(h6) {
    margin-top: 1.5rem;
    margin-bottom: 1rem;
  }

  .event-description :global(ul),
  .event-description :global(ol) {
    margin-bottom: 1rem;
    padding-left: 2rem;
  }

  .bg-gradient {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  }

  @media (max-width: 991px) {
    .event-hero {
      height: auto;
      min-height: 400px;
    }

    .countdown-number {
      font-size: 1.5rem;
    }

    .countdown-label {
      font-size: 0.65rem;
    }
  }
</style>
