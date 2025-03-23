<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Configuración de paginación
$porPagina = 4;
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
$offset = ($pagina - 1) * $porPagina;

// Obtener datos del cliente
$sqlCliente = "SELECT c.* FROM cliente c 
              INNER JOIN usuarios u ON c.id = u.cliente_id 
              WHERE u.id = ?";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute([$user_id]);
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("No se encontró información del cliente.");
}

// Obtener citas ordenadas por ID descendente (más recientes primero)
$sqlCitas = "SELECT 
    c.id,
    c.fecha, 
    c.hora,
    LOWER(c.estado) as estado,
    s.tipo AS servicio, 
    e.nombreempleado AS especialista,
    n.nombrenegocio AS sucursal
FROM citas c
INNER JOIN servicios s ON c.servicio_id = s.id
INNER JOIN empleados e ON c.empleado_id = e.id
INNER JOIN negocio n ON c.negocio_id = n.id
WHERE c.cliente_id = ?
ORDER BY c.id DESC, c.fecha DESC
LIMIT ? OFFSET ?";

$stmtCitas = $pdo->prepare($sqlCitas);
$stmtCitas->execute([$cliente['id'], $porPagina, $offset]);
$citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);

// Obtener total de citas
$sqlTotal = "SELECT COUNT(*) FROM citas WHERE cliente_id = ?";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute([$cliente['id']]);
$totalCitas = $stmtTotal->fetchColumn();
$totalPaginas = ceil($totalCitas / $porPagina);

$pageTitle = 'Perfil Cliente - BELLA HAIR';
include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>
<!-- Main Content Area -->
<div class="lg:col-span-3 space-y-6">
    <!-- Welcome Message -->
    <div class="welcome-message bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-8 rounded-2xl shadow-xl animate-slide-in">
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
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de Citas -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Citas Recientes</h3>
            <a href="../cliente/reservasion.php" class="bg-blue-500 text-white px-5 py-2.5 rounded-lg hover:bg-blue-600 transition-colors">
                Nueva Cita +
            </a>
        </div>

        <div class="space-y-4">
            <?php if(empty($citas)): ?>
                <!-- Mensaje cuando no hay citas -->
                <div class="text-center p-8 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                    <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-semibold text-gray-900">No tienes citas programadas</h3>
                    <p class="mt-2 text-gray-600">¡Programa tu primera cita ahora mismo!</p>
                    <a href="../cliente/reservasion.php" class="mt-6 inline-block bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700 transition-colors">
                        Reservar ahora →
                    </a>
                </div>
            <?php else: ?>
                <?php foreach($citas as $index => $cita): ?>
                <div class="border rounded-xl p-4 hover:bg-gray-50 transition-colors shadow-sm relative 
                    <?= ($pagina === 0 && $index < 0) ? 'border-2 border-blue-200 bg-blue-50' : 'border-gray-200' ?>">
                    
                    <?php if($pagina === 0 && $index < 0): ?>
                    <span class="absolute top-2 right-2 bg-blue-500 text-white px-3 py-1 rounded-full text-xs shadow-md animate-pulse">
                        ¡Nueva!
                    </span>
                    <?php endif; ?>

                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-3">
                                <span class="<?= match($cita['estado']) {
                                    'confirmada' => 'bg-emerald-100 text-emerald-600',
                                    'pendiente' => 'bg-amber-100 text-amber-600',
                                    'cancelada' => 'bg-red-100 text-red-600',
                                    default => 'bg-gray-200 text-gray-600'
                                } ?> px-3 py-1 rounded-full text-sm">
                                    <?= ucfirst($cita['estado']) ?>
                                </span>
                                <span class="text-gray-500 text-sm">
                                    <?= date('d M Y', strtotime($cita['fecha'])) ?> 
                                    <?= date('H:i', strtotime($cita['hora'])) ?>
                                </span>
                            </div>
                            <h4 class="font-semibold text-lg"><?= htmlspecialchars($cita['servicio']) ?></h4>
                            <p class="text-gray-600">
                                <?= htmlspecialchars($cita['especialista']) ?> - 
                                <?= htmlspecialchars($cita['sucursal']) ?>
                            </p>
                        </div>
                        
                        <?php if(in_array($cita['estado'], ['confirmada', 'pendiente'])): ?>
                        <div class="flex gap-3 sm:flex-col">
                            <button onclick="showCancelModal(<?= $cita['id'] ?>)" 
                                class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Cancelar
                            </button>
                            <button onclick="showRescheduleModal(<?= $cita['id'] ?>)" 
                                class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                                </svg>
                                Reagendar
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if(!empty($citas)): ?>
        <!-- Paginación -->
        <div class="mt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
            <span class="text-gray-600 text-sm">
                Mostrando <?= count($citas) ?> de <?= $totalCitas ?> citas
            </span>
            
            <div class="flex gap-2">
                <?php if($pagina > 1): ?>
                <a href="?pagina=<?= $pagina-1 ?>" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                    &laquo; Anterior
                </a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $totalPaginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" class="px-4 py-2 <?= $i == $pagina ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600' ?> rounded-lg hover:bg-emerald-100 transition-colors">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if($pagina < $totalPaginas): ?>
                <a href="?pagina=<?= $pagina+1 ?>" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                    Siguiente &raquo;
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modales -->
<div id="cancelModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl transform transition-all">
        <h3 class="text-xl font-bold mb-4">Confirmar Cancelación</h3>
        <form action="procesar_cancelacion.php" method="POST">
            <input type="hidden" name="cita_id" id="cancelCitaId">
            <p class="mb-6 text-gray-600">¿Estás seguro de querer cancelar esta cita?</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideModal('cancelModal')" 
                    class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                    Volver
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

<div id="rescheduleModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl transform transition-all">
        <h3 class="text-xl font-bold mb-4">Reagendar Cita</h3>
        <form action="procesar_reagendado.php" method="POST">
            <input type="hidden" name="cita_id" id="rescheduleCitaId">
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 mb-2">Nueva fecha</label>
                    <input type="date" name="fecha" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-gray-700 mb-2">Nueva hora</label>
                    <input type="time" name="hora" class="w-full p-2.5 border rounded-lg focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="hideModal('rescheduleModal')" 
                    class="px-4 py-2 text-gray-500 hover:text-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                    Reagendar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showCancelModal(citaId) {
    document.getElementById('cancelCitaId').value = citaId;
    document.getElementById('cancelModal').classList.remove('hidden');
}

function showRescheduleModal(citaId) {
    document.getElementById('rescheduleCitaId').value = citaId;
    document.getElementById('rescheduleModal').classList.remove('hidden');
}

function hideModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Cerrar modal al hacer click fuera
window.onclick = function(event) {
    if (event.target.classList.contains('bg-opacity-50')) {
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.classList.add('hidden');
        });
    }
}
</script>

<?php
include_once '../templates/footercliente.php';
?>