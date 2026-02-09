<script>
  import { useForm } from '@inertiajs/svelte';
  import Alert from '../../Components/Shared/Alert.svelte';

  let { errors = {} } = $props();

  const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: ''
  });

  function handleSubmit(e) {
    e.preventDefault();
    $form.post('/register');
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
              <p class="text-center fs-4 mb-4">Crea tu cuenta y comienza a gestionar eventos</p>

              <form onsubmit={handleSubmit}>
                <div class="mb-3">
                  <label for="name" class="form-label">Nombre Completo</label>
                  <input
                    type="text"
                    class="form-control {errors.name ? 'is-invalid' : ''}"
                    id="name"
                    bind:value={$form.name}
                    required
                    autofocus
                  />
                  {#if errors.name}
                    <div class="invalid-feedback">{errors.name}</div>
                  {/if}
                </div>

                <div class="mb-3">
                  <label for="email" class="form-label">Correo Electrónico</label>
                  <input
                    type="email"
                    class="form-control {errors.email ? 'is-invalid' : ''}"
                    id="email"
                    bind:value={$form.email}
                    required
                  />
                  {#if errors.email}
                    <div class="invalid-feedback">{errors.email}</div>
                  {/if}
                </div>

                <div class="mb-3">
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

                <div class="mb-4">
                  <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                  <input
                    type="password"
                    class="form-control"
                    id="password_confirmation"
                    bind:value={$form.password_confirmation}
                    required
                  />
                </div>

                <button
                  type="submit"
                  class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2"
                  disabled={$form.processing}
                >
                  {#if $form.processing}
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Creando cuenta...
                  {:else}
                    Registrarse
                  {/if}
                </button>

                <div class="d-flex align-items-center justify-content-center">
                  <p class="fs-4 mb-0 fw-bold">¿Ya tienes cuenta?</p>
                  <a class="text-primary fw-bold ms-2" href="/login">Iniciar sesión</a>
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
