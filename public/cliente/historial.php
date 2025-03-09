<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../LoginAdmin.php");
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

$pageTitle = 'Historial - Noir Elite';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>


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
                        <a href="../cliente/reservasion.php" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-emerald-600 hover:bg-emerald-700 transition-colors duration-200">
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
        
<?php
include_once '../templates/footercliente.php';
?>

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
