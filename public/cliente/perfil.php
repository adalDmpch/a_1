<?php
require '../../config/confg.php';


session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../LoginAdmin.php");
    exit();
}


?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .dashboard-card {
            @apply bg-white border border-gray-200 rounded-xl shadow-lg;
        }
        
        .hover-transition {
            @apply transition-all duration-300 ease-in-out;
        }
        
        .dropdown-menu {
            display: none;
            animation: fadeIn 0.2s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    <title>Perfil Profesional - Noir Elite</title>
</head>
<body class="font-body bg-white text-gray-800 flex flex-col min-h-screen">
    <!-- Navbar Responsivo -->
    <nav class="bg-white border-b-2 border-emerald-500/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                        <span class="text-emerald-600">NOIR</span> 
                        <span class="text-gray-800">ELITE</span>
                    </h1>
                </div>

                <!-- Menú Mobile -->
                <div class="flex md:hidden">
                    <button onclick="toggleMobileMenu()" class="inline-flex items-center justify-center p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>

                <!-- Menú Desktop -->
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                    <!-- Selector de Locales -->
                    <div class="relative" id="branch-dropdown">
                        <button onclick="toggleDropdown('branch-dropdown')" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-emerald-500 pb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span class="text-sm sm:text-base">Sucursal Principal</span>
                        </button>
                        <div id="branch-dropdown-menu" class="absolute hidden bg-white shadow-2xl rounded-lg p-4 w-48 mt-2 border border-gray-200 right-0">
                            <div class="space-y-2">
                                <div class="p-3 hover:bg-gray-50 rounded-lg cursor-pointer border border-emerald-500">
                                    <h4 class="text-emerald-600 font-medium text-sm">Principal</h4>
                                    <p class="text-xs text-gray-500">Av. Reforma 123</p>
                                </div>
                                <div class="p-3 hover:bg-gray-50 rounded-lg cursor-pointer border border-gray-200">
                                    <h4 class="text-gray-900 font-medium text-sm">Zona Rosa</h4>
                                    <p class="text-xs text-gray-500">Calle Amberes 45</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Menú Cuenta -->
                    <div class="relative" id="account-dropdown">
                        <button onclick="toggleDropdown('account-dropdown')" class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <span class="text-sm sm:text-base">Mi Cuenta</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div id="account-dropdown-menu" class="absolute hidden bg-white shadow-2xl rounded-lg p-4 w-48 mt-2 border border-gray-200 right-0">
                            <a href="#preferencias" class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Preferencias</a>
                            <a href="#seguridad" class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Seguridad</a>
                            <a href="#facturacion" class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Facturación</a>
                            <div class="border-t my-2"></div>
                            <a href="#notificaciones" class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Notificaciones</a>
                            <a href="#soporte" class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Soporte</a>
                        </div>
                    </div>
                    <button onclick="openModal()" class="bg-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-green-800 text-sm sm:text-base">
                        Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>

        <!-- Menú Mobile -->
        <div class="hidden mobile-menu md:hidden">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <div class="relative">
                    <!-- Menu Mobile-->
                </div>
            </div>
        </div>
    </nav>

    <!-- Modal de Cierre de Sesión -->
    <div id="logout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl p-6 sm:max-w-md">
            <h2 class="text-lg font-semibold text-gray-900" id="modal-title">¿Estás seguro?</h2>
            <p class="mt-2 text-sm text-gray-500">Esta acción cerrará tu sesión en la plataforma.</p>
            
            <div class="mt-4 flex justify-end space-x-3">
                <a type="button"  href="../../actions/logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 text-sm sm:text-base">
                    Cerrar Sesión
                </a>
                <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-400 text-sm sm:text-base">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <!-- Contenido Principal -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Panel Lateral -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Perfil -->
                <div class="dashboard-card p-4 sm:p-6 text-center">
                    <div class="relative group mb-4 sm:mb-6">
                        <img src="/assets/images/Mapache.png" alt="Foto de perfil" class="w-24 h-24 sm:w-32 sm:h-32 rounded-full mx-auto border-4 border-gray-100 shadow-lg hover-transition hover:scale-105">
                    </div>
                    <h2 class="font-heading text-xl sm:text-2xl font-bold text-gray-900 mb-2">Jenner Alexander</h2>
                    <div class="mb-4">
                        <span class="bg-emerald-100 text-emerald-600 text-xs sm:text-sm px-3 py-1 rounded-full">Miembro Premium</span>
                    </div>
                    <div class="space-y-2 text-sm text-gray-400 mb-4 sm:mb-6">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-xs sm:text-sm">Miembro desde: Enero 2024</span>
                        </div>
                    </div>
                    <a href="../cliente/editar_perfil.php" class="w-full py-2 px-4 bg-green-200 hover:bg-green-600 hover:text-white rounded-lg hover-transition text-xs sm:text-sm font-medium">
                        Editar Perfil
                    </a>
                </div>
                    <!-- Navegación Rápida -->
                    <div class="dashboard-card p-4 sm:p-6">
                        <h3 class="font-heading text-lg font-bold text-gray-900 mb-4">Accesos Rápidos</h3>
                        <nav class="space-y-2">
                            <a href="/templates/History.html" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <span>Historial Completo</span>
                            </a>
                            <a href="#metodos-pago" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                </svg>
                                <span>Métodos de Pago</span>
                            </a>
                            <a href="#seguridad" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Seguridad</span>
                            </a>
                            <a href="#soporte" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <span>Soporte 24/7</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Contenido Principal -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Contenido Principal -->
    <div class="lg:col-span-3 space-y-6">
        <!-- Mensaje de Bienvenida -->
        <div class="welcome-message bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 rounded-2xl shadow-xl animate-slide-in">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">¡Prepárate para vivir una experiencia única!</h2>
                <p class="text-lg opacity-90 mb-6">Estamos emocionados de crear un look perfecto para ti. Necesitamos algunos datos importantes para personalizar tu experiencia al máximo.</p>
                <button onclick="startReservation()" class="px-8 py-3 bg-white text-emerald-600 rounded-full font-bold hover:bg-opacity-90 transform hover:scale-105 transition-all">
                    Comenzar Reserva 🚀
                </button>
            </div>
        </div>
                            <!-- Perfil Section -->
                    <div class="dashboard-card interactive-element bg-gray-900 text-white mb-6">
                        <div class="p-6 flex items-center justify-between">
                            <div>
                                <h2 class="font-heading text-2xl font-bold mb-1">Jenner Alexander</h2>
                                <div class="flex items-center space-x-3">
                                    <span class="bg-emerald-600/20 text-emerald-400 px-3 py-1 rounded-full text-sm">
                                        Activo
                                    </span>
                                    <span class="text-gray-400 text-sm">★ 4.9 (128 reseñas)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Citas Pendientes -->
                    <div class="dashboard-card">
                        <div class="p-4 sm:p-6 border-b border-gray-200">
                            <div>
                                <h1 class="font-heading text-3xl font-bold text-gray-900">
                                    <span class="text-emerald-600">NOIR</span> 
                                    <span class="text-gray-800">ELITE</span>
                                </h1>
                                <p class="text-gray-500 text-sm">Tu mejor aliado para resaltar tu belleza.</p>
                            </div>
                        <br>
                    </br>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                <h3 class="font-heading text-lg sm:text-xl font-bold text-gray-900 mb-2 sm:mb-0">Citas Programadas (2)</h3>
                                <a href="/templates/reservation.html" class="bg-blue-300 text-white px-4 py-2 text-sm sm:text-base rounded-lg hover:bg-blue-700 inline-block text-center">
                                    Nueva cita +
                                </a>
                                
                            </div>
                        <div class="p-4 sm:p-6 space-y-4">
                            <!-- Cita 1 -->
                            <div class="cita-item flex flex-col sm:flex-row items-center justify-between p-4 hover:bg-gray-50 rounded-lg border border-gray-200">
                                <div class="space-y-2 mb-3 sm:mb-0 sm:flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs sm:text-sm font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">Confirmada</span>
                                        <span class="text-xs sm:text-sm text-gray-500">20 Ene 2024 - 15:00</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900 text-sm sm:text-base">Corte Premium</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">Sucursal Principal - Ana Martínez</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <button class="px-3 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-500 hover:text-white rounded-lg">
                                        Cancelar
                                    </button>
                                    <button class="px-3 py-2 text-xs sm:text-sm bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg">
                                        Reagendar
                                    </button>
                                </div>
                            </div>
                            
                            <div class="cita-item flex flex-col sm:flex-row items-center justify-between p-4 hover:bg-gray-50 rounded-lg border border-gray-200">
                                <div class="space-y-2 mb-3 sm:mb-0 sm:flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-xs sm:text-sm font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">Confirmada</span>
                                        <span class="text-xs sm:text-sm text-gray-500">20 Ene 2024 - 15:00</span>
                                    </div>
                                    <h4 class="font-medium text-gray-900 text-sm sm:text-base">Corte Premium</h4>
                                    <p class="text-xs sm:text-sm text-gray-500">Sucursal Principal - Ana Martínez</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                    <button class="px-3 py-2 text-xs sm:text-sm text-red-600 hover:bg-red-500 hover:text-white rounded-lg">
                                        Cancelar
                                    </button>
                                    <button class="px-3 py-2 text-xs sm:text-sm bg-emerald-600 text-white hover:bg-emerald-700 rounded-lg">
                                        Reagendar
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación Responsiva -->
                        <div class="flex justify-center p-4 sm:p-6">
                            <nav class="flex flex-wrap justify-center gap-1 sm:gap-2">
                                <button onclick="changePage(-1)" 
                                        class="pagination-btn px-3 py-1.5 text-xs sm:text-sm rounded-md bg-white text-gray-500 hover:bg-gray-50 border border-gray-300">
                                    &laquo;
                                </button>
                                <button onclick="changePage(1)" 
                                        class="pagination-btn px-3 py-1.5 text-xs sm:text-sm bg-emerald-600 text-white border border-emerald-600">
                                    1
                                </button>
                                <button onclick="changePage(2)" 
                                        class="pagination-btn px-3 py-1.5 text-xs sm:text-sm bg-white text-gray-700 hover:bg-gray-50 border border-gray-300">
                                    2
                                </button>
                                <button onclick="changePage(1)" 
                                        class="pagination-btn px-3 py-1.5 text-xs sm:text-sm rounded-md bg-white text-gray-500 hover:bg-gray-50 border border-gray-300">
                                    &raquo;
                                </button>
                            </nav>
                        </div>
                    </div>
                    
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
                <div class="text-center text-gray-400 text-xs sm:text-sm">
                    <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                        <a href="#privacidad" class="hover:text-emerald-400">Política de Privacidad</a>
                        <a href="#terminos" class="hover:text-emerald-400">Términos de Servicio</a>
                    </div>
                    <p class="mt-3">© 2024 Noir Elite. Todos los derechos reservados.</p>
                </div>
            </div>
        </footer>

    <script>
        let currentPage = 1;
        const itemsPerPage = 2;

        function changePage(page) {
            const totalItems = document.querySelectorAll('.cita-item').length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            if (page === -1 && currentPage > 1) currentPage--;
            if (page === 1 && currentPage < totalPages) currentPage++;
            if (typeof page === 'number') currentPage = page;

            document.querySelectorAll('.cita-item').forEach((item, index) => {
                const start = (currentPage - 1) * itemsPerPage;
                const end = start + itemsPerPage;
                item.classList.toggle('hidden', !(index >= start && index < end));
            });

            document.querySelectorAll('.pagination-btn').forEach(btn => {
                btn.classList.remove('bg-emerald-600', 'text-white', 'border-emerald-600');
                btn.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
            });
            
            const activeBtn = document.querySelector(`.pagination-btn:nth-child(${currentPage + 1})`);
            if(activeBtn) {
                activeBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
                activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
            }
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', () => {
            changePage(1);
            
            // Responsividad de tabla
            function adjustTable() {
                const table = document.querySelector('table');
                if (window.innerWidth < 640) {
                    table.classList.add('min-w-[600px]');
                } else {
                    table.classList.remove('min-w-[600px]');
                }
            }
            
            window.addEventListener('resize', adjustTable);
            adjustTable();
            
            // Cerrar menús al hacer scroll
            window.addEventListener('scroll', () => {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
                document.querySelector('.mobile-menu').classList.add('hidden');
            });
        });

        // Modal de edición
        function openEditModal() {
            // Implementar lógica del modal aquí
            console.log('Abrir modal de edición');
        }


        // Gestión de dropdowns
        function toggleDropdown(dropdownId) {
            const menu = document.getElementById(`${dropdownId}-menu`);
            document.querySelectorAll('.dropdown-menu').forEach(other => {
                if(other !== menu) other.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        function openModal() {
            document.getElementById("logout-modal").classList.remove("hidden");
        }
        
        function closeModal() {
            document.getElementById("logout-modal").classList.add("hidden");
        }

    </script>
    </body>
    </html>