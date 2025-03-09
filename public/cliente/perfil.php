<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}


// Obtener el user_id desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del cliente basado en usuario_id
$sql = "SELECT e.* FROM cliente e 
        INNER JOIN usuarios u ON e.id = u.cliente_id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);


// Verificar si se encontraron datos
if (!$cliente) {
    die("No se encontró información en la tabla cliente para este usuario.");
}
?>  



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Perfil Cliente - Noir Elite</title>
    <style>
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="font-body bg-gray-50 text-gray-800 flex flex-col min-h-screen">
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


                <!-- Menú Desktop -->
                <div class="hidden md:flex items-center space-x-6 ">
                    <!-- Selector de Locales -->
                    <div class="relative lg:space-x-8" id="branch-dropdown">
                        <button onclick="toggleDropdown('branch-dropdown')"
                            class="flex items-center space-x-2 text-gray-600 hover:text-gray-900 border-b-2 border-transparent hover:border-emerald-500 pb-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-sm sm:text-base">Sucursal Principal</span>
                        </button>
                        <div id="branch-dropdown-menu lg:space-x-8"
                            class="absolute hidden bg-white shadow-2xl rounded-lg p-4 w-48 mt-2 border border-gray-200 right-0">
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
                        <button onclick="toggleDropdown('account-dropdown')"
                            class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                            <span class="text-sm sm:text-base">Mi Cuenta</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="account-dropdown-menu"
                            class="absolute hidden bg-white shadow-2xl rounded-lg p-4 w-48 mt-2 border border-gray-200 right-0">
                            <a href="#preferencias"
                                class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Preferencias</a>
                            <a href="#seguridad"
                                class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Seguridad</a>
                            <a href="#facturacion"
                                class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Facturación</a>
                            <div class="border-t my-2"></div>
                            <a href="#notificaciones"
                                class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Notificaciones</a>
                            <a href="#soporte"
                                class="block text-gray-600 hover:bg-gray-50 p-2 rounded text-sm">Soporte</a>
                        </div>
                    </div>
                    <a href="../../actions/logout.php"
                        class="bg-emerald-600 text-white px-4 py-2 sm:px-6 sm:py-2 rounded-lg hover:bg-green-800 text-sm sm:text-base">
                        Cerrar Sesión
                    </a>
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
                </nav>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="lg:col-span-3 space-y-6">
            <!-- Welcome Message -->
            <div
                class="welcome-message bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 rounded-2xl shadow-xl animate-slide-in">
                <h2 class="text-3xl font-bold mb-4 text-center">¡Prepárate para vivir una experiencia única!</h2>
                <p class="text-lg opacity-90 mb-6 text-center">Estamos emocionados de crear un look perfecto para ti.
                    Necesitamos algunos datos importantes para personalizar tu experiencia al máximo.</p>

                <a
                    class="block w-full max-w-xs mx-auto bg-white text-emerald-700 px-8 py-3 rounded-full hover:scale-105 transition text-center font-bold">
                    Comenzar Reserva 🚀
                </a>
            </div>
            <!-- Perfil Section -->
            <div class="dashboard-card interactive-element bg-gray-900 text-white mb-6">
                <div class="p-6 flex items-center justify-between">
                    <div>
                        <h2 id="displayName" class="font-heading text-2xl font-bold mb-1">
                            <?= htmlspecialchars($cliente['nombre'] ?? 'Nombre no disponible') ?>
                        </h2>
                        <div class="flex items-center space-x-3">
                            <span class="bg-emerald-600/20 text-emerald-400 px-3 py-1 rounded-full text-sm">
                            <?= htmlspecialchars($cliente['genero'] ?? 'no disponible') ?>
                            </span>
                            <span class="text-gray-400 text-sm">★ 4.9 (128 reseñas)</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Scheduled Appointments -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold">Citas Programadas (2)</h3>
                    <a href="/templates/reservation.html"
                        class="bg-blue-300 text-white px-4 py-2 text-sm sm:text-base rounded-lg hover:bg-blue-700 inline-block text-center">
                        Nueva cita +
                    </a>
                </div>
                <di class="space-y-4">
                    <!-- Appointment Items -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border rounded-lg">
                            <div>
                                <span
                                    class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-full text-xs">Confirmada</span>
                                <h4 class="font-medium">Corte Premium</h4>
                                <p class="text-gray-500 text-sm">20 Ene 2024 - 15:00</p>
                            </div>
                            <div class="space-x-2">
                                <button class="text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg">Cancelar</button>
                                <button class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg">Reagendar</button>
                            </div>
                        </div>
                    </div>

                    <div
                        class="cita-item flex flex-col sm:flex-row items-center justify-between p-4 hover:bg-gray-50 rounded-lg border border-gray-200">
                        <div class="space-y-2 mb-3 sm:mb-0 sm:flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span
                                    class="text-xs sm:text-sm font-medium text-emerald-600 bg-emerald-100 px-2 py-1 rounded-full">Confirmada</span>
                                <span class="text-xs sm:text-sm text-gray-500">20 Ene 2024 - 15:00</span>
                            </div>
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base">Corte Premium</h4>
                            <p class="text-xs sm:text-sm text-gray-500">Sucursal Principal - Ana Martínez</p>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                            <button class="text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg">
                                Cancelar
                            </button>
                            <button class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg">
                                Reagendar
                            </button>
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
    <footer class="bg-gray-800 text-gray-400 py-6 text-center">
        <p>© 2024 Noir Elite. Todos los derechos reservados.</p>
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