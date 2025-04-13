<?php
require '../../config/confg.php';

// Fetch all businesses for dropdown filter
$stmt = $pdo->query("SELECT * FROM negocio ORDER BY nombrenegocio");
$allNegocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get filter parameters
$negocioFilter = isset($_GET['negocio_id']) ? $_GET['negocio_id'] : '';
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Fetch businesses with additional info and apply filters
$businessQuery = "
    SELECT n.*, mp.tipo AS metodo_de_pago, 
           (
               SELECT COALESCE(STRING_AGG(s.tipo, ', '), '')
               FROM negocio_servicios ns 
               JOIN servicios s ON ns.servicio_id = s.id
               WHERE ns.negocio_id = n.id
           ) AS servicios
    FROM negocio n
    LEFT JOIN metodo_de_pago mp ON n.metodo_de_pago_id = mp.id
    WHERE 1=1
";

$params = [];

if (!empty($searchTerm)) {
    $businessQuery .= " AND (n.nombrenegocio LIKE ? OR n.ubicaciondelnegocio LIKE ?)";
    $params[] = "%$searchTerm%";
    $params[] = "%$searchTerm%";
}

$businessQuery .= " ORDER BY n.nombrenegocio";
$stmt = $pdo->prepare($businessQuery);
$stmt->execute($params);
$negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch employees with business info and apply filters
$employeeQuery = "
    SELECT e.*, n.nombrenegocio AS nombre_negocio
    FROM empleados e
    LEFT JOIN negocio n ON e.negocio_id = n.id
    WHERE 1=1
";

$empParams = [];

if (!empty($negocioFilter)) {
    $employeeQuery .= " AND e.negocio_id = ?";
    $empParams[] = $negocioFilter;
}

if (!empty($searchTerm)) {
    $employeeQuery .= " AND (e.nombreempleado LIKE ? OR n.nombrenegocio LIKE ?)";
    $empParams[] = "%$searchTerm%";
    $empParams[] = "%$searchTerm%";
}

// Pagination for employees - MODIFIED to 4 per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 4; // Changed from 10 to 4 employees per page
$totalEmployeesStmt = $pdo->prepare(str_replace("SELECT e.*, n.nombrenegocio", "SELECT COUNT(*)", $employeeQuery));
$totalEmployeesStmt->execute($empParams);
$totalEmployees = $totalEmployeesStmt->fetchColumn();
$totalPages = ceil($totalEmployees / $perPage);

$employeeQuery .= " ORDER BY e.nombreempleado LIMIT " . $perPage . " OFFSET " . ($page - 1) * $perPage;
$stmt = $pdo->prepare($employeeQuery);
$stmt->execute($empParams);
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
include_once '../templates/mode.php';
?>

<!-- Contenido Principal -->
<main class="container mx-auto p-6 flex-grow bg-gray-50">
    
    <!-- Header section with title and search -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <h1 class="text-3xl font-bold text-green-800 mb-4">Panel de Administración</h1>
        
        <!-- Search and Filter Form -->
        <form action="" method="GET" class="mb-6">
            <div class="flex flex-wrap gap-4 items-end">
                <div class="flex-grow min-w-[200px]">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="search" id="search" value="<?= htmlspecialchars($searchTerm) ?>" 
                            class="pl-10 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500" 
                            placeholder="Buscar por nombre o ubicación...">
                    </div>
                </div>
                
                <div class="min-w-[200px]">
                    <label for="negocio_id" class="block text-sm font-medium text-gray-700 mb-1">Filtrar por Negocio</label>
                    <select name="negocio_id" id="negocio_id" 
                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Todos los negocios</option>
                        <?php foreach ($allNegocios as $neg): ?>
                            <option value="<?= $neg['id'] ?>" <?= $negocioFilter == $neg['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($neg['nombrenegocio']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <button type="submit" class="bg-green-700 hover:bg-green-800 text-white py-2 px-4 rounded-md shadow-sm transition">
                        <i class="fas fa-filter mr-2"></i>Aplicar filtros
                    </button>
                </div>
                
                <?php if (!empty($searchTerm) || !empty($negocioFilter)): ?>
                <div>
                    <a href="<?= $_SERVER['PHP_SELF'] ?>" class="inline-flex items-center py-2 px-4 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition">
                        <i class="fas fa-times mr-2"></i>Limpiar filtros
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-store text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Negocios</p>
                    <p class="text-2xl font-bold text-gray-800"><?= count($negocios) ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-users text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Total Empleados</p>
                    <p class="text-2xl font-bold text-gray-800"><?= $totalEmployees ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-credit-card text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Métodos de Pago</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?php
                        $stmtPayments = $pdo->query("SELECT COUNT(*) FROM metodo_de_pago");
                        echo $stmtPayments->fetchColumn();
                        ?>
                    </p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-green-600">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <i class="fas fa-concierge-bell text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Servicios</p>
                    <p class="text-2xl font-bold text-gray-800">
                        <?php
                        $stmtServices = $pdo->query("SELECT COUNT(*) FROM servicios");
                        echo $stmtServices->fetchColumn();
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabla de Negocios -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-800">
                <i class="fas fa-store text-green-700 mr-2"></i> Mis Negocios
            </h2>
            <div class="flex flex-wrap gap-3">
                <a href="/a_1/public/admin/create_bussines.php" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Agregar Negocio
                </a>
                <a href="/a_1/public/admin/create_metodo_pago.php" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center">
                    <i class="fas fa-credit-card mr-2"></i> Métodos de Pago
                </a>
                <a href="/a_1/public/admin/servicio.php" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center">
                    <i class="fas fa-concierge-bell mr-2"></i> Servicios
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-green-700 text-white">
                        <th class="p-3 font-semibold">Nombre</th>
                        <th class="p-3 font-semibold hidden md:table-cell">Dirección</th>
                        <th class="p-3 font-semibold hidden md:table-cell">Teléfono</th>
                        <th class="p-3 font-semibold hidden md:table-cell">Email</th>
                        <th class="p-3 font-semibold hidden lg:table-cell">Método de Pago</th>
                        <th class="p-3 font-semibold hidden xl:table-cell">Servicios</th>
                        <th class="p-3 font-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($negocios) > 0): ?>
                        <?php foreach ($negocios as $negocio): ?>
                        <tr class="border-b border-gray-200 hover:bg-green-50 transition">
                            <td class="p-3 font-medium"><?= htmlspecialchars($negocio['nombrenegocio']) ?></td>
                            <td class="p-3 hidden md:table-cell"><?= htmlspecialchars($negocio['ubicaciondelnegocio']) ?></td>
                            <td class="p-3 hidden md:table-cell"><?= htmlspecialchars($negocio['phonenegocio']) ?></td>
                            <td class="p-3 hidden md:table-cell"><?= htmlspecialchars($negocio['emailnegocio']) ?></td>
                            <td class="p-3 hidden lg:table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?= htmlspecialchars($negocio['metodo_de_pago'] ?: 'No especificado') ?>
                                </span>
                            </td>
                            <td class="p-3 hidden xl:table-cell">
                                <?php if (!empty($negocio['servicios'])): ?>
                                    <?php $servicios = explode(', ', $negocio['servicios']); ?>
                                    <div class="flex flex-wrap gap-1">
                                        <?php foreach ($servicios as $servicio): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                <?= htmlspecialchars($servicio) ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400 text-sm italic">No hay servicios</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="/a_1/public/admin/edit_bussinnes.php?id=<?= $negocio['id'] ?>" 
                                       class="inline-flex items-center text-green-600 hover:text-green-900 font-medium">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden sm:inline">Editar</span>
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="javascript:void(0)" 
                                       class="inline-flex items-center text-red-600 hover:text-red-900 font-medium delete-btn" 
                                       data-id="<?= $negocio['id'] ?>" 
                                       data-tipo="negocio" 
                                       data-nombre="<?= htmlspecialchars($negocio['nombrenegocio']) ?>">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="ml-1 hidden sm:inline">Eliminar</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="p-4 text-center text-gray-500">
                                No se encontraron negocios que coincidan con los criterios de búsqueda.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Empleados -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border-l-4 border-green-700">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-green-800">
                <i class="fas fa-users text-green-700 mr-2"></i> Mis Empleados
            </h2>
            <a href="/a_1/public/admin/create.php" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded-lg font-semibold transition flex items-center">
                <i class="fas fa-user-plus mr-2"></i> Agregar Empleados
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-green-700 text-white">
                        <th class="p-3 font-semibold">Nombre</th>
                        <th class="p-3 font-semibold">Sucursal</th>
                        <th class="p-3 font-semibold hidden md:table-cell">Teléfono</th>
                        <th class="p-3 font-semibold hidden lg:table-cell">Email</th>
                        <th class="p-3 font-semibold hidden lg:table-cell">Edad</th>
                        <th class="p-3 font-semibold text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($empleados) > 0): ?>
                        <?php foreach ($empleados as $empleado): ?>
                        <tr class="border-b border-gray-200 hover:bg-green-50 transition">
                            <td class="p-3 font-medium"><?= htmlspecialchars($empleado['nombreempleado']) ?></td>
                            <td class="p-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <?= htmlspecialchars($empleado['nombre_negocio'] ?: 'No asignado') ?>
                                </span>
                            </td>
                            <td class="p-3 hidden md:table-cell"><?= htmlspecialchars($empleado['phoneempleado']) ?></td>
                            <td class="p-3 hidden lg:table-cell"><?= htmlspecialchars($empleado['email_empleado']) ?></td>
                            <td class="p-3 hidden lg:table-cell"><?= htmlspecialchars($empleado['edad']) ?></td>
                            <td class="p-3 text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="/a_1/public/admin/edit.php?id=<?= $empleado['id'] ?>" 
                                        class="inline-flex items-center text-green-600 hover:text-green-900 font-medium">
                                        <i class="fas fa-edit"></i>
                                        <span class="ml-1 hidden sm:inline">Editar</span>
                                    </a>
                                    <span class="text-gray-300">|</span>
                                    <a href="/a_1/public/admin/employee_details.php?id=<?= $empleado['id'] ?>" 
                                        class="inline-flex items-center text-blue-600 hover:text-blue-900 font-medium">
                                        <i class="fas fa-eye"></i>
                                        <span class="ml-1 hidden sm:inline">Detalles</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="p-4 text-center text-gray-500">
                                No se encontraron empleados que coincidan con los criterios de búsqueda.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination - Updated to show 4 employees per page -->
        <?php if ($totalPages > 1): ?>
        <div class="flex justify-between items-center mt-6">
            <div class="text-sm text-gray-500">
                Mostrando <?= count($empleados) ?> de <?= $totalEmployees ?> empleados
            </div>
            <div class="flex space-x-1">
                <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?><?= !empty($negocioFilter) ? '&negocio_id=' . $negocioFilter : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" 
                   class="px-3 py-1 rounded border border-gray-300 text-green-700 hover:bg-green-50 transition">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <?php endif; ?>
                
                <?php 
                // Show fewer page numbers if there are many pages
                $startPage = max(1, $page - 2);
                $endPage = min($totalPages, $startPage + 4);
                if ($endPage - $startPage < 4) {
                    $startPage = max(1, $endPage - 4);
                }
                ?>
                
                <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="?page=<?= $i ?><?= !empty($negocioFilter) ? '&negocio_id=' . $negocioFilter : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" 
                   class="px-3 py-1 rounded border <?= $i == $page ? 'bg-green-700 text-white border-green-700' : 'border-gray-300 text-green-700 hover:bg-green-50' ?> transition">
                    <?= $i ?>
                </a>
                <?php endfor; ?>
                
                <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?><?= !empty($negocioFilter) ? '&negocio_id=' . $negocioFilter : '' ?><?= !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : '' ?>" 
                   class="px-3 py-1 rounded border border-gray-300 text-green-700 hover:bg-green-50 transition">
                    <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>
<?php if (isset($_GET['mensaje'])): ?>
    <div class="toast-notification <?php echo $_GET['tipo']; ?>">
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
    </div>

    <style>
        .toast-notification {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #333;
            color: #fff;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            font-family: sans-serif;
            z-index: 9999;
            opacity: 0;
            animation: fadeInOut 5s ease-in-out forwards;
        }

        .toast-notification.success {
            background-color: #28a745;
        }

        .toast-notification.error {
            background-color: #dc3545;
        }

        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; }
            100% { opacity: 0; transform: translateY(20px); }
        }
    </style>

    <script>
        setTimeout(() => {
            const toast = document.querySelector('.toast-notification');
            if (toast) toast.remove();
        }, 5000);
    </script>
<?php endif; ?>

<!-- Sweet Alert Script -->
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const tipo = this.getAttribute('data-tipo');
        const nombre = this.getAttribute('data-nombre');
        const tipoCapitalizado = tipo.charAt(0).toUpperCase() + tipo.slice(1);
        
        Swal.fire({
            title: `¿Eliminar ${tipoCapitalizado}?`,
            html: `¿Estás seguro que deseas eliminar <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#047857',
            cancelButtonColor: '#dc2626',
            confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar',
            cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancelar',
            focusConfirm: false,
            allowOutsideClick: () => !Swal.isLoading(),
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../../actions/delete.php?id=${id}&tipo=${tipo}`;
            }
        });
    });
});
</script>

<!-- Filter by business automatically submits the form -->
<script>
document.getElementById('negocio_id').addEventListener('change', function() {
    this.form.submit();
});
</script>

<!-- Add animations CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<?php
include_once '../templates/footeradmin.php';
?>