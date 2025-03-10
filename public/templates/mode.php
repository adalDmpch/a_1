<!-- Dark Mode Implementation for Bella Hair -->
<div id="theme-toggle-container" class="fixed bottom-6 right-6 z-50">
  <button id="theme-toggle" class="bg-gradient-to-r from-green-600 to-green-800 hover:from-green-700 hover:to-green-900 text-white rounded-full p-3 shadow-lg transition-all duration-300 transform hover:scale-110 focus:outline-none">
    <svg id="sun-icon" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
    <svg id="moon-icon" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
    </svg>
  </button>
</div>

<!-- Contenedor para el efecto de transición -->
<div id="theme-transition-overlay" class="fixed inset-0 z-[100] pointer-events-none opacity-0"></div>

<!-- Estilos para el modo oscuro refinados para Bella Hair -->
<style id="dark-mode-styles">
  /* Variables para tema oscuro */
  :root {
    --light-main-bg: #ffffff;
    --light-card-bg: #ffffff;
    --light-text-primary: #000000;
    --light-text-secondary: #4b5563;
    --light-border-color: #e5e7eb;
  }
  
  .dark-mode {
    --main-bg: #0f172a;
    --card-bg: #1e293b;
    --text-primary: #f8fafc;
    --text-secondary: #cbd5e1;
    --border-color: #334155;
    --theme-primary: #059669;
    --theme-secondary: #10b981;
    --highlight-bg: #0f2a22;
    --accent-light: rgba(16, 185, 129, 0.15);
  }
  
  /* Establecer variables CSS para modo claro por defecto */
  :root:not(.dark-mode) {
    --main-bg: var(--light-main-bg);
    --card-bg: var(--light-card-bg);
    --text-primary: var(--light-text-primary);
    --text-secondary: var(--light-text-secondary);
    --border-color: var(--light-border-color);
  }
  
  /* Prevenir el parpadeo */
  html.transitioning * {
    transition: background-color 1.2s ease, color 1.2s ease, border-color 1.2s ease !important;
  }
  
  /* Estilos generales */
  body {
    background-color: var(--main-bg);
    color: var(--text-primary);
  }
  
  /* Colores de fondo */
  .dark-mode .bg-gray-50,
  .dark-mode .bg-gray-100 {
    background-color: var(--main-bg);
  }
  
  .dark-mode .bg-white {
    background-color: var(--card-bg);
  }
  
  /* Textos */
  .dark-mode h1, 
  .dark-mode h2, 
  .dark-mode h3, 
  .dark-mode h4,
  .dark-mode .font-bold, 
  .dark-mode .font-semibold {
    color: var(--text-primary);
  }
  
  .dark-mode .text-gray-500, 
  .dark-mode .text-gray-600,
  .dark-mode .text-gray-700, 
  .dark-mode .text-gray-800 {
    color: var(--text-secondary);
  }
  
  /* Bordes */
  .dark-mode .border,
  .dark-mode .border-gray-200, 
  .dark-mode .border-gray-300,
  .dark-mode .border-t,
  .dark-mode .border-b {
    border-color: var(--border-color);
  }
  
  /* Elementos de formulario */
  .dark-mode input, 
  .dark-mode select, 
  .dark-mode textarea {
    background-color: #1e293b;
    border-color: var(--border-color);
    color: var(--text-primary);
  }
  
  .dark-mode input::placeholder {
    color: #64748b;
  }
  
  /* Elementos específicos de Bella Hair */
  .dark-mode .border-l-4, 
  .dark-mode .border-t-4 {
    border-color: var(--theme-primary);
  }
  
  /* Colores verde adaptados para modo oscuro */
  .dark-mode .bg-green-100,
  .dark-mode .bg-green-100\/90 {
    background-color: rgba(5, 150, 105, 0.15);
  }
  
  .dark-mode .border-green-200 {
    border-color: rgba(5, 150, 105, 0.3);
  }
  
  .dark-mode .text-green-700,
  .dark-mode .text-green-800 {
    color: #34d399;
  }
  
  .dark-mode .hover\:bg-green-50:hover {
    background-color: var(--highlight-bg);
  }

  /* Tablas */
  .dark-mode table {
    border-color: var(--border-color);
  }
  
  .dark-mode table th {
    background-color: var(--theme-primary);
    color: white;
  }
  
  .dark-mode table tr:nth-child(even) {
    background-color: rgba(30, 41, 59, 0.7);
  }
  
  .dark-mode table td {
    border-color: var(--border-color);
  }
  
  /* SweetAlert2 ajustes */
  .dark-mode .swal2-popup {
    background-color: var(--card-bg);
    color: var(--text-primary);
  }
  
  .dark-mode .swal2-title,
  .dark-mode .swal2-content {
    color: var(--text-primary);
  }
  
  /* Estilos para la transición elegante */
  #theme-transition-overlay {
    transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: opacity;
    backdrop-filter: blur(0px);
  }
  
  #theme-transition-overlay.active {
    opacity: 0.8;
    backdrop-filter: blur(4px);
  }
  
  #theme-transition-overlay.to-dark {
    background: radial-gradient(circle at var(--x) var(--y), rgba(16, 185, 129, 0.5), rgba(15, 23, 42, 0.95));
  }
  
  #theme-transition-overlay.to-light {
    background: radial-gradient(circle at var(--x) var(--y), rgba(16, 185, 129, 0.5), rgba(248, 250, 252, 0.95));
  }

  /* Animación del botón */
  @keyframes pulse {
    0% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.8);
      transform: scale(1);
    }
    50% {
      box-shadow: 0 0 0 12px rgba(16, 185, 129, 0);
      transform: scale(1.05);
    }
    100% {
      box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
      transform: scale(1);
    }
  }
  
  .button-pulse {
    animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1);
  }
</style>

<!-- Script en el encabezado para prevenir el parpadeo -->
<script>
  // Verificar y aplicar el modo oscuro antes de que se cargue la página
  (function() {
    // Obtener preferencia guardada (por defecto oscuro si no hay preferencia)
    const darkModePreference = localStorage.getItem('darkMode') !== 'false';
    
    // Aplicar clase de inmediato
    if (darkModePreference) {
      document.documentElement.classList.add('dark-mode');
    }
  })();
</script>

<!-- Script para el modo oscuro con transición de desvanecimiento elegante -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const html = document.documentElement;
  const themeToggle = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('sun-icon');
  const moonIcon = document.getElementById('moon-icon');
  const transitionOverlay = document.getElementById('theme-transition-overlay');
  
  // Verificar preferencia guardada y activar modo oscuro por defecto
  const darkModePreference = localStorage.getItem('darkMode') !== 'false'; // Por defecto oscuro
  
  // Actualizar la interfaz según el estado actual (los estilos ya se aplicaron en el script inicial)
  if (darkModePreference) {
    moonIcon.classList.add('hidden');
    sunIcon.classList.remove('hidden');
  } else {
    sunIcon.classList.add('hidden');
    moonIcon.classList.remove('hidden');
  }
  
  // Alternar modo oscuro al hacer clic en el botón
  themeToggle.addEventListener('click', function() {
    const isDarkMode = html.classList.contains('dark-mode');
    
    // Animación de pulso en el botón
    themeToggle.classList.add('button-pulse');
    
    if (isDarkMode) {
      disableDarkMode(true);
    } else {
      enableDarkMode(true);
    }
    
    // Quitar clase de animación después de que termine
    setTimeout(() => {
      themeToggle.classList.remove('button-pulse');
    }, 1000);
  });
  
  // Función para habilitar el modo oscuro
  function enableDarkMode(animate) {
    // Cambiar iconos
    moonIcon.classList.add('hidden');
    sunIcon.classList.remove('hidden');
    
    if (animate) {
      // Ejecutar transición de desvanecimiento
      playFadeTransition(true);
    } else {
      // Aplicar directamente sin animación
      html.classList.add('dark-mode');
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'true');
  }
  
  // Función para deshabilitar el modo oscuro
  function disableDarkMode(animate) {
    // Cambiar iconos
    sunIcon.classList.add('hidden');
    moonIcon.classList.remove('hidden');
    
    if (animate) {
      // Ejecutar transición de desvanecimiento
      playFadeTransition(false);
    } else {
      // Aplicar directamente sin animación
      html.classList.remove('dark-mode');
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'false');
  }
  
  // Transición elegante de desvanecimiento para el cambio de modo
  function playFadeTransition(toDark) {
    // Agregar clase transitioning para prevenir parpadeos
    html.classList.add('transitioning');
    
    // Posición del botón para el gradiente radial
    const rect = themeToggle.getBoundingClientRect();
    const x = rect.left + rect.width / 2;
    const y = rect.top + rect.height / 2;
    
    // Establecer posición del origen del gradiente
    transitionOverlay.style.setProperty('--x', x + 'px');
    transitionOverlay.style.setProperty('--y', y + 'px');
    
    // Establecer la clase de dirección correcta
    transitionOverlay.className = 'fixed inset-0 z-[100] pointer-events-none opacity-0';
    transitionOverlay.classList.add(toDark ? 'to-dark' : 'to-light');
    
    // Iniciar desvanecimiento
    setTimeout(() => {
      transitionOverlay.classList.add('active');
      
      // Cambiar el tema después de un breve retraso
      setTimeout(() => {
        if (toDark) {
          html.classList.add('dark-mode');
        } else {
          html.classList.remove('dark-mode');
        }
        
        // Desaparecer el overlay gradualmente
        setTimeout(() => {
          transitionOverlay.classList.remove('active');
          
          // Remover clases después de completar
          setTimeout(() => {
            transitionOverlay.classList.remove(toDark ? 'to-dark' : 'to-light');
            html.classList.remove('transitioning');
          }, 800);
        }, 400);
      }, 400);
    }, 50);
  }
});
</script>