<script>
  import { useForm } from '@inertiajs/svelte';

  let { errors = {} } = $props();

  const form = useForm({
    email: '',
    password: '',
    remember: false
  });

  function handleSubmit(e) {
    e.preventDefault();
    $form.post('/login', {
      preserveScroll: true,
      onSuccess: () => $form.reset('password'),
    });
  }
</script>

<svelte:head>
  <title>Iniciar Sesión - Plataforma de Eventos</title>
</svelte:head>

<div class="auth-wrapper min-vh-100 d-flex align-items-center">
  <!-- Decorative shapes -->
  <div class="auth-shape-1"></div>
  <div class="auth-shape-2"></div>

  <div class="container position-relative">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <!-- Logo/Brand -->
        <div class="text-center mb-5">
          <a href="/" class="d-inline-block mb-3">
            <div class="brand-logo">
              <i class="ti ti-calendar-event fs-1 text-white"></i>
            </div>
          </a>
          <h2 class="fw-bold mb-2">¡Bienvenido de vuelta!</h2>
          <p class="text-muted">Inicia sesión para continuar</p>
        </div>

        <!-- Login Card -->
        <div class="card border-0 shadow-lg auth-card">
          <div class="card-body p-4 p-md-5">
            <form onsubmit={handleSubmit}>
              <!-- Email -->
              <div class="mb-4">
                <label for="email" class="form-label fw-semibold">
                  <i class="ti ti-mail me-2 text-primary"></i>
                  Correo Electrónico
                </label>
                <input
                  type="email"
                  id="email"
                  class="form-control form-control-lg"
                  class:is-invalid={errors.email}
                  placeholder="correo@ejemplo.com"
                  bind:value={$form.email}
                  required
                />
                {#if errors.email}
                  <div class="invalid-feedback">{errors.email}</div>
                {/if}
              </div>

              <!-- Password -->
              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <label for="password" class="form-label fw-semibold mb-0">
                    <i class="ti ti-lock me-2 text-primary"></i>
                    Contraseña
                  </label>
                  <a href="/forgot-password" class="text-decoration-none small text-primary">
                    ¿Olvidaste tu contraseña?
                  </a>
                </div>
                <input
                  type="password"
                  id="password"
                  class="form-control form-control-lg"
                  class:is-invalid={errors.password}
                  placeholder="••••••••"
                  bind:value={$form.password}
                  required
                />
                {#if errors.password}
                  <div class="invalid-feedback">{errors.password}</div>
                {/if}
              </div>

              <!-- Remember Me -->
              <div class="mb-4">
                <div class="form-check">
                  <input
                    type="checkbox"
                    id="remember"
                    class="form-check-input"
                    bind:checked={$form.remember}
                  />
                  <label for="remember" class="form-check-label">
                    Mantener sesión iniciada
                  </label>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                class="btn btn-primary btn-lg w-100 mb-3"
                disabled={$form.processing}
              >
                {#if $form.processing}
                  <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                  Iniciando sesión...
                {:else}
                  <i class="ti ti-login me-2"></i>
                  Iniciar Sesión
                {/if}
              </button>

              <!-- Divider -->
              <div class="text-center text-muted my-4">
                <div class="divider">
                  <span>o continúa con</span>
                </div>
              </div>

              <!-- Social Login -->
              <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-secondary">
                  <i class="ti ti-brand-google me-2"></i>
                  Google
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-4">
          <p class="text-muted">
            ¿No tienes una cuenta?
            <a href="/register" class="text-primary fw-semibold text-decoration-none hover-underline">
              Regístrate gratis
            </a>
          </p>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-3">
          <a href="/" class="text-muted text-decoration-none small hover-primary">
            <i class="ti ti-arrow-left me-1"></i>
            Volver al inicio
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .auth-wrapper {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    position: relative;
    overflow: hidden;
  }

  .auth-shape-1 {
    position: absolute;
    top: -100px;
    right: -100px;
    width: 500px;
    height: 500px;
    background: linear-gradient(135deg, #5D87FF15 0%, #49BEFF15 100%);
    border-radius: 50%;
    z-index: 0;
  }

  .auth-shape-2 {
    position: absolute;
    bottom: -150px;
    left: -150px;
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #13DEB915 0%, #FFAE1F15 100%);
    border-radius: 50%;
    z-index: 0;
  }

  .brand-logo {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%);
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 10px 30px rgba(93, 135, 255, 0.3);
    transition: transform 0.3s ease;
  }

  .brand-logo:hover {
    transform: translateY(-5px) scale(1.05);
  }

  .auth-card {
    border-radius: 20px;
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.98);
    animation: fadeInUp 0.6s ease-out;
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .form-control:focus {
    border-color: var(--bs-primary);
    box-shadow: 0 0 0 0.25rem rgba(93, 135, 255, 0.15);
  }

  .form-control-lg {
    padding: 0.75rem 1rem;
    border-radius: 10px;
  }

  .btn-lg {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
  }

  .btn-primary {
    background: linear-gradient(135deg, #5D87FF 0%, #49BEFF 100%);
    border: none;
    transition: all 0.3s ease;
  }

  .btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(93, 135, 255, 0.3);
  }

  .btn-outline-secondary {
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .btn-outline-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
  }

  .divider {
    display: flex;
    align-items: center;
    text-align: center;
  }

  .divider::before,
  .divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #dee2e6;
  }

  .divider span {
    padding: 0 1rem;
    font-size: 0.875rem;
  }

  .hover-underline {
    position: relative;
  }

  .hover-underline::after {
    content: '';
    position: absolute;
    width: 0;
    height: 2px;
    bottom: -2px;
    left: 0;
    background-color: var(--bs-primary);
    transition: width 0.3s ease;
  }

  .hover-underline:hover::after {
    width: 100%;
  }

  .hover-primary {
    transition: color 0.3s ease;
  }

  .hover-primary:hover {
    color: var(--bs-primary) !important;
  }

  @media (max-width: 767px) {
    .auth-shape-1,
    .auth-shape-2 {
      display: none;
    }

    .card-body {
      padding: 2rem 1.5rem !important;
    }

    .brand-logo {
      width: 70px;
      height: 70px;
    }
  }
</style>
