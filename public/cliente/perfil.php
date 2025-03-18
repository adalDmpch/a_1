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

$pageTitle = 'Perfil Cliente - BELLA HAIR';
include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>


<!-- Main Content Area -->
<div class="lg:col-span-3 space-y-6">
    <!-- Welcome Message -->
    <div
        class="welcome-message bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 rounded-2xl shadow-xl animate-slide-in">
        <h2 class="text-3xl font-bold mb-4 text-center">¡Prepárate para vivir una experiencia única!</h2>
        <p class="text-lg opacity-90 mb-6 text-center">Estamos emocionados de crear un look perfecto para ti.
            Necesitamos algunos datos importantes para personalizar tu experiencia al máximo.</p>

        <a href="../cliente/reservasion.php"
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
            <a href="../cliente/reservasion.php"
                class="bg-blue-300 text-white px-4 py-2 text-sm sm:text-base rounded-lg hover:bg-blue-700 inline-block text-center">
                Nueva cita +
            </a>
        </div>
        <di class="space-y-4">
            
                <!-- Appointment Items -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 rounded-lg border border-gray-200">
                        <div >
                            <span class="bg-emerald-100 text-emerald-600 px-2 py-1 rounded-full text-xs">Confirmada</span>
                            <h4 class="font-medium">Corte Premium</h4>
                            <p class="text-gray-500 text-sm">20 Ene 2024 - 15:00</p>
                            <p class="text-gray-500">Sucursal Principal - Ana Martínez</p>

                        </div>
                        <div class="space-x-2">
                            <button class="text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg">
                                Cancelar
                            </button>
                            <button class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg">
                                Reagendar
                            </button>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 border rounded-lg hover:bg-gray-50 rounded-lg border border-gray-200">
                        <div >
                            <span class="bg-gray-200 text-gray-600 px-2 py-1 rounded-full text-xs">Pendiente</span>
                            <h4 class="font-medium">Corte Premium</h4>
                            <p class="text-gray-500 text-sm">20 Ene 2024 - 15:00</p>
                            <p class="text-gray-500">Sucursal Principal - Ana Martínez</p>

                        </div>
                        <div class="space-x-2">
                            <button class="text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg">
                                Cancelar
                            </button>
                            <button class="text-green-600 hover:bg-green-50 px-3 py-2 rounded-lg">
                                Reagendar
                            </button>
                        </div>
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
</div>
       
<?php
include_once '../templates/footercliente.php';
?>