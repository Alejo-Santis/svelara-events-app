<script>
  import PublicLayout from '../../../Layouts/PublicLayout.svelte';
  import EventCard from '../../../Components/Event/EventCard.svelte';
  import EventFilters from '../../../Components/Event/EventFilters.svelte';
  import Pagination from '../../../Components/Shared/Pagination.svelte';
  import LoadingSpinner from '../../../Components/Shared/LoadingSpinner.svelte';

  let { events = { data: [], links: [] }, categories = [], filters = {} } = $props();

  let hasEvents = $derived(events.data && events.data.length > 0);
</script>

<PublicLayout>
  <div class="container py-5">
    <div class="row mb-4">
      <div class="col-12">
        <h1 class="fw-bold mb-2">Explorar Eventos</h1>
        <p class="text-muted">Encuentra el evento perfecto para ti</p>
      </div>
    </div>

    <div class="row">
      <!-- Sidebar Filters -->
      <div class="col-lg-3 mb-4">
        <EventFilters {categories} {filters} />
      </div>

      <!-- Events Grid -->
      <div class="col-lg-9">
        {#if hasEvents}
          <div class="row g-4">
            {#each events.data as event}
              <div class="col-md-6 col-lg-4">
                <EventCard {event} />
              </div>
            {/each}
          </div>

          <!-- Pagination -->
          <div class="row mt-5">
            <div class="col-12">
              <Pagination links={events.links} />
            </div>
          </div>
        {:else}
          <div class="card">
            <div class="card-body text-center py-5">
              <i class="ti ti-calendar-off fs-1 text-muted mb-3 d-block"></i>
              <h5 class="fw-semibold mb-2">No se encontraron eventos</h5>
              <p class="text-muted mb-4">
                Intenta ajustar los filtros o busca algo diferente
              </p>
              <a href="/eventos" class="btn btn-primary">
                <i class="ti ti-refresh me-2"></i>
                Ver todos los eventos
              </a>
            </div>
          </div>
        {/if}
      </div>
    </div>
  </div>
</PublicLayout>
