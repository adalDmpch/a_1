<!-- Footer -->
<footer class="bg-gray-800 text-white py-10">
    <div class="max-w-6xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8">
            <!-- Logo y descripción -->
            <div class="space-y-3">
                <h4 class="font-heading text-xl font-bold">NOIR<span class="text-emerald-500">ELITE</span></h4>
                <p class="text-gray-400 text-sm">Estilo y excelencia en cada corte</p>
            </div>

            <!-- Horarios -->
            <div>
                <h5 class="font-heading text-sm uppercase tracking-wider mb-4 text-gray-300">Horario</h5>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>Lun-Vie: 9am - 8pm</li>
                    <li>Sábado: 9am - 6pm</li>
                    <li>Domingo: Cerrado</li>
                </ul>
            </div>

                <div>
                    <h5 class="font-heading text-sm uppercase tracking-wider mb-4 text-gray-300">Contacto</h5>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Av. Libertador 1234
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            hola@noir.com
                        </li>
                        <li class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            +54 11 5678-9012
                        </li>
                    </ul>
                </div>

            <!-- Redes sociales -->
            <div>
                <h5 class="font-heading text-sm uppercase tracking-wider mb-4 text-gray-300">Síguenos</h5>
                <div class="flex space-x-3">
                    <a href="#" class="bg-gray-800 p-2 rounded-lg text-white hover:bg-emerald-600">
                        <!-- Icono de Twitter -->
                    </a>
                    <a href="#" class="bg-gray-800 p-2 rounded-lg text-white hover:bg-emerald-600">
                        <!-- Icono de Facebook -->
                    </a>
                    <a href="#" class="bg-gray-800 p-2 rounded-lg text-white hover:bg-emerald-600">
                        <!-- Icono de Instagram -->
                    </a>
                </div>
            </div>
        </div>

            <div class="border-t border-gray-800 mt-8 pt-6 text-center">
                <p class="text-gray-500 text-sm">&copy; 2024 Noir Elite Barbería. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

<!-- Script para toggle del menú móvil -->
<script>
    const menuButton = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
</script>