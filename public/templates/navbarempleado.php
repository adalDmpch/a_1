<?php
// templates/navbaradmin.php
?>

<style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');

        .font-heading {
            font-family: 'Poppins', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        .hover-zoom {
            transition: transform 0.3s ease;
        }

        .hover-zoom:hover {
            transform: scale(1.03);
        }

        .calendar-day {
            min-height: 100px;
            transition: all 0.2s;
        }

        .calendar-day:hover:not(.inactive) {
            background-color: #f0fdf4;
            border-color: #10b981;
        }

        .calendar-day.inactive {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .appointment {
            border-left: 3px solid #10b981;
        }
    </style>

<!-- Navbar -->
<nav class="bg-white border-b border-gray-200 fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4">
        
        <div class="flex justify-between items-center h-20">
            <!-- Logo -->
            <div class="flex items-center space-x-2 sm:space-x-3">
                <h1 class="font-heading text-3xl font-bold text-gray-900">
                    <span class="text-emerald-600">NOIR</span>
                    <span class="text-gray-800">ELITE</span>
                </h1>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="/a_1/public/empleado/inicio.php" class="text-gray-600 hover:text-emerald-600">Inicio</a>
                <a href="/a_1/public/empleado/agenda.php" class="text-gray-600 hover:text-emerald-600">Agendas</a>
                <a href="/a_1/public/empleado/perfil.php" class="text-emerald-600 font-medium">Perfil</a>
                <a href="../../actions/logout.php" class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all">
                    Cerrar Sesion
                </a>
            </div>

            <button id="menuButton" class="md:hidden text-gray-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menú móvil -->
    <div id="mobileMenu" class="md:hidden hidden bg-white border-b border-gray-200 py-4">
        <div class="max-w-7xl mx-auto px-4 space-y-3">
            <a href="/a_1/public/empleado/inicio.php" class="block text-gray-600 hover:text-emerald-600 py-2">Inicio</a>
            <a href="/a_1/public/empleado/agenda.php" class="block text-gray-600 hover:text-emerald-600 py-2">Agendas</a>
            <a href="/a_1/public/empleado/perfil.php" class="block text-emerald-600 font-medium py-2">Perfil</a>
            <a href="../../actions/logout.php" class="block bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all inline-block mt-2">
                Cerrar Sesion
            </a>
        </div>
    </div>
</nav>

<script>
    // Menú móvil
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');

    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
</script>