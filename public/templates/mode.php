<!-- Dark Mode Implementation for Bella Hair -->
<div id="dark-mode-animation" class="fixed inset-0 bg-black z-[100] pointer-events-none opacity-0"></div>

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

<!-- Estilos para el modo oscuro refinados para Bella Hair -->
<style id="dark-mode-styles">
  /* Variables para tema oscuro */
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
  
  /* Estilos generales */
  .dark-mode body {
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
</style>

<!-- Script para el modo oscuro con animación suave -->
<script>
document.addEventListener('DOMContentLoaded', function() {
  const html = document.documentElement;
  const themeToggle = document.getElementById('theme-toggle');
  const sunIcon = document.getElementById('sun-icon');
  const moonIcon = document.getElementById('moon-icon');
  const darkModeAnimation = document.getElementById('dark-mode-animation');
  
  // Verificar preferencia guardada y activar modo oscuro por defecto
  const darkModePreference = localStorage.getItem('darkMode') !== 'false'; // Por defecto oscuro
  
  // Aplicar modo oscuro por defecto o según preferencia
  if (darkModePreference) {
    enableDarkMode(false);
  } else {
    disableDarkMode(false);
  }
  
  // Alternar modo oscuro al hacer clic en el botón
  themeToggle.addEventListener('click', function() {
    const isDarkMode = html.classList.contains('dark-mode');
    
    if (isDarkMode) {
      disableDarkMode(true);
    } else {
      enableDarkMode(true);
    }
  });
  
  // Función para habilitar el modo oscuro
  function enableDarkMode(animate) {
    // Cambiar iconos
    moonIcon.classList.add('hidden');
    sunIcon.classList.remove('hidden');
    
    if (animate) {
      // Ejecutar animación
      playToggleAnimation(true);
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
      // Ejecutar animación
      playToggleAnimation(false);
    } else {
      // Aplicar directamente sin animación
      html.classList.remove('dark-mode');
    }
    
    // Guardar preferencia
    localStorage.setItem('darkMode', 'false');
  }
  
  // Animación para el cambio de modo
  function playToggleAnimation(toDark) {
    // Reset de la animación
    darkModeAnimation.style.animation = 'none';
    darkModeAnimation.offsetHeight; // Trigger reflow
    
    if (toDark) {
      // Animación a modo oscuro
      darkModeAnimation.style.background = 'radial-gradient(circle at var(--x) var(--y), rgba(16, 185, 129, 0.9) 0%, rgba(15, 23, 42, 0.95) 50%)';
      
      // Posición del origen de la animación
      const rect = themeToggle.getBoundingClientRect();
      const x = rect.left + rect.width / 2;
      const y = rect.top + rect.height / 2;
      darkModeAnimation.style.setProperty('--x', x + 'px');
      darkModeAnimation.style.setProperty('--y', y + 'px');
      
      // Animar
      darkModeAnimation.style.transition = 'opacity 0s';
      darkModeAnimation.style.opacity = '1';
      
      setTimeout(() => {
        darkModeAnimation.style.transition = 'all 0.8s cubic-bezier(0.19, 1, 0.22, 1)';
        darkModeAnimation.style.transform = 'scale(5)';
        
        setTimeout(() => {
          html.classList.add('dark-mode');
          
          setTimeout(() => {
            darkModeAnimation.style.opacity = '0';
            darkModeAnimation.style.transform = 'scale(1)';
          }, 400);
        }, 200);
      }, 50);
    } else {
      // Animación a modo claro
      html.classList.remove('dark-mode');
      
      // Simple fadeout para modo claro
      darkModeAnimation.style.background = '#f8fafc';
      darkModeAnimation.style.opacity = '0.5';
      
      setTimeout(() => {
        darkModeAnimation.style.transition = 'opacity 0.8s ease';
        darkModeAnimation.style.opacity = '0';
      }, 50);
    }
  }
});
</script>