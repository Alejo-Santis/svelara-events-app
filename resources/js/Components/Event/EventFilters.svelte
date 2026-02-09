<script>
  import { router } from '@inertiajs/svelte';

  let { categories = [], filters = {} } = $props();

  let localFilters = $state({
    category_id: filters.category_id || '',
    is_free: filters.is_free || '',
    is_online: filters.is_online || '',
    date_from: filters.date_from || '',
    date_to: filters.date_to || '',
    search: filters.search || ''
  });

  function applyFilters() {
    router.get('/eventos', localFilters, {
      preserveState: true,
      preserveScroll: true
    });
  }

  function resetFilters() {
    localFilters = {
      category_id: '',
      is_free: '',
      is_online: '',
      date_from: '',
      date_to: '',
      search: ''
    };
    applyFilters();
  }
</script>

<div class="card">
  <div class="card-body">
    <h5 class="card-title mb-4">
      <i class="ti ti-filter me-2"></i>
      Filtros
    </h5>

    <div class="mb-3">
      <label for="search" class="form-label">Buscar</label>
      <input
        type="text"
        class="form-control"
        id="search"
        bind:value={localFilters.search}
        placeholder="Buscar eventos..."
      />
    </div>

    <div class="mb-3">
      <label for="category" class="form-label">Categoría</label>
      <select class="form-select" id="category" bind:value={localFilters.category_id}>
        <option value="">Todas las categorías</option>
        {#each categories as category}
          <option value={category.id}>{category.name}</option>
        {/each}
      </select>
    </div>

    <div class="mb-3">
      <label for="price" class="form-label">Precio</label>
      <select class="form-select" id="price" bind:value={localFilters.is_free}>
        <option value="">Todos</option>
        <option value="1">Gratis</option>
        <option value="0">De pago</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="location" class="form-label">Ubicación</label>
      <select class="form-select" id="location" bind:value={localFilters.is_online}>
        <option value="">Todos</option>
        <option value="1">En línea</option>
        <option value="0">Presencial</option>
      </select>
    </div>

    <div class="mb-3">
      <label for="date_from" class="form-label">Fecha desde</label>
      <input
        type="date"
        class="form-control"
        id="date_from"
        bind:value={localFilters.date_from}
      />
    </div>

    <div class="mb-3">
      <label for="date_to" class="form-label">Fecha hasta</label>
      <input
        type="date"
        class="form-control"
        id="date_to"
        bind:value={localFilters.date_to}
      />
    </div>

    <div class="d-grid gap-2">
      <button class="btn btn-primary" onclick={applyFilters}>
        <i class="ti ti-search me-2"></i>
        Aplicar Filtros
      </button>
      <button class="btn btn-outline-secondary" onclick={resetFilters}>
        <i class="ti ti-refresh me-2"></i>
        Limpiar
      </button>
    </div>
  </div>
</div>
