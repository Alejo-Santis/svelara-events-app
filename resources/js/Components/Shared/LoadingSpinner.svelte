<script>
  let { size = 'md', text = 'Cargando...', fullScreen = false } = $props();

  const sizeClasses = {
    sm: 'spinner-border-sm',
    md: '',
    lg: 'spinner-border-lg'
  };
</script>

{#if fullScreen}
  <div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="text-center">
      <div class="spinner-border text-primary {sizeClasses[size]}" role="status">
        <span class="visually-hidden">{text}</span>
      </div>
      {#if text}
        <p class="mt-3 text-muted">{text}</p>
      {/if}
    </div>
  </div>
{:else}
  <div class="spinner-border text-primary {sizeClasses[size]}" role="status">
    <span class="visually-hidden">{text}</span>
  </div>
{/if}
