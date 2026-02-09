<script>
  import { router, useForm } from '@inertiajs/svelte';
  import Alert from '../../Components/Shared/Alert.svelte';

  let { errors = {}, status = null } = $props();

  const form = useForm({
    email: '',
    password: '',
    remember: false
  });

  function handleSubmit(e) {
    e.preventDefault();
    $form.post('/login', {
      onFinish: () => {
        $form.reset('password');
      }
    });
  }
</script>

<div class="page-wrapper" id="main-wrapper">
  <div class="position-relative overflow-hidden text-bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="d-flex align-items-center justify-content-center w-100">
      <div class="row justify-content-center w-100">
        <div class="col-md-8 col-lg-6 col-xxl-4">
          <div class="card mb-0">
            <div class="card-body">
              <a href="/" class="text-nowrap logo-img text-center d-block py-3 w-100">
                <img src="/assets/images/logos/logo.svg" alt="Logo" style="max-width: 180px;">
              </a>
              <p class="text-center fs-4 mb-4">Gestiona tus eventos de manera profesional</p>

              {#if status}
                <Alert type="success" message={status} />
              {/if}

              {#if errors.email}
                <Alert type="danger" message={errors.email} />
              {/if}

              <form onsubmit={handleSubmit}>
                <div class="mb-3">
                  <label for="email" class="form-label">Correo Electrónico</label>
                  <input
                    type="email"
                    class="form-control {errors.email ? 'is-invalid' : ''}"
                    id="email"
                    bind:value={$form.email}
                    required
                    autofocus
                  />
                  {#if errors.email}
                    <div class="invalid-feedback">{errors.email}</div>
                  {/if}
                </div>

                <div class="mb-4">
                  <label for="password" class="form-label">Contraseña</label>
                  <input
                    type="password"
                    class="form-control {errors.password ? 'is-invalid' : ''}"
                    id="password"
                    bind:value={$form.password}
                    required
                  />
                  {#if errors.password}
                    <div class="invalid-feedback">{errors.password}</div>
                  {/if}
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                  <div class="form-check">
                    <input
                      class="form-check-input primary"
                      type="checkbox"
                      id="remember"
                      bind:checked={$form.remember}
                    />
                    <label class="form-check-label text-dark" for="remember">
                      Recordarme
                    </label>
                  </div>
                  <a class="text-primary fw-bold" href="/forgot-password">¿Olvidaste tu contraseña?</a>
                </div>

                <button
                  type="submit"
                  class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2"
                  disabled={$form.processing}
                >
                  {#if $form.processing}
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Iniciando sesión...
                  {:else}
                    Iniciar Sesión
                  {/if}
                </button>

                <div class="d-flex align-items-center justify-content-center">
                  <p class="fs-4 mb-0 fw-bold">¿Nuevo en la plataforma?</p>
                  <a class="text-primary fw-bold ms-2" href="/register">Crear una cuenta</a>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .min-vh-100 {
    min-height: 100vh !important;
  }
</style>
