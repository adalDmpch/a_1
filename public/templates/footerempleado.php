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

            <!-- Contacto -->
            <div>
                <h5 class="font-heading text-sm uppercase tracking-wider mb-4 text-gray-300">Contacto</h5>
                <ul class="space-y-2 text-gray-400 text-sm">
                    <li>Av. Libertador 1234</li>
                    <li>hola@noir.com</li>
                    <li>+54 11 5678-9012</li>
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