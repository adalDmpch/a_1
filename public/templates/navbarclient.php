    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto px-4 py-6 grid lg:grid-cols-4 gap-6">
        <div class="w-full max-w-md mx-auto space-y-6">
            <!-- Profile Card -->
            <div class="bg-white shadow-lg rounded-xl p-8 text-center flex flex-col items-center">
                <img src="../uploads/<?= htmlspecialchars(basename($cliente['foto_de_perfil'] ?? 'default.png')) ?>" alt="Foto de perfil"
                    class="w-40 h-40 rounded-full object-cover mb-6 shadow-xl border-4 border-emerald-100">
                    
                <h2 class="text-3xl font-bold text-gray-900 mb-3"> <?= htmlspecialchars($cliente['nombre'] ?? 'Nombre no disponible') ?></h2>
                <span class="bg-emerald-100 text-emerald-700 px-4 py-2 rounded-full text-sm font-semibold">
                    Miembro Premium
                </span>

                <div class="flex items-center justify-center space-x-1 space-y-2 text-sm text-gray-400 mb-3 sm:mb-1">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-gray-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-xs sm:text-sm">Miembro desde: Enero 2024</span>
                </div>
                <a href="../cliente/editar_perfil.php"
                    class="mt-6 w-full max-w-xs py-3 px-6 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-lg transition-colors text-base font-semibold text-center block">
                    Editar Perfil
                </a>
            </div>

            <!-- Quick Access -->
            <div class="bg-white shadow-lg rounded-xl p-8">
                <h3 class="text-xl font-bold mb-6 text-center text-gray-800">Accesos Rápidos</h3>
                <nav class="space-y-4">
                    <a href="../cliente/historial.php"
                        class="flex items-center p-4 text-gray-800 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-100 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-600 group-hover:text-emerald-700" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        <span class="text-base group-hover:text-emerald-700">Historial Completo</span>
                    </a>

                    <a href="#metodos-pago"
                        class="flex items-center p-4 text-gray-800 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-100 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-600 group-hover:text-emerald-700" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="text-base group-hover:text-emerald-700">Métodos de Pago</span>
                    </a>

                    <a href="#seguridad"
                        class="flex items-center p-4 text-gray-800 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-100 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-600 group-hover:text-emerald-700" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span class="text-base group-hover:text-emerald-700">Seguridad</span>
                    </a>

                    <a href="#soporte"
                        class="flex items-center p-4 text-gray-800 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-100 group">
                        <svg class="w-6 h-6 mr-4 text-emerald-600 group-hover:text-emerald-700" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-base group-hover:text-emerald-700">Soporte 24/7</span>
                    </a>
                    
                        <a href="../cliente/perfil.php" class="flex items-center space-x-3 p-3 text-white bg-red-600 hover:bg-red-700 rounded-lg transition border border-red-700">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                            </svg>
                            <span>Inicio</span>
                        </a>
                        
                </nav>
            </div>
        </div>
