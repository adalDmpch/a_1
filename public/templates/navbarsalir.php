   <!-- Navbar -->
   <nav  class="bg-white border-b-2 border-emerald-500/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                        <span class="text-emerald-600">NOIR</span>
                        <span class="text-gray-800">ELITE</span>
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <!-- <a href="" class="text-gray-600 hover:text-gray-900">Volver al Dashboard</a> -->
                    <a href="../empleado/perfil.php" class="flex items-center space-x-3 p-3 text-red-600 hover:bg-red-100  px-3 py-2 rounded-lg">
                        <svg class="w-6 h-6 text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                        </svg>
                        <span>Inicio</span>
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
                <a href="../empleado/perfil.php" class="flex items-center space-x-3 p-3 text-red-600 hover:bg-red-100  px-3 py-2 rounded-lg">
                        <svg class="w-6 h-6 text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                        </svg>
                        <span>Inicio</span>
                    </a>
            </div>
        </div>
    </nav>
