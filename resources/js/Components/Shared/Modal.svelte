<script>
  let { show = $bindable(false), title = '', size = 'md', centered = false, onClose = null } = $props();

  function handleClose() {
    show = false;
    if (onClose) onClose();
  }

  function handleBackdropClick(event) {
    if (event.target === event.currentTarget) {
      handleClose();
    }
  }
</script>

{#if show}
  <div class="modal fade show d-block" tabindex="-1" role="dialog" onclick={handleBackdropClick}>
    <div class="modal-dialog modal-{size} {centered ? 'modal-dialog-centered' : ''}" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">{title}</h5>
          <button type="button" class="btn-close" onclick={handleClose} aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <slot />
        </div>
        <div class="modal-footer">
          <slot name="footer">
            <button type="button" class="btn btn-secondary" onclick={handleClose}>Cerrar</button>
          </slot>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-backdrop fade show"></div>
{/if}

<style>
  .modal.show {
    background-color: rgba(0, 0, 0, 0.5);
  }
</style>
