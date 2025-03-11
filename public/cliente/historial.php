<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener datos del cliente
$sqlCliente = "SELECT e.* FROM cliente e 
              INNER JOIN usuarios u ON e.id = u.cliente_id 
              WHERE u.id = ?";
$stmtCliente = $pdo->prepare($sqlCliente);
$stmtCliente->execute([$user_id]);
$cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("No se encontró información del cliente.");
}

// Configuración de paginación
$porPagina = 10;
$pagina = isset($_GET['pagina']) ? max(1, (int)$_GET['pagina']) : 1;

// Manejar filtros
$filtro = $_GET['filtro'] ?? 'fecha_reciente';

switch($filtro) {
    case 'fecha_antigua':
        $orderBy = "c.fecha ASC, c.hora ASC";
        break;
    case 'creacion_reciente':
        $orderBy = "c.id DESC";
        break;
    default:
        $orderBy = "c.fecha DESC, c.hora DESC";
        break;
}

// Obtener total de citas
$sqlCount = "SELECT COUNT(*) FROM citas WHERE cliente_id = ?";
$stmtCount = $pdo->prepare($sqlCount);
$stmtCount->execute([$cliente['id']]);
$totalCitas = $stmtCount->fetchColumn();

// Calcular paginación solo si hay más de 10 citas
$paginacionActiva = $totalCitas > $porPagina;
$totalPaginas = $paginacionActiva ? ceil($totalCitas / $porPagina) : 1;
$pagina = min($pagina, $totalPaginas);
$offset = $paginacionActiva ? ($pagina - 1) * $porPagina : 0;

// Construir consulta
$sqlHistorial = "SELECT 
    c.fecha, 
    c.hora,
    c.estado,
    s.tipo AS servicio, 
    e.nombreempleado AS especialista, 
    s.precio AS monto
FROM citas c
INNER JOIN servicios s ON c.servicio_id = s.id
INNER JOIN empleados e ON c.empleado_id = e.id
WHERE c.cliente_id = ?
ORDER BY $orderBy" . ($paginacionActiva ? " LIMIT $porPagina OFFSET $offset" : "");

$stmtHistorial = $pdo->prepare($sqlHistorial);
$stmtHistorial->execute([$cliente['id']]);
$historial = $stmtHistorial->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = 'Historial - BELLA HAIR';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>

<div class="lg:col-span-3 space-y-6">
    <!-- Selector de Filtros -->
    <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h3 class="font-semibold text-gray-700">Filtrar por:</h3>
            <div class="relative w-full sm:w-64">
                <select 
                    id="filtro-orden"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                    onchange="window.location.href = '?<?= http_build_query(array_merge($_GET, ['pagina' => 1])) ?>&filtro=' + this.value"
                >
                    <option value="fecha_reciente" <?= $filtro === 'fecha_reciente' ? 'selected' : '' ?>>Fecha más reciente</option>
                    <option value="fecha_antigua" <?= $filtro === 'fecha_antigua' ? 'selected' : '' ?>>Fecha más antigua</option>
                    <option value="creacion_reciente" <?= $filtro === 'creacion_reciente' ? 'selected' : '' ?>>Creación reciente</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tabla de Historial -->
    <div class="dashboard-card bg-white rounded-lg shadow-sm">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="font-heading text-lg sm:text-xl font-bold text-gray-900">Historial de Citas</h3>
            <p class="mt-2 text-sm text-gray-500">
                <?php if($paginacionActiva) : ?>
                    Mostrando <?= count($historial) ?> citas (Página <?= $pagina ?> de <?= $totalPaginas ?>)
                <?php else : ?>
                    Mostrando todas las <?= $totalCitas ?> citas
                <?php endif; ?>
            </p>
        </div>
        
        <div class="p-4 sm:p-6">
            <?php if (empty($historial)) : ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">No hay citas registradas</h3>
                    <p class="mt-1 text-sm text-gray-500">Programa tu primera cita ahora mismo</p>
                </div>
            <?php else : ?>
                <div class="overflow-x-auto rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Servicio</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialista</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($historial as $cita) : ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        <?= date('d M Y', strtotime($cita['fecha'])) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <?= date('H:i', strtotime($cita['hora'])) ?>
                                    </div>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($cita['servicio']) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($cita['especialista']) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                    $<?= number_format($cita['monto'], 2) ?>
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap">
                                    <span class="px-2.5 py-1.5 rounded-full text-xs font-medium 
                                        <?= match(strtolower($cita['estado'])) {
                                            'pendiente' => 'bg-yellow-100 text-yellow-800',
                                            'aceptada' => 'bg-blue-100 text-blue-800',
                                            'completado' => 'bg-green-100 text-green-800',
                                            default => 'bg-red-100 text-red-800'
                                        } ?>">
                                        <?= ucfirst($cita['estado']) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if($paginacionActiva) : ?>
                <div class="mt-6 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Página <?= $pagina ?> de <?= $totalPaginas ?>
                    </div>
                    <div class="flex gap-2">
                        <?php if($pagina > 1): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina - 1])) ?>" 
                           class="px-4 py-2 border rounded-md hover:bg-gray-100 transition-colors">
                            ← Anterior
                        </a>
                        <?php endif; ?>
                        
                        <?php if($pagina < $totalPaginas): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['pagina' => $pagina + 1])) ?>" 
                           class="px-4 py-2 border rounded-md hover:bg-gray-100 transition-colors">
                            Siguiente →
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include_once '../templates/footercliente.php';
?>