<script>
  import PublicLayout from '../../../Layouts/PublicLayout.svelte';
  import EventCard from '../../../Components/Event/EventCard.svelte';
  import Pagination from '../../../Components/Shared/Pagination.svelte';
  import { router } from '@inertiajs/svelte';

  let { events = { data: [], links: [], total: 0 }, categories = [], filters = {}, total = 0 } = $props();

  let hasEvents = $derived(events.data && events.data.length > 0);
  let searchQuery = $state(filters.search || '');
  let selectedCategory = $state(filters.category_id || '');
  let selectedSort = $state(filters.sort || 'date_asc');
  let priceType = $state(filters.price_type || '');
  let eventType = $state(filters.event_type || '');
  let dateRange = $state(filters.date_range || '');
  let showFilters = $state(false);

  function applyFilters() {
    const params = {
      search: searchQuery || undefined,
      category_id: selectedCategory || undefined,
      sort: selectedSort || undefined,
      price_type: priceType || undefined,
      event_type: eventType || undefined,
      date_range: dateRange || undefined,
    };

    // Remove undefined values
    Object.keys(params).forEach(key => params[key] === undefined && delete params[key]);

    router.get('/eventos', params, {
      preserveState: true,
      preserveScroll: true,
    });
  }

  function clearFilters() {
    searchQuery = '';
    selectedCategory = '';
    selectedSort = 'date_asc';
    priceType = '';
    eventType = '';
    dateRange = '';
    router.get('/eventos');
  }

  function handleSearch(e) {
    if (e.key === 'Enter') {
      applyFilters();
    }
  }

  $effect(() => {
    // Auto-apply filters when they change (except search)
    if (selectedCategory || selectedSort !== 'date_asc' || priceType || eventType || dateRange) {
      applyFilters();
    }
  });
</script>

<PublicLayout>
  <!-- Breadcrumb -->
  <div class="bg-light py-3 border-bottom">
    <div class="container">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="/">Inicio</a></li>
          <li class="breadcrumb-item active">Eventos</li>
        </ol>
      </nav>
    </div>
  </div>

  <!-- Search Hero -->
  <section class="search-hero bg-gradient py-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-10">
          <div class="text-center mb-4">
            <h1 class="display-5 fw-bold mb-3">Explora Eventos</h1>
            <p class="lead text-muted">Descubre experiencias únicas cerca de ti</p>
          </div>

          <!-- Search Bar -->
          <div class="card shadow-lg border-0">
            <div class="card-body p-2">
              <div class="row g-2">
                <div class="col-lg-9">
                  <div class="input-group input-group-lg">
                    <span class="input-group-text bg-transparent border-0">
                      <i class="ti ti-search text-muted"></i>
                    </span>
                    <input
                      type="text"
                      class="form-control border-0 shadow-none"
                      placeholder="Buscar eventos por nombre o descripción..."
                      bind:value={searchQuery}
                      onkeydown={handleSearch}
                    />
                  </div>
                </div>
                <div class="col-lg-3">
                  <button onclick={applyFilters} class="btn btn-primary btn-lg w-100">
                    <i class="ti ti-search me-2"></i>
                    Buscar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <div class="container py-5">
    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3 mb-4">
        <div class="position-sticky" style="top: 20px;">
          <!-- Mobile Filter Toggle -->
          <button
            class="btn btn-outline-primary w-100 mb-3 d-lg-none"
            onclick={() => showFilters = !showFilters}
          >
            <i class="ti ti-filter me-2"></i>
            {showFilters ? 'Ocultar' : 'Mostrar'} Filtros
          </button>

          <div class="filters-sidebar {showFilters ? 'd-block' : 'd-none d-lg-block'}">
            <!-- Category Filter -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-body">
                <h6 class="fw-bold mb-3">
                  <i class="ti ti-category me-2 text-primary"></i>
                  Categoría
                </h6>
                <select
                  class="form-select"
                  bind:value={selectedCategory}
                  onchange={applyFilters}
                >
                  <option value="">Todas las categorías</option>
                  {#each categories as category}
                    <option value={category.id}>
                      {category.name} ({category.events_count || 0})
                    </option>
                  {/each}
                </select>
              </div>
            </div>

            <!-- Date Range Filter -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-body">
                <h6 class="fw-bold mb-3">
                  <i class="ti ti-calendar me-2 text-primary"></i>
                  Fecha
                </h6>
                <select
                  class="form-select"
                  bind:value={dateRange}
                  onchange={applyFilters}
                >
                  <option value="">Cualquier fecha</option>
                  <option value="today">Hoy</option>
                  <option value="tomorrow">Mañana</option>
                  <option value="this_week">Esta semana</option>
                  <option value="this_month">Este mes</option>
                  <option value="next_month">Próximo mes</option>
                </select>
              </div>
            </div>

            <!-- Price Filter -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-body">
                <h6 class="fw-bold mb-3">
                  <i class="ti ti-currency-dollar me-2 text-primary"></i>
                  Precio
                </h6>
                <div class="d-flex flex-column gap-2">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="priceType"
                      id="priceAll"
                      value=""
                      checked={priceType === ''}
                      onchange={() => { priceType = ''; applyFilters(); }}
                    />
                    <label class="form-check-label" for="priceAll">
                      Todos
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="priceType"
                      id="priceFree"
                      value="free"
                      checked={priceType === 'free'}
                      onchange={() => { priceType = 'free'; applyFilters(); }}
                    />
                    <label class="form-check-label" for="priceFree">
                      <i class="ti ti-gift text-success me-1"></i>
                      Gratis
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="priceType"
                      id="pricePaid"
                      value="paid"
                      checked={priceType === 'paid'}
                      onchange={() => { priceType = 'paid'; applyFilters(); }}
                    />
                    <label class="form-check-label" for="pricePaid">
                      <i class="ti ti-ticket text-primary me-1"></i>
                      De pago
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Event Type Filter -->
            <div class="card shadow-sm border-0 mb-3">
              <div class="card-body">
                <h6 class="fw-bold mb-3">
                  <i class="ti ti-world me-2 text-primary"></i>
                  Tipo de Evento
                </h6>
                <div class="d-flex flex-column gap-2">
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="eventType"
                      id="typeAll"
                      value=""
                      checked={eventType === ''}
                      onchange={() => { eventType = ''; applyFilters(); }}
                    />
                    <label class="form-check-label" for="typeAll">
                      Todos
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="eventType"
                      id="typeOnline"
                      value="online"
                      checked={eventType === 'online'}
                      onchange={() => { eventType = 'online'; applyFilters(); }}
                    />
                    <label class="form-check-label" for="typeOnline">
                      <i class="ti ti-world text-info me-1"></i>
                      Online
                    </label>
                  </div>
                  <div class="form-check">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="eventType"
                      id="typePresencial"
                      value="presencial"
                      checked={eventType === 'presencial'}
                      onchange={() => { eventType = 'presencial'; applyFilters(); }}
                    />
                    <label class="form-check-label" for="typePresencial">
                      <i class="ti ti-map-pin text-success me-1"></i>
                      Presencial
                    </label>
                  </div>
                </div>
              </div>
            </div>

            <!-- Clear Filters -->
            <button onclick={clearFilters} class="btn btn-outline-secondary w-100">
              <i class="ti ti-x me-2"></i>
              Limpiar Filtros
            </button>
          </div>
        </div>
      </div>

      <!-- Events Grid -->
      <div class="col-lg-9">
        <!-- Results Header -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
          <div>
            <h5 class="fw-bold mb-1">
              {#if hasEvents}
                {events.total} Eventos Encontrados
              {:else}
                No se encontraron eventos
              {/if}
            </h5>
            {#if filters.search}
              <p class="text-muted mb-0 small">
                Resultados para: <strong>"{filters.search}"</strong>
              </p>
            {/if}
          </div>

          <div class="d-flex align-items-center gap-2">
            <label for="sortSelect" class="text-muted small mb-0 text-nowrap">Ordenar por:</label>
            <select
              id="sortSelect"
              class="form-select form-select-sm"
              style="width: auto;"
              bind:value={selectedSort}
              onchange={applyFilters}
            >
              <option value="date_asc">Fecha (próximos primero)</option>
              <option value="date_desc">Fecha (lejanos primero)</option>
              <option value="popular">Más populares</option>
              <option value="price_asc">Precio (menor a mayor)</option>
              <option value="price_desc">Precio (mayor a menor)</option>
              <option value="name_asc">Nombre (A-Z)</option>
            </select>
          </div>
        </div>

        {#if hasEvents}
          <!-- Events Grid -->
          <div class="row g-4 mb-5">
            {#each events.data as event}
              <div class="col-md-6 col-xl-4">
                <EventCard {event} />
              </div>
            {/each}
          </div>

          <!-- Pagination -->
          <div class="d-flex justify-content-center">
            <Pagination links={events.links} />
          </div>
        {:else}
          <!-- Empty State -->
          <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
              <div class="mb-4">
                <i class="ti ti-calendar-off" style="font-size: 4rem; color: #ddd;"></i>
              </div>
              <h4 class="fw-bold mb-3">No se encontraron eventos</h4>
              <p class="text-muted mb-4">
                {#if filters.search || selectedCategory || priceType || eventType || dateRange}
                  Intenta ajustar los filtros o busca algo diferente
                {:else}
                  No hay eventos disponibles en este momento
                {/if}
              </p>
              <button onclick={clearFilters} class="btn btn-primary">
                <i class="ti ti-refresh me-2"></i>
                Limpiar Filtros
              </button>
            </div>
          </div>
        {/if}
      </div>
    </div>
  </div>
</PublicLayout>

<style>
  .search-hero {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
  }

  .filters-sidebar {
    animation: slideIn 0.3s ease;
  }

  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateY(-10px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .form-check-input:checked {
    background-color: var(--bs-primary);
    border-color: var(--bs-primary);
  }

  @media (max-width: 991px) {
    .position-sticky {
      position: relative !important;
    }
  }
</style>
