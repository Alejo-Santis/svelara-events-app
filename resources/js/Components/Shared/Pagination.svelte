<script>
  import { router } from '@inertiajs/svelte';

  let { links = [], preserveScroll = true } = $props();

  function navigate(url) {
    if (url) {
      router.get(url, {}, { preserveScroll });
    }
  }
</script>

{#if links && links.length > 3}
  <nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mb-0">
      {#each links as link}
        <li class="page-item {link.active ? 'active' : ''} {!link.url ? 'disabled' : ''}">
          {#if link.url}
            <button
              class="page-link"
              onclick={() => navigate(link.url)}
            >
              {@html link.label}
            </button>
          {:else}
            <span class="page-link">{@html link.label}</span>
          {/if}
        </li>
      {/each}
    </ul>
  </nav>
{/if}
