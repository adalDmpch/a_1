<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Verificar que se recibió un ID de empleado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php?mensaje=ID de empleado no válido&tipo=error");
    exit();
}

$empleadoId = $_GET['id'];

// Obtener información del empleado
$stmt = $pdo->prepare("
    SELECT e.*, n.nombrenegocio 
    FROM empleados e
    LEFT JOIN negocio n ON e.negocio_id = n.id
    WHERE e.id = ?
");
$stmt->execute([$empleadoId]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    header("Location: index.php?mensaje=Empleado no encontrado&tipo=error");
    exit();
}

// Obtener historial de citas del empleado
$stmtCitas = $pdo->prepare("
    SELECT c.*, cl.nombre as cliente_nombre, s.tipo as servicio
    FROM citas c
    LEFT JOIN cliente cl ON c.cliente_id = cl.id
    LEFT JOIN servicios s ON c.servicio_id = s.id
    WHERE c.empleado_id = ?
    ORDER BY c.fecha DESC, c.hora DESC
");
$stmtCitas->execute([$empleadoId]);
$citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);

// Contar el total de citas
$totalCitas = count($citas);

// Calcular estadísticas (si hay citas)
$estadisticas = [];
if ($totalCitas > 0) {
    // Calcular porcentaje de citas completadas
    $citasCompletadas = array_filter($citas, function($cita) {
        return $cita['estado'] == 'completada';
    });
    $porcentajeCompletadas = round((count($citasCompletadas) / $totalCitas) * 100);
    
    // Calcular promedio de citas por mes (últimos 6 meses)
    $seisUltimosMeses = date('Y-m-d', strtotime('-6 months'));
    $citasRecientes = array_filter($citas, function($cita) use ($seisUltimosMeses) {
        return $cita['fecha'] >= $seisUltimosMeses;
    });
    $promedioCitasMes = round(count($citasRecientes) / 6, 1);
    
    // Servicios más solicitados con este empleado
    $servicios = [];
    foreach ($citas as $cita) {
        if (!isset($servicios[$cita['servicio']])) {
            $servicios[$cita['servicio']] = 0;
        }
        $servicios[$cita['servicio']]++;
    }
    arsort($servicios);
    $serviciosMasPopulares = array_slice($servicios, 0, 3);
    
    $estadisticas = [
        'totalCitas' => $totalCitas,
        'porcentajeCompletadas' => $porcentajeCompletadas,
        'promedioCitasMes' => $promedioCitasMes,
        'serviciosMasPopulares' => $serviciosMasPopulares
    ];
}

// Procesar la eliminación si se solicita
if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] === 'true') {
    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        // Primero eliminar todas las citas asociadas
        $stmtDeleteCitas = $pdo->prepare("DELETE FROM citas WHERE empleado_id = ?");
        $stmtDeleteCitas->execute([$empleadoId]);
        
        // Luego eliminar el empleado
        $stmtDeleteEmpleado = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
        $stmtDeleteEmpleado->execute([$empleadoId]);
        
        // Confirmar transacción
        $pdo->commit();
        
        header("Location: index.php?mensaje=Empleado y su historial eliminados correctamente&tipo=success");
        exit();
        
    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $pdo->rollBack();
        $errorMsg = "Error al eliminar: " . $e->getMessage();
    }
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
include_once '../templates/mode.php';
?>

<!-- Contenido Principal -->
<main class="container mx-auto p-6 flex-grow bg-gray-50">
    
    <!-- Breadcrumbs -->
    <div class="mb-4 text-sm">
        <a href="index.php" class="text-green-700 hover:text-green-900 transition">
            <i class="fas fa-home"></i> Panel de Administración
        </a>
        <span class="text-gray-500 mx-2">/</span>
        <span class="text-gray-700">Detalles del Empleado</span>
    </div>
    
    <!-- Sección superior con foto e información -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <div class="flex flex-col md:flex-row items-start gap-6">
            <!-- Foto del empleado (placeholder) -->
            <div class="w-32 h-32 rounded-full bg-green-100 flex items-center justify-center mb-4 md:mb-0 overflow-hidden">
                <i class="fas fa-user text-green-700 text-5xl"></i>
            </div>
            
            <!-- Información del empleado -->
            <div class="flex-grow">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
                    <h1 class="text-3xl font-bold text-green-800 mb-2 md:mb-0">
                        <?= htmlspecialchars($empleado['nombreempleado']) ?>
                    </h1>
                    
                    <div class="flex gap-2">
                        <a href="/a_1/public/admin/edit.php?id=<?= $empleado['id'] ?>" 
                           class="inline-flex items-center bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition">
                            <i class="fas fa-edit mr-2"></i> Editar
                        </a>
                        <button id="openDeleteModal" 
                                class="inline-flex items-center bg-red-100 text-red-700 px-4 py-2 rounded-lg hover:bg-red-200 transition">
                            <i class="fas fa-trash-alt mr-2"></i> Eliminar
                        </button>
                        <button id="generateReport" 
                                class="inline-flex items-center bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition">
                            <i class="fas fa-file-pdf mr-2"></i> Generar Reporte
                        </button>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                    <div class="p-3 bg-green-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Sucursal</h3>
                        <p class="font-semibold text-green-800">
                            <?= htmlspecialchars($empleado['nombrenegocio'] ?: 'No asignado') ?>
                        </p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Teléfono</h3>
                        <p class="font-semibold text-green-800">
                            <?= htmlspecialchars($empleado['phoneempleado']) ?>
                        </p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                        <p class="font-semibold text-green-800">
                            <?= htmlspecialchars($empleado['email_empleado']) ?>
                        </p>
                    </div>
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Edad</h3>
                        <p class="font-semibold text-green-800">
                            <?= htmlspecialchars($empleado['edad']) ?> años
                        </p>
                    </div>
                    
                   
                    
                    <div class="p-3 bg-green-50 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Total de citas</h3>
                        <p class="font-semibold text-green-800">
                            <?= $totalCitas ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estadísticas del empleado -->
    <?php if ($totalCitas > 0): ?>
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <h2 class="text-2xl font-bold text-green-800 mb-6">
            <i class="fas fa-chart-pie text-green-700 mr-2"></i> Estadísticas
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-green-50 p-4 rounded-lg text-center">
                <h3 class="text-lg font-semibold mb-2 text-green-800">Citas completadas</h3>
                <div class="flex justify-center">
                    <div class="relative h-32 w-32">
                        <div class="absolute inset-0 flex items-center justify-center text-3xl font-bold text-green-700">
                            <?= $estadisticas['porcentajeCompletadas'] ?>%
                        </div>
                        <svg viewBox="0 0 36 36" class="circular-chart">
                            <path class="circle-bg" d="M18 2.0845
                                  a 15.9155 15.9155 0 0 1 0 31.831
                                  a 15.9155 15.9155 0 0 1 0 -31.831" 
                                  fill="none" stroke="#E2E8F0" stroke-width="3"/>
                            <path class="circle" 
                                  stroke-dasharray="<?= $estadisticas['porcentajeCompletadas'] ?>, 100"
                                  d="M18 2.0845
                                  a 15.9155 15.9155 0 0 1 0 31.831
                                  a 15.9155 15.9155 0 0 1 0 -31.831" 
                                  fill="none" stroke="#047857" stroke-width="3"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-4 text-green-800 text-center">Promedio mensual</h3>
                <div class="flex items-center justify-center h-24">
                    <div class="text-center">
                        <span class="text-4xl font-bold text-green-700"><?= $estadisticas['promedioCitasMes'] ?></span>
                        <p class="text-gray-600 text-sm mt-1">citas por mes</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h3 class="text-lg font-semibold mb-2 text-green-800 text-center">Servicios más solicitados</h3>
                <ul class="space-y-2 mt-4">
                    <?php foreach ($estadisticas['serviciosMasPopulares'] as $servicio => $cantidad): ?>
                    <li class="flex justify-between items-center">
                        <span class="text-gray-700"><?= htmlspecialchars($servicio ?: 'No especificado') ?></span>
                        <span class="text-green-700 font-semibold"><?= $cantidad ?> citas</span>
                    </li>
                    <?php endforeach; ?>
                    <?php if (empty($estadisticas['serviciosMasPopulares'])): ?>
                    <li class="text-gray-500 text-center italic">No hay datos disponibles</li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Historial de citas -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <h2 class="text-2xl font-bold text-green-800 mb-6">
            <i class="fas fa-calendar-alt text-green-700 mr-2"></i> Historial de Citas
        </h2>
        
        <?php if ($totalCitas > 0): ?>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-green-700 text-white">
                        <th class="p-3 font-semibold">Fecha</th>
                        <th class="p-3 font-semibold">Hora</th>
                        <th class="p-3 font-semibold">Cliente</th>
                        <th class="p-3 font-semibold">Servicio</th>
                        <th class="p-3 font-semibold">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($citas as $cita): ?>
                    <tr class="border-b border-gray-200 hover:bg-green-50 transition">
                        <td class="p-3"><?= date('d/m/Y', strtotime($cita['fecha'])) ?></td>
                        <td class="p-3"><?= date('H:i', strtotime($cita['hora'])) ?></td>
                        <td class="p-3"><?= htmlspecialchars($cita['cliente_nombre']) ?></td>
                        <td class="p-3"><?= htmlspecialchars($cita['servicio']) ?></td>
                        <td class="p-3">
                            <?php
                            $estadoClass = '';
                            switch ($cita['estado']) {
                                case 'pendiente':
                                    $estadoClass = 'bg-yellow-100 text-yellow-800';
                                    break;
                                case 'completada':
                                    $estadoClass = 'bg-green-100 text-green-800';
                                    break;
                                case 'cancelada':
                                    $estadoClass = 'bg-red-100 text-red-800';
                                    break;
                                default:
                                    $estadoClass = 'bg-gray-100 text-gray-800';
                            }
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $estadoClass ?>">
                                <?= ucfirst($cita['estado']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <div class="bg-gray-50 rounded-lg p-8 text-center">
            <i class="fas fa-calendar-times text-gray-400 text-5xl mb-4"></i>
            <p class="text-gray-500">Este empleado no tiene historial de citas registradas.</p>
        </div>
        <?php endif; ?>
    </div>
    
    <!-- Modal de confirmación para eliminar -->
    <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i> 
                Confirmar eliminación
            </h2>
            
            <p class="text-gray-700 mb-4">
                Estás a punto de eliminar al empleado <strong><?= htmlspecialchars($empleado['nombreempleado']) ?></strong>. 
                Esta acción también eliminará todo su historial de citas y no se puede deshacer.
            </p>
            
            <div class="mt-6 flex justify-end space-x-4">
                <button id="cancelDelete" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>
                
                <form method="POST" action="">
                    <input type="hidden" name="confirm_delete" value="true">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                        <i class="fas fa-trash-alt mr-2"></i> Eliminar definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Scripts para la página -->
<script>
// Funcionalidad del modal de confirmación
const openModalBtn = document.getElementById('openDeleteModal');
const deleteModal = document.getElementById('deleteModal');
const cancelBtn = document.getElementById('cancelDelete');

openModalBtn.addEventListener('click', () => {
    deleteModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden'; // Prevenir scroll
});

cancelBtn.addEventListener('click', () => {
    deleteModal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restaurar scroll
});

// Funcionalidad para generar reporte
document.getElementById('generateReport').addEventListener('click', function() {
    // Crear un form para hacer POST a generate_employee_report.php
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = 'generate_employee_report.php';
    
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'employee_id';
    input.value = '<?= $empleadoId ?>';
    
    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
});

// Estilos para la gráfica circular
document.head.insertAdjacentHTML('beforeend', `
<style>
.circular-chart {
    display: block;
    margin: 0 auto;
    max-width: 100%;
    max-height: 100%;
    transform: rotate(-90deg);
}
.circle-bg {
    stroke-width: 3;
}
.circle {
    stroke-width: 3;
    stroke-linecap: round;
    animation: progress 1s ease-out forwards;
}
@keyframes progress {
    0% {
        stroke-dasharray: 0 100;
    }
}
</style>
`);
</script>

<?php
include_once '../templates/footeradmin.php';
?>