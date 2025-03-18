</main>
<!-- Footer -->
<footer class="bg-gray-800 border-t border-gray-700 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
        <div class="text-center text-gray-400 text-xs sm:text-sm">
            <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                <a href="#privacidad" class="hover:text-emerald-400">Política de Privacidad</a>
                <a href="#soporte" class="hover:text-emerald-400">Soporte 24/7</a>
                <a href="#terminos" class="hover:text-emerald-400">Términos de Servicio</a>
            </div>
            <p class="mt-3">© 2024 BELLA HAIR. Todos los derechos reservados.</p>
        </div>
    </div>
    
    <!-- Modal Soporte 24/7 -->
    <div id="soporteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4 z-[9999]">
        <div class="bg-white rounded-xl p-8 max-w-md w-full mx-4 shadow-2xl transform transition-all relative z-[10000]">
            <h3 class="text-2xl font-bold mb-6 text-emerald-700 text-center">Soporte 24/7</h3>
            
            <div class="space-y-4">
                <!-- Opción Email -->
                <a href="mailto:soporte@bellahair.com" 
                   class="flex items-center p-4 bg-gray-100 hover:bg-emerald-50 rounded-xl transition-all group border border-emerald-100">
                    <div class="bg-emerald-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Correo Electrónico</h4>
                        <p class="text-sm text-gray-600">soporte@bellahair.com</p>
                    </div>
                </a>

                <!-- Opción Teléfono -->
                <a href="tel:+5215512345678" 
                   class="flex items-center p-4 bg-gray-100 hover:bg-emerald-50 rounded-xl transition-all group border border-emerald-100">
                    <div class="bg-emerald-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">Llamada Telefónica</h4>
                        <p class="text-sm text-gray-600">+52 55 1234 5678</p>
                    </div>
                </a>

                <!-- Opción WhatsApp -->
                <a href="https://wa.me/5215512345678?text=Necesito%20ayuda%20con%20mi%20reserva" 
                   target="_blank"
                   class="flex items-center p-4 bg-gray-100 hover:bg-emerald-50 rounded-xl transition-all group border border-emerald-100">
                    <div class="bg-emerald-100 p-3 rounded-lg mr-4">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-800">WhatsApp</h4>
                        <p class="text-sm text-gray-600">Chat en tiempo real</p>
                    </div>
                </a>
            </div>

            <button onclick="hideModal('soporteModal')" 
                    class="mt-6 w-full py-3 px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors font-semibold">
                Cerrar
            </button>
        </div>
    </div>
</footer>

<script>
    // Gestión de paginación
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
        if (activeBtn) {
            activeBtn.classList.add('bg-emerald-600', 'text-white', 'border-emerald-600');
            activeBtn.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
        }
    }

    // Gestión de dropdowns
    function toggleDropdown(dropdownId) {
        const menu = document.getElementById(`${dropdownId}-menu`);
        document.querySelectorAll('.dropdown-menu').forEach(other => {
            if (other !== menu) other.classList.add('hidden');
        });
        menu.classList.toggle('hidden');
    }

    // Sistema de modales
    function showModal(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        modal.addEventListener('click', function(e) {
            if(e.target === modal) hideModal(modalId);
        });
    }

    function hideModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    }

    // Eventos globales
    document.addEventListener('keydown', (e) => {
        if(e.key === 'Escape') {
            const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
            openModals.forEach(modal => hideModal(modal.id));
        }
    });

    document.querySelector('a[href="#soporte"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        showModal('soporteModal');
    });

    // Inicialización
    document.addEventListener('DOMContentLoaded', function() {
        changePage(1); // Iniciar paginación
    });

    // Logout (mantener si existe)
    function openLogoutModal() {
        document.getElementById("logout-modal").classList.remove("hidden");
    }

    function closeLogoutModal() {
        document.getElementById("logout-modal").classList.add("hidden");
    }
</script>
</body>
</html>