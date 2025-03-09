    <!-- Navbar Responsivo -->
    <nav class="bg-white border-b-2 border-emerald-500/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <h1 class="font-heading text-3xl font-bold text-gray-900">
                    <span class="text-emerald-600">NOIR</span> 
                    <span class="text-gray-800">ELITE</span>
                </h1>

                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
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
                    <a href="../../actions/logout.php"
                        class="bg-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-green-800 text-sm sm:text-base">
                        Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <body class="font-body bg-gray-50 text-gray-800 flex flex-col min-h-screen">



    