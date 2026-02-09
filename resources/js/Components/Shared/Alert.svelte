<script>
  let { type = 'info', message = '', dismissible = true, onDismiss = null } = $props();

  let visible = $state(true);

  function handleDismiss() {
    visible = false;
    if (onDismiss) onDismiss();
  }

  const iconMap = {
    success: 'ti-circle-check',
    danger: 'ti-alert-circle',
    warning: 'ti-alert-triangle',
    info: 'ti-info-circle'
  };
</script>

{#if visible}
  <div class="alert alert-{type} {dismissible ? 'alert-dismissible' : ''} fade show" role="alert">
    <div class="d-flex align-items-center">
      <i class="ti {iconMap[type]} fs-5 me-2"></i>
      <div class="flex-grow-1">
        {message}
        <slot />
      </div>
      {#if dismissible}
        <button type="button" class="btn-close" onclick={handleDismiss} aria-label="Close"></button>
      {/if}
    </div>
  </div>
{/if}
