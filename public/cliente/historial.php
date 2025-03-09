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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
    <style>
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .dashboard-card {
            @apply bg-white border border-gray-200 rounded-xl shadow-lg;
        }
        
        .float-wa {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            z-index: 100;
        }
    </style>
    <title>Reserva - Noir Elite</title>
</head>
<body class="font-body bg-white text-gray-800 flex flex-col min-h-screen">
    <!-- Navbar -->
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

    <!-- Contenido Principal -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8 flex-1">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Panel Lateral -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Perfil -->
                <div class="bg-white shadow-lg rounded-xl p-8 text-center flex flex-col items-center">
                    <img src="/a_1/public/cliente/uploads/<?= htmlspecialchars(basename($cliente['foto_de_perfil'] ?? 'default.png')) ?>" alt="Foto de perfil"
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

                <!-- Navegación Rápida -->
                <div class="dashboard-card p-4 sm:p-6">
                    <h3 class="font-heading text-lg font-bold text-gray-900 mb-4">Accesos Rápidos</h3>
                    <nav class="space-y-2">
                        <a href="../../public/cliente/historial.php" class="flex items-center space-x-3 p-3 text-gray-800 hover:bg-gray-50 rounded-lg hover-transition border border-gray-200">
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

            <!-- Panel Principal -->
            <div class="lg:col-span-3 space-y-6">
                <!-- Historial Reciente -->
                <div class="dashboard-card bg-white rounded-lg">
                    <div class="p-4 sm:p-6 border-b border-gray-200">
                        <h3 class="font-heading text-lg sm:text-xl font-bold text-gray-900">Historial Reciente</h3>
                    </div>
                    <div class="p-4 sm:p-6">
                        <!-- Mensaje cuando no hay historial -->
                        <div id="empty-history" class="hidden text-center py-12">
                            <div class="max-w-md mx-auto">
                                <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                <h3 class="mt-2 text-xl font-bold text-gray-900">No hay reservaciones aún</h3>
                                <p class="mt-1 text-gray-500">Empieza a reservar tus servicios ahora mismo</p>
                                <div class="mt-6">
                                    <a href="/reservar" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200">
                                        Reservar Ahora
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Tabla de Historial -->
                        <div id="history-table" class="relative overflow-x-auto rounded-lg shadow">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Servicio</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialista</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Monto</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="history-body">
                                    <!-- Filas dinámicas -->
                                </tbody>
                            </table>
                        </div>
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

    <!-- WhatsApp Float -->
    <a href="https://wa.me/+529191409310?text=Hola" class="float-wa" target="_blank">
        <i class="fa fa-whatsapp" style="margin-top:16px;"></i>
    </a>

    <script>
        // Control del estado vacío
        document.addEventListener('DOMContentLoaded', () => {
            const historyBody = document.getElementById('history-body');
            const emptyMessage = document.getElementById('empty-history');
            const historyTable = document.getElementById('history-table');

            const updateHistoryView = () => {
                const hasHistory = historyBody.children.length > 0;
                historyTable.classList.toggle('hidden', !hasHistory);
                emptyMessage.classList.toggle('hidden', hasHistory);
            };

            // Ejemplo de datos (simular vacío comentando las filas)
            historyBody.innerHTML = `

            `;

            updateHistoryView();
        });

        // Dropdowns
        function toggleDropdown(dropdownId) {
            const menu = document.getElementById(`${dropdownId}-menu`);
            document.querySelectorAll('.dropdown-menu').forEach(other => {
                if(other !== menu) other.classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        }

        // Logout Modal
        const logoutModal = document.getElementById('logout-modal');
        function openModal() {
            logoutModal.classList.remove('hidden');
        }
        
        function closeModal() {
            logoutModal.classList.add('hidden');
        }

        function logout() {
            window.location.href = "/logout";
        }
    </script>
</body>
</html>