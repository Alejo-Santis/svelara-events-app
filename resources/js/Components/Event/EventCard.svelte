<script>
  import { router } from '@inertiajs/svelte';

  let { event = {}, showActions = true } = $props();

  function formatDate(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      day: 'numeric',
      month: 'short',
      year: 'numeric'
    }).format(date);
  }

  function formatTime(dateString) {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('es-CO', {
      hour: '2-digit',
      minute: '2-digit'
    }).format(date);
  }

  function getStatusBadge(status) {
    const statusMap = {
      draft: { class: 'bg-secondary', text: 'Borrador' },
      published: { class: 'bg-success', text: 'Publicado' },
      cancelled: { class: 'bg-danger', text: 'Cancelado' },
      completed: { class: 'bg-info', text: 'Completado' }
    };
    return statusMap[status] || statusMap.draft;
  }

  function viewEvent() {
    router.get(`/eventos/${event.slug}`);
  }

  function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
      style: 'currency',
      currency: 'COP',
      minimumFractionDigits: 0
    }).format(price);
  }
</script>

<div class="event-card card border-0 shadow-sm h-100 overflow-hidden">
  <div class="position-relative event-image-wrapper">
    <a href="/eventos/{event.slug}" class="d-block">
      <img
        src={event.featured_image || '/assets/images/products/s1.jpg'}
        class="card-img-top event-image"
        alt={event.title}
      />
      <div class="event-image-overlay"></div>
    </a>

    <!-- Price Badge -->
    <div class="position-absolute top-0 start-0 m-3">
      {#if !event.is_free}
        <span class="badge bg-white text-dark px-3 py-2 shadow-sm fw-bold">
          {formatPrice(event.price)}
        </span>
      {:else}
        <span class="badge bg-success text-white px-3 py-2 shadow-sm fw-bold">
          <i class="ti ti-gift me-1"></i>
          GRATIS
        </span>
      {/if}
    </div>

    <!-- Category Badge -->
    <div class="position-absolute top-0 end-0 m-3">
      <span class="badge px-3 py-2 shadow-sm fw-semibold" style="background: {event.category?.color || '#5D87FF'}15; color: {event.category?.color || '#5D87FF'};">
        {event.category?.name || 'Sin categoría'}
      </span>
    </div>
  </div>

  <div class="card-body p-4">
    <!-- Title -->
    <a href="/eventos/{event.slug}" class="text-decoration-none">
      <h5 class="card-title fw-bold mb-3 text-dark event-title">
        {event.title}
      </h5>
    </a>

    <!-- Description -->
    <p class="text-muted mb-3 event-description">
      {event.short_description || 'Sin descripción disponible'}
    </p>

    <!-- Event Info -->
    <div class="event-info">
      <!-- Date & Time -->
      <div class="d-flex align-items-start mb-2">
        <div class="info-icon bg-primary-subtle rounded-2 p-2 me-3">
          <i class="ti ti-calendar text-primary fs-5"></i>
        </div>
        <div class="flex-grow-1">
          <div class="fw-semibold text-dark small">{formatDate(event.start_date)}</div>
          <div class="text-muted small">{formatTime(event.start_date)}</div>
        </div>
      </div>

      <!-- Location -->
      <div class="d-flex align-items-start mb-2">
        <div class="info-icon bg-success-subtle rounded-2 p-2 me-3">
          <i class="ti {event.is_online ? 'ti-world' : 'ti-map-pin'} text-success fs-5"></i>
        </div>
        <div class="flex-grow-1">
          <div class="fw-semibold text-dark small">
            {#if event.is_online}
              Evento Virtual
            {:else}
              {event.venue_name || 'Ubicación'}
            {/if}
          </div>
          {#if !event.is_online && event.location}
            <div class="text-muted small text-truncate">{event.location}</div>
          {/if}
        </div>
      </div>

      <!-- Capacity -->
      {#if event.capacity}
        <div class="d-flex align-items-center mb-3">
          <div class="info-icon bg-warning-subtle rounded-2 p-2 me-3">
            <i class="ti ti-users text-warning fs-5"></i>
          </div>
          <div class="flex-grow-1">
            <div class="d-flex align-items-center justify-content-between mb-1">
              <span class="small text-muted">
                {event.attendees_count || 0} / {event.capacity} asistentes
              </span>
              <span class="small fw-semibold text-dark">
                {Math.round(((event.attendees_count || 0) / event.capacity) * 100)}%
              </span>
            </div>
            <div class="progress" style="height: 4px;">
              <div
                class="progress-bar bg-warning"
                role="progressbar"
                style="width: {((event.attendees_count || 0) / event.capacity) * 100}%"
              ></div>
            </div>
          </div>
        </div>
      {/if}
    </div>

    <!-- Action Button -->
    {#if showActions}
      <div class="mt-3 pt-3 border-top">
        <button onclick={viewEvent} class="btn btn-primary w-100 hover-lift-sm">
          Ver Detalles
          <i class="ti ti-arrow-right ms-1"></i>
        </button>
      </div>
    {/if}
  </div>
</div>

<style>
  .event-card {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 16px;
  }

  .event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
  }

  .event-image-wrapper {
    overflow: hidden;
    height: 200px;
  }

  .event-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
  }

  .event-card:hover .event-image {
    transform: scale(1.08);
  }

  .event-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .event-card:hover .event-image-overlay {
    opacity: 1;
  }

  .event-title {
    font-size: 1.1rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.8rem;
  }

  .event-title:hover {
    color: var(--bs-primary) !important;
  }

  .event-description {
    font-size: 0.875rem;
    line-height: 1.5;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 2.625rem;
  }

  .info-icon {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
  }

  .hover-lift-sm {
    transition: all 0.3s ease;
  }

  .hover-lift-sm:hover {
    transform: translateY(-2px);
  }

  .badge {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.3px;
  }

  @media (max-width: 767px) {
    .event-image-wrapper {
      height: 180px;
    }

    .event-title {
      font-size: 1rem;
    }
  }
</style>
