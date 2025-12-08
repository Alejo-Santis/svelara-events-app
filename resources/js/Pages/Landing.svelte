<script>
  import { onMount } from 'svelte';

  let isScrolled = $state(false);

  onMount(() => {
    const handleScroll = () => {
      isScrolled = window.scrollY > 50;
    };

    window.addEventListener('scroll', handleScroll);

    return () => {
      window.removeEventListener('scroll', handleScroll);
    };
  });

  const features = [
    {
      icon: '📅',
      title: 'Gestión de Eventos',
      description: 'Crea y administra eventos de manera sencilla con nuestro sistema intuitivo.'
    },
    {
      icon: '👥',
      title: 'Control de Asistentes',
      description: 'Registra y gestiona a los participantes de tus eventos en tiempo real.'
    },
    {
      icon: '📊',
      title: 'Reportes Detallados',
      description: 'Obtén estadísticas y análisis completos de tus eventos.'
    },
    {
      icon: '🎟️',
      title: 'Venta de Entradas',
      description: 'Sistema integrado para la venta y distribución de entradas.'
    },
    {
      icon: '📱',
      title: 'Acceso Móvil',
      description: 'Gestiona tus eventos desde cualquier dispositivo, en cualquier momento.'
    },
    {
      icon: '🔔',
      title: 'Notificaciones',
      description: 'Mantén informados a los asistentes con notificaciones automáticas.'
    }
  ];
</script>

<!-- Header -->
<header class={`fixed w-full top-0 z-50 transition-all duration-300 ${isScrolled ? 'bg-white shadow-md' : 'bg-transparent'}`}>
  <nav class="container mx-auto px-6 py-4">
    <div class="flex items-center justify-between">
      <div class="flex items-center space-x-2">
        <span class="text-2xl">🎪</span>
        <span class={`text-xl font-bold transition-colors ${isScrolled ? 'text-gray-900' : 'text-white'}`}>
          EventManager
        </span>
      </div>

      <div class="hidden md:flex items-center space-x-8">
        <a href="#features" class={`hover:text-blue-600 transition-colors ${isScrolled ? 'text-gray-700' : 'text-white'}`}>
          Características
        </a>
        <a href="#about" class={`hover:text-blue-600 transition-colors ${isScrolled ? 'text-gray-700' : 'text-white'}`}>
          Acerca de
        </a>
        <a href="#contact" class={`hover:text-blue-600 transition-colors ${isScrolled ? 'text-gray-700' : 'text-white'}`}>
          Contacto
        </a>
      </div>

      <div class="flex items-center space-x-4">
        <button class={`px-4 py-2 rounded-lg transition-colors ${isScrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'}`}>
          Iniciar Sesión
        </button>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
          Registrarse
        </button>
      </div>
    </div>
  </nav>
</header>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 overflow-hidden">
  <div class="absolute inset-0 bg-black/20"></div>

  <div class="container mx-auto px-6 relative z-10 text-center text-white">
    <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in">
      Gestiona tus Eventos
      <br />
      de Manera Profesional
    </h1>

    <p class="text-xl md:text-2xl mb-12 max-w-3xl mx-auto text-white/90">
      La plataforma completa para organizar, gestionar y hacer seguimiento de tus eventos con facilidad.
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
      <button class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all hover:scale-105 shadow-xl">
        Comenzar Gratis
      </button>
      <button class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold text-lg hover:bg-white/10 transition-all">
        Ver Demo
      </button>
    </div>
  </div>

  <!-- Decorative elements -->
  <div class="absolute bottom-0 left-0 right-0">
    <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
    </svg>
  </div>
</section>

<!-- Features Section -->
<section id="features" class="py-20 bg-white">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16">
      <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
        Todo lo que necesitas
      </h2>
      <p class="text-xl text-gray-600 max-w-2xl mx-auto">
        Herramientas poderosas y fáciles de usar para llevar tus eventos al siguiente nivel
      </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {#each features as feature}
        <div class="p-8 rounded-xl bg-gray-50 hover:bg-gray-100 transition-all hover:shadow-lg group">
          <div class="text-5xl mb-4 transform group-hover:scale-110 transition-transform">
            {feature.icon}
          </div>
          <h3 class="text-2xl font-bold text-gray-900 mb-3">
            {feature.title}
          </h3>
          <p class="text-gray-600 leading-relaxed">
            {feature.description}
          </p>
        </div>
      {/each}
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-600">
  <div class="container mx-auto px-6 text-center">
    <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
      ¿Listo para comenzar?
    </h2>
    <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
      Únete a miles de organizadores que ya confían en nuestra plataforma
    </p>
    <button class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold text-lg hover:bg-gray-100 transition-all hover:scale-105 shadow-xl">
      Crear Cuenta Gratuita
    </button>
  </div>
</section>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-12">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
      <div>
        <div class="flex items-center space-x-2 mb-4">
          <span class="text-2xl">🎪</span>
          <span class="text-xl font-bold">EventManager</span>
        </div>
        <p class="text-gray-400">
          La mejor plataforma para gestionar tus eventos de manera profesional.
        </p>
      </div>

      <div>
        <h4 class="font-bold mb-4">Producto</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-white transition-colors">Características</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Precios</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Casos de Uso</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-bold mb-4">Compañía</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-white transition-colors">Acerca de</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Blog</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Carreras</a></li>
        </ul>
      </div>

      <div>
        <h4 class="font-bold mb-4">Soporte</h4>
        <ul class="space-y-2 text-gray-400">
          <li><a href="#" class="hover:text-white transition-colors">Ayuda</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
          <li><a href="#" class="hover:text-white transition-colors">Documentación</a></li>
        </ul>
      </div>
    </div>

    <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center">
      <p class="text-gray-400 text-sm">
        © 2025 EventManager. Todos los derechos reservados.
      </p>
      <div class="flex space-x-6 mt-4 md:mt-0">
        <a href="#" class="text-gray-400 hover:text-white transition-colors">Privacidad</a>
        <a href="#" class="text-gray-400 hover:text-white transition-colors">Términos</a>
        <a href="#" class="text-gray-400 hover:text-white transition-colors">Cookies</a>
      </div>
    </div>
  </div>
</footer>

<style>
  @keyframes fade-in {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .animate-fade-in {
    animation: fade-in 1s ease-out;
  }
</style>
