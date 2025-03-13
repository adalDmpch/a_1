
</main>
   <!-- Footer -->
   <footer class="bg-gray-800 border-t border-gray-700 mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6">
            <div class="text-center text-gray-400 text-xs sm:text-sm">
                <div class="flex flex-col sm:flex-row justify-center space-y-2 sm:space-y-0 sm:space-x-4">
                    <a href="#privacidad" class="hover:text-emerald-400">Política de Privacidad</a>
                    <a href="#terminos" class="hover:text-emerald-400">Términos de Servicio</a>
                </div>
                <p class="mt-3">© 2024 BELLA HAIR. Todos los derechos reservados.</p>
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

        function openModal() {
            document.getElementById("logout-modal").classList.remove("hidden");
        }

        function closeModal() {
            document.getElementById("logout-modal").classList.add("hidden");
        }
    </script>
</body>

</html>