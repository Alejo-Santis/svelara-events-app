<script>
  import { router, usePage } from '@inertiajs/svelte';
  const page = usePage();
  const auth = $derived(page?.props?.auth || { user: null });
  let mobileMenuOpen = $state(false);
</script>

<div style="font-family: system-ui">
  <nav style="position: fixed; top: 0; left: 0; right: 0; background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); z-index: 1000; padding: 1rem 0;">
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
      <a href="/" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none; color: #2c3e50;">
        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white;">
          <i class="ti ti-calendar-event" style="font-size: 1.5rem;"></i>
        </div>
        <span style="font-size: 1.25rem; font-weight: 700; background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">EventHub</span>
      </a>
      
      <div style="display: flex; gap: 2rem; align-items: center;">
        <a href="/" style="color: #2c3e50; text-decoration: none; font-weight: 500;">Inicio</a>
        <a href="/eventos" style="color: #2c3e50; text-decoration: none; font-weight: 500;">Eventos</a>
        <a href="/categorias" style="color: #2c3e50; text-decoration: none; font-weight: 500;">Categorías</a>
        
        {#if auth?.user}
          <span style="color: #2c3e50; font-weight: 500;">{auth.user.name}</span>
          <button onclick={() => router.post('/logout')} style="padding: 0.5rem 1.25rem; background: #ff4757; color: white; border: none; border-radius: 50px; cursor: pointer; font-weight: 500;">
            Salir
          </button>
        {:else}
          <a href="/login" style="color: #2c3e50; text-decoration: none; font-weight: 500;">Iniciar Sesión</a>
          <a href="/register" style="padding: 0.5rem 1.25rem; background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%); color: white; border-radius: 50px; text-decoration: none; font-weight: 500;">Registrarse</a>
        {/if}
      </div>
    </div>
  </nav>

  <main style="margin-top: 80px; min-height: calc(100vh - 200px);">
    <slot />
  </main>

  <footer style="background: #1e293b; color: white; padding: 2rem 0; margin-top: 4rem;">
    <div class="container" style="text-align: center;">
      <p>&copy; {new Date().getFullYear()} EventHub. Todos los derechos reservados.</p>
    </div>
  </footer>
</div>
