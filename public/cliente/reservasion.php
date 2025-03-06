<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
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
        
        .float-wa{
            position:fixed;
            width:60px;
            height:60px;
            bottom:40px;
            right:40px;
            background-color:#25d366;
            color:#FFF;
            border-radius:50px;
            text-align:center;
            font-size:30px;
            z-index:100;
        }
        
        /* Estilos personalizados */
        .step-indicator {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .step-indicator.active {
            background: #059669;
            color: white;
        }
        
        .product-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;  
        }
        
        .add-to-cart-btn {
            background: #059669;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            transition: background 0.3s ease;
        }
        
        .add-to-cart-btn:hover {
            background: #047857;
        }
        
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .product-card {
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            overflow: hidden;
            background-color: white;
            transition: transform 0.2s;
            max-width: 300px;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        
        .image-container {
            height: 200px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9fafb;
        }
        
        .product-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
            max-height: 200px;
        }
        
        .add-to-cart-btn {
            background-color: #10b981;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
            transition: background-color 0.2s;
        }
        
        .add-to-cart-btn:hover {
            background-color: #059669;
        }
    </style>
    <title>Reserva - Noir Elite</title>
</head>
<body class="font-body bg-white text-gray-800">
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
                    <button onclick="openModal()" class="bg-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-emerald-500 text-sm sm:text-base">
                        Cerrar Sesión
                    </button>
                </div>
                <div id="logout-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-800 bg-opacity-50">
                    <div class="bg-white rounded-lg shadow-xl p-6 sm:max-w-md">
                        <h2 class="text-lg font-semibold text-gray-900" id="modal-title">¿Estás seguro?</h2>
                        <p class="mt-2 text-sm text-gray-500">Esta acción cerrará tu sesión en la plataforma.</p>
                        
                        <div class="mt-4 flex justify-end space-x-3">
                            <button type="button" onclick="logout()" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-500 text-sm sm:text-base">
                                Cerrar Sesión
                            </button>
                            <button type="button" onclick="closeModal()" class="bg-gray-300 text-gray-900 px-4 py-2 rounded-lg hover:bg-gray-400 text-sm sm:text-base">
                                Cancelar
                            </button>
                        </div>
                    </div>
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

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
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
                    <button onclick="openEditModal()" class="w-full py-2 px-4 bg-green-200 hover:bg-green-600 hover:text-white rounded-lg hover-transition text-xs sm:text-sm font-medium">
                        Editar Perfil
                    </button>
                </div>
                

                <!-- Navegación Rápida -->
                <div class="dashboard-card p-4 sm:p-6">
                    <h3 class="font-heading text-lg font-bold text-gray-900 mb-4">Accesos Rápidos</h3>
                    <nav class="space-y-2">
                        <a href="#historial" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
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
                        <br>
                        <br>
                        <a href="/templates/perfil.html" class="flex items-center space-x-3 p-3 text-white bg-red-600 hover:bg-red-700 rounded-lg transition border border-red-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                            </svg>
                            <span>Inicio</span>
                        </a>
                        
                        
                    </nav>
                </div>
            </div>
<!-- Contenido Principal -->
<div class="lg:col-span-3 space-y-6">
    <div class="lg:col-span-3 space-y-6">
        <!-- Mensaje de Bienvenida Animado -->
        <div class="welcome-message bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 rounded-2xl shadow-xl mb-8">
            <button onclick="dismissWelcome()" class="float-right text-white/80 hover:text-white transition-colors">
                ✕
            </button>
            <div class="max-w-3xl mx-auto text-center">
                <div class="mb-6 ">
                    <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold mb-4">¡Estamos emocionados de tenerte aquí!</h2>
                <p class="text-lg opacity-90 mb-6">Es hora de Comnezar !!!  </p>
            </div>
        </div>
    
        <!-- Pasos (inicialmente ocultos) -->
        <div id="reservation-steps" class="hidden">
            <!-- Indicador de Pasos Mejorado -->
            <div class="flex justify-center mb-8 animate-slide-down">
                <div class="flex items-center space-x-4 bg-white p-2 rounded-full shadow-lg">
                    <div class="step-indicator active" data-step="1">
                        <span>1</span>
                        <div class="step-tooltip">Información Básica</div>
                    </div>
                    <div class="h-1 w-8 bg-gray-200 rounded-full"></div>
                    <div class="step-indicator" data-step="2">
                        <span>2</span>
                        <div class="step-tooltip">Salud y Preferencias</div>
                    </div>
                    <div class="h-1 w-8 bg-gray-200 rounded-full"></div>
                    <div class="step-indicator" data-step="3">
                        <span>3</span>
                        <div class="step-tooltip">Productos Premium</div>
                    </div>
                </div>
            </div>
    
            <!-- Resto de los pasos... -->
        </div>
    </div>
    <!-- Paso 1 - Información Básica -->
    <div id="step-1" class="step-content active">
        <div class="dashboard-card p-6">
            <!-- Contenido del formulario original -->
            <h2 class="text-3xl font-light text-gray-800 mb-8">Reserva tu cita</h2>
                                    <!-- Aviso de Pago -->
                                    <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <p class="text-sm text-blue-700">El pago se realizará directamente en el establecimiento. La selección del método de pago es solo para agilizar el proceso durante tu visita.</p>
                                        </div>
                                    </div>
            <form class="space-y-8">
                <!-- Información Personal -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Información Personal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Nombre Completo
                            </label>
                            <input type="text" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" placeholder="Ej: Juan Pérez">
                        </div>
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Teléfono
                            </label>
                            <input type="tel" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none" placeholder="Ej: (555) 123-4567">
                        </div>
                    </div>
                </div>
    
                <!-- Servicio -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Servicio
                    </label>
                    <select class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option value="">Selecciona un servicio</option>
                        <option>Corte dama - $25</option>
                        <option>Corte caballero - $20</option>
                        <option>Peinado especial - $35</option>
                        <option>Tinte completo - $45</option>
                        <option>Balayage - $80</option>
                        <option>Botox capilar - $75</option>
                    </select>
                </div>
    
                <!-- Fecha y Hora -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Fecha
                        </label>
                        <input type="date" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                    </div>
    
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Hora
                        </label>
                        <select class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                            <option value="">Selecciona una hora</option>
                            <option>09:00</option>
                            <option>10:00</option>
                            <option>11:00</option>
                            <option>12:00</option>
                            <option>13:00</option>
                            <option>14:00</option>
                            <option>15:00</option>
                            <option>16:00</option>
                            <option>17:00</option>
                            <option>18:00</option>
                            <option>19:00</option>
                        </select>
                    </div>
                </div>
    
                <!-- Estilista -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Estilista (opcional)
                    </label>
                    <select class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
                        <option>Sin preferencia</option>
                        <option>María García</option>
                        <option>José Ramírez</option>
                        <option>Ana Martínez</option>
                    </select>
                </div>
    
                <!-- Método de Pago -->
                <div class="space-y-6">
                    <h3 class="text-lg font-medium text-gray-900">Método de Pago Preferido</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                        <label class="relative flex flex-col bg-white p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="radio" name="payment" class="absolute top-3 right-3" value="efectivo">
                            <svg class="w-6 h-6 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span class="text-sm font-medium">Efectivo</span>
                        </label>
                        
                        <label class="relative flex flex-col bg-white p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="radio" name="payment" class="absolute top-3 right-3" value="tarjeta">
                            <svg class="w-6 h-6 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span class="text-sm font-medium">Tarjeta</span>
                        </label>
    
                        <label class="relative flex flex-col bg-white p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="radio" name="payment" class="absolute top-3 right-3" value="transferencia">
                            <svg class="w-6 h-6 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <span class="text-sm font-medium">Transferencia</span>
                        </label>
    
                        <label class="relative flex flex-col bg-white p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="radio" name="payment" class="absolute top-3 right-3" value="otro">
                            <svg class="w-6 h-6 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-sm font-medium">Otro</span>
                        </label>
                    </div>
                </div>
    
                <!-- Notas -->
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                        Notas Adicionales
                    </label>
                    <textarea 
                        class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg h-32 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none resize-none"
                        placeholder="¿Alguna preferencia o solicitud especial?"></textarea>
                </div>
                
            </form>
            <div class="flex justify-between mt-6">
                <button class="next-step px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 hover:scale-105 transform transition-all duration-200 flex items-center">Siguiente</button>
            </div>
        </div>
    </div>

    <!-- Paso 2 - Detalles de Salud -->
    <div id="step-2" class="step-content hidden">
        <div class="dashboard-card p-6">
            <h3 class="text-2xl font-bold mb-6">Detalles de Salud</h3>
            
                <!-- Aviso de Bienestar -->
                <div class="mb-8 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-blue-700">Esta sección tiene como propósito velar por su bienestar, prevenir lesiones y proteger su salud. La información proporcionada en este formulario se utilizará con ese fin.</p>
                    </div>
                </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
   <!-- Alergias Mejorado -->
   <div class="space-y-4 bg-gradient-to-br from-emerald-50 to-blue-50 p-6 rounded-xl">
    <h4 class="font-medium text-lg text-emerald-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
        </svg>
        Alergias conocidas
    </h4>
    <div class="grid grid-cols-2 gap-3">
        <label class="flex items-center space-x-2 p-3 bg-white rounded-lg border-2 border-transparent hover:border-emerald-200 transition-all duration-200">
            <input type="checkbox" class="form-checkbox h-5 w-5 text-emerald-600 rounded-md border-2 border-gray-300 checked:border-emerald-600 focus:ring-emerald-500">
            <span class="text-gray-700">Productos con alcohol</span>
        </label>
        <label class="flex items-center space-x-2 p-3 bg-white rounded-lg border-2 border-transparent hover:border-emerald-200 transition-all duration-200">
            <input type="checkbox" class="form-checkbox h-5 w-5 text-purple-600 rounded-md border-2 border-gray-300 checked:border-purple-600 focus:ring-purple-500">
            <span class="text-gray-700">Fragancias</span>
        </label>
        <label class="flex items-center space-x-2 p-3 bg-white rounded-lg border-2 border-transparent hover:border-emerald-200 transition-all duration-200">
            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded-md border-2 border-gray-300 checked:border-blue-600 focus:ring-blue-500">
            <span class="text-gray-700">Colorantes</span>
        </label>
        <label class="flex items-center space-x-2 p-3 bg-white rounded-lg border-2 border-transparent hover:border-emerald-200 transition-all duration-200">
            <input type="checkbox" class="form-checkbox h-5 w-5 text-gray-600 rounded-md border-2 border-gray-300 checked:border-gray-600 focus:ring-gray-500">
            <span class="text-gray-700">Ninguna</span>
        </label>
    </div>
</div>

                <!-- Tipo de Cabello -->
                <div class="space-y-4 bg-gradient-to-br from-purple-50 to-pink-50 p-6 rounded-xl">
                    <h4 class="font-medium text-lg text-purple-800 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Tipo de cabello
                    </h4>
                    <div class="relative group">
                        <select class="w-full p-3 pr-8 bg-white border-2 border-purple-100 rounded-lg appearance-none focus:border-purple-400 focus:ring-2 focus:ring-purple-200 transition-all duration-200">
                            <option class="text-gray-700">Selecciona tu tipo</option>
                            <option class="text-purple-600">Liso</option>
                            <option class="text-purple-600">Ondulado</option>
                            <option class="text-purple-600">Rizado</option>
                            <option class="text-purple-600">Muy rizado</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-purple-500 transform group-hover:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <br>
            <br>
<!-- Nueva Sección: Condición del Cabello -->
<div class="md:col-span-2 space-y-4 bg-gradient-to-br from-blue-50 to-cyan-50 p-6 rounded-xl animate-slideIn">
    <h4 class="font-medium text-lg text-blue-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
        </svg>
        Condición actual del cabello
    </h4>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <label class="flex flex-col items-center p-4 bg-white rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200 cursor-pointer">
            <input type="radio" name="condition" class="h-5 w-5 text-blue-600 mb-2">
            <span class="text-gray-700 text-center">Normal</span>
            <span class="text-blue-500 text-2xl mt-2">✨</span>
        </label>
        <label class="flex flex-col items-center p-4 bg-white rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200 cursor-pointer">
            <input type="radio" name="condition" class="h-5 w-5 text-blue-600 mb-2">
            <span class="text-gray-700 text-center">Seco</span>
            <span class="text-blue-500 text-2xl mt-2">🍂</span>
        </label>
        <label class="flex flex-col items-center p-4 bg-white rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200 cursor-pointer">
            <input type="radio" name="condition" class="h-5 w-5 text-blue-600 mb-2">
            <span class="text-gray-700 text-center">Graso</span>
            <span class="text-blue-500 text-2xl mt-2">💧</span>
        </label>
        <label class="flex flex-col items-center p-4 bg-white rounded-xl border-2 border-transparent hover:border-blue-200 transition-all duration-200 cursor-pointer">
            <input type="radio" name="condition" class="h-5 w-5 text-blue-600 mb-2">
            <span class="text-gray-700 text-center">Dañado</span>
            <span class="text-blue-500 text-2xl mt-2">⚠️</span>
        </label>
    </div>
</div>

            <!-- Notas Adicionales -->
            <div class="mt-6">
                <textarea class="w-full p-3 border rounded-lg" 
                    placeholder="Otra información relevante (tratamientos recientes, sensibilidades, etc.)"></textarea>
            </div>

            <div class="flex justify-between mt-8 space-x-4">
                <button class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>Anterior</button>
                <button class="next-step px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 hover:scale-105 transform transition-all duration-200 flex items-center">Siguiente
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Paso 3 - Selección de Productos -->
    <div id="step-3" class="step-content hidden">
        <div class="dashboard-card p-6">
            <div class="mb-8 text-center">
                <h3 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent mb-4">Selección de Productos</h3>
                <p class="text-gray-600 max-w-2xl mx-auto">Descubre nuestra exclusiva línea de productos profesionales. Cada selección incluye asesoría experta y muestras gratuitas.</p>
            </div>
    
            <!-- Filtros de Productos -->
            <div class="mb-8 flex flex-wrap gap-4 justify-center">
                <button class="filter-btn active" data-category="all">Todos</button>
                <button class="filter-btn" data-category="cuidado">Cuidado Diario</button>
                <button class="filter-btn" data-category="tratamiento">Tratamientos</button>
                <button class="filter-btn" data-category="accesorios">Accesorios</button>
            </div>
            <!-- Opción Sorpréndeme -->
            <div class="mb-6 text-right">
                <button onclick="selectRandomProducts()" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-500">
                    ¡Sorpréndeme!
                </button>
            </div>

            <!-- Lista de Productos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Producto 1 -->
<div class="product-card">
    <div class="image-container">
        <img src="/assets/images/prod3.jpg" alt="Shampoo" class="product-image">
    </div>
    <div class="p-4">
        <h4 class="product-title font-medium">Shampoo Hidratante</h4>
        <p class="product-brand text-sm text-gray-500">L'Oréal Professional</p>
        <p class="product-price text-emerald-600 font-bold">$25.00</p>
        <button onclick="addToCart(this)" 
            class="add-to-cart-btn mt-2 w-full">Agregar</button>
    </div>
</div>

<!-- Producto 2 -->
<div class="product-card">
    <div class="image-container">
        <img src="/assets/images/prod1.webp" alt="Acondicionador" class="product-image">
    </div>
    <div class="p-4">
        <h4 class="product-title font-medium">Acondicionador Reparador</h4>
        <p class="product-brand text-sm text-gray-500">Kerastase</p>
        <p class="product-price text-emerald-600 font-bold">$30.00</p>
        <button onclick="addToCart(this)" 
            class="add-to-cart-btn mt-2 w-full">Agregar</button>
    </div>
</div>

<!-- Producto 3 -->
<div class="product-card">
    <div class="image-container">
        <img src="/assets/images/acondicionados.avif" alt="Mascarilla" class="product-image">
    </div>
    <div class="p-4">
        <h4 class="product-title font-medium">Mascarilla Nutritiva</h4>
        <p class="product-brand text-sm text-gray-500">Olaplex</p>
        <p class="product-price text-emerald-600 font-bold">$45.00</p>
        <button onclick="addToCart(this)" 
            class="add-to-cart-btn mt-2 w-full">Agregar</button>
    </div>
</div>
            </div>

            <!-- Carrito -->
            <div class="cart-section mt-8 p-4 border rounded-lg">
                <h4 class="text-lg font-bold mb-4">Tu selección</h4>
                <div id="cart-items" class="space-y-3"></div>
                <div class="cart-total mt-4 pt-4 border-t font-bold">
                    Total: $<span id="total-amount">0.00</span>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button class="prev-step px-6 py-2 bg-gray-300 rounded-lg">Anterior</button>
                <button onclick="finalizarReserva()" 
                    class="px-8 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-500">
                    Finalizar Reserva
                </button>
            </div>
        </div>
    </div>
</div>
            </div>
    </main>

    <!-- Footer Responsivo -->
    <footer class="bg-gray-800 border-t border-gray-700 mt-8 sm:mt-12">
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


        <!-- WhatsApp Float -->
        <a href="https://wa.me/+529191409310?text=Hola" class="float-wa" target="_blank">
            <i class="fa fa-whatsapp" style="margin-top:16px;"></i>
        </a>

    <script>
        // Navegación entre pasos
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', () => {
                const currentStep = document.querySelector('.step-content.active');
                const nextStep = currentStep.nextElementSibling;
                
                if(nextStep && nextStep.classList.contains('step-content')) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    nextStep.classList.add('active');
                    nextStep.classList.remove('hidden');
                    updateStepIndicator('next');
                }
            });
        });

        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', () => {
                const currentStep = document.querySelector('.step-content.active');
                const prevStep = currentStep.previousElementSibling;
                
                if(prevStep && prevStep.classList.contains('step-content')) {
                    currentStep.classList.remove('active');
                    currentStep.classList.add('hidden');
                    prevStep.classList.add('active');
                    prevStep.classList.remove('hidden');
                    updateStepIndicator('prev');
                }
            });
        });

        // Actualizar indicador de pasos
        function updateStepIndicator(direction) {
            const indicators = document.querySelectorAll('.step-indicator');
            let activeIndex = Array.from(indicators).findIndex(ind => ind.classList.contains('active'));
            
            if(direction === 'next' && activeIndex < indicators.length - 1) {
                indicators[activeIndex].classList.remove('active');
                indicators[activeIndex + 1].classList.add('active');
            }
            
            if(direction === 'prev' && activeIndex > 0) {
                indicators[activeIndex].classList.remove('active');
                indicators[activeIndex - 1].classList.add('active');
            }
        }

        // Carrito de compras
        let cart = [];
        let total = 0;

        function addToCart(button) {
            const productCard = button.closest('.product-card');
            const product = {
                name: productCard.querySelector('.product-title').textContent,
                price: parseFloat(productCard.querySelector('.product-price').textContent.replace('$', '')),
                brand: productCard.querySelector('.product-brand').textContent,
                quantity: 1
            };
            
            cart.push(product);
            updateCartDisplay();
        }

        function updateCartDisplay() {
            const cartItems = document.getElementById('cart-items');
            const totalAmount = document.getElementById('total-amount');
            
            cartItems.innerHTML = '';
            total = 0;
            
            cart.forEach(item => {
                total += item.price * item.quantity;
                cartItems.innerHTML += `
                    <div class="cart-item">
                        <div>
                            <span class="font-medium">${item.name}</span>
                            <span class="text-sm text-gray-500">${item.brand}</span>
                        </div>
                        <span>$${item.price.toFixed(2)}</span>
                    </div>
                `;
            });
            
            totalAmount.textContent = total.toFixed(2);
        }

        // Función Sorpréndeme
        function selectRandomProducts() {
            const products = document.querySelectorAll('.product-card');
            const randomProducts = Array.from(products).sort(() => 0.5 - Math.random()).slice(0, 2);
            
            randomProducts.forEach(product => {
                const addButton = product.querySelector('.add-to-cart-btn');
                addButton.click();
            });
        }

// Finalizar Reserva
function finalizarReserva() {
    if(cart.length === 0) {
        mostrarModal('Error', 'Por favor selecciona al menos un producto', 'error');
        return;
    }
    
    const reservationData = {
        personalInfo: {
            nombre: document.querySelector('input[type="text"]').value,
            telefono: document.querySelector('input[type="tel"]').value
        },
        servicio: document.querySelector('select').value,
        productos: cart,
        total: total
    };
    
    console.log('Datos de reserva:', reservationData);
    mostrarModal('¡Reserva Completada!', 'Tu reserva ha sido procesada con éxito. Te hemos enviado los detalles por whatsapp.', 'success');
    // Aquí iría la lógica para enviar los datos al servidor
}

// Función para mostrar el modal avanzado
function mostrarModal(titulo, mensaje, tipo) {
    // Crear el sonido de notificación
    const audio = new Audio();
    audio.src = tipo === 'success' ? 
        'https://cdn.jsdelivr.net/npm/notification-sounds@0.1.0/dist/sounds/pristine.mp3' : 
        'https://cdn.jsdelivr.net/npm/notification-sounds@0.1.0/dist/sounds/intuition.mp3';
    audio.volume = 0.5;
    
    // Crear el modal container
    const modalContainer = document.createElement('div');
    modalContainer.className = 'fixed inset-0 z-50 flex items-center justify-center overflow-hidden';
    modalContainer.style.opacity = '0';

    // Overlay con blur
    const overlay = document.createElement('div');
    overlay.className = 'absolute inset-0 bg-black backdrop-blur-sm';
    overlay.style.opacity = '0';
    
    // Contenido modal con animaciones
    const modalContent = document.createElement('div');
    modalContent.className = 'bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-11/12 md:w-96 relative z-10 overflow-hidden';
    modalContent.style.transform = 'translateY(20px) scale(0.95)';
    
    // Colores y clases según el tipo
    const headerClass = tipo === 'success' ? 'bg-green-600' : 'bg-red-600';
    const iconBgClass = tipo === 'success' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600';
    const btnClass = tipo === 'success' ? 'bg-green-600 hover:bg-green-700' : 'bg-red-600 hover:bg-red-700';
    
    // Definir el ícono según el tipo
    const iconSVG = tipo === 'success' ? 
        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>` : 
        `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>`;
    
    // Construir la estructura HTML interna
    modalContent.innerHTML = `
        <div class="${headerClass} h-2 w-full"></div>
        <div class="px-6 py-6">
            <div class="flex items-center justify-center mb-5">
                <div class="${iconBgClass} rounded-full p-3 animate-pulse">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        ${iconSVG}
                    </svg>
                </div>
            </div>
            <h3 class="text-xl font-bold text-center text-gray-900 dark:text-white mb-3">${titulo}</h3>
            <p class="text-gray-600 dark:text-gray-300 text-center mb-6">${mensaje}</p>
            <div class="flex justify-center">
                <button type="button" class="${btnClass} text-white font-medium rounded-lg px-5 py-2.5 text-center inline-flex items-center transition-all duration-300 transform hover:scale-105 focus:ring-4 focus:ring-opacity-50 focus:outline-none">
                    <span class="mr-2">Aceptar</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
    
    // Confeti para success (solo en éxito)
    if (tipo === 'success') {
        const confettiCanvas = document.createElement('canvas');
        confettiCanvas.id = 'confetti-canvas';
        confettiCanvas.className = 'fixed inset-0 z-40 pointer-events-none';
        document.body.appendChild(confettiCanvas);
    }
    
    // Agregar elementos al DOM
    modalContainer.appendChild(overlay);
    modalContainer.appendChild(modalContent);
    document.body.appendChild(modalContainer);
    
    // Bloquear scroll del body
    document.body.style.overflow = 'hidden';
    
    // Reproducir sonido
    audio.play();
    
    // Lanzar confeti si es éxito
    if (tipo === 'success' && typeof confetti === 'function') {
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });
    }
    
    // Aplicar animaciones de entrada
    setTimeout(() => {
        overlay.style.transition = 'opacity 0.3s ease-out';
        overlay.style.opacity = '0.5';
        
        modalContainer.style.transition = 'opacity 0.4s ease-out';
        modalContainer.style.opacity = '1';
        
        modalContent.style.transition = 'all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        modalContent.style.transform = 'translateY(0) scale(1)';
    }, 10);
    
    // Añadir ripple effect al botón
    const button = modalContent.querySelector('button');
    button.addEventListener('mousedown', function(e) {
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size/2;
        const y = e.clientY - rect.top - size/2;
        
        ripple.style.width = ripple.style.height = `${size}px`;
        ripple.style.left = `${x}px`;
        ripple.style.top = `${y}px`;
        ripple.className = 'absolute rounded-full bg-white bg-opacity-30 pointer-events-none';
        
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
    
    // Cerrar modal al hacer clic en el botón
    button.addEventListener('click', () => {
        // Animaciones de salida
        overlay.style.opacity = '0';
        modalContent.style.transform = 'translateY(20px) scale(0.95)';
        modalContent.style.opacity = '0';
        
        setTimeout(() => {
            document.body.removeChild(modalContainer);
            document.body.style.overflow = '';
            if (tipo === 'success' && document.getElementById('confetti-canvas')) {
                document.getElementById('confetti-canvas').remove();
            }
        }, 500);
    });
    
    // Cerrar modal con la tecla Escape
    const escHandler = (e) => {
        if (e.key === 'Escape') {
            button.click();
            document.removeEventListener('keydown', escHandler);
        }
    };
    document.addEventListener('keydown', escHandler);
}

// Cargar confetti.js para efectos especiales 
if (tipo === 'success') {
    const confettiScript = document.createElement('script');
    confettiScript.src = 'https://cdn.jsdelivr.net/npm/canvas-confetti@1.5.1/dist/confetti.browser.min.js';
    document.head.appendChild(confettiScript);
}
    </script>
</body>
</html>