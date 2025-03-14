<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../public/login.php");
    exit();
}

$pageTitle = 'Noir Elite - Historial de Citas';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarempleado.php';

// Obtener el correo del usuario autenticado
$sqlCorreo = "SELECT email_usuario FROM usuarios WHERE id = ?";
$stmtCorreo = $pdo->prepare($sqlCorreo);
$stmtCorreo->execute([$_SESSION["user_id"]]);
$usuario = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['error'] = "No se encontró el usuario.";
    header("Location: ../login.php");
    exit();
}

$correo = $usuario['email_usuario'];

// Obtener el ID del empleado usando su correo
$sqlEmpleado = "SELECT id FROM empleados WHERE email_empleado = ?";
$stmtEmpleado = $pdo->prepare($sqlEmpleado);
$stmtEmpleado->execute([$correo]);
$empleado = $stmtEmpleado->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    $_SESSION['error'] = "No se encontró el empleado asociado a este usuario.";
    header("Location: ../login.php");
    exit();
}

$empleado_id = $empleado['id'];

// Verificar que $empleado_id tenga un valor válido
if (!$empleado_id) {
    $_SESSION['error'] = "El ID del empleado no es válido.";
    header("Location: ../login.php");
    exit();
}

try {
    // Obtener el historial de citas con estado 'aceptada', 'cancelada' y 'completada'
    $sql = "SELECT 
                c.id, 
                c.fecha, 
                c.hora, 
                c.servicio_id, 
                c.estado, 
                c.cliente_id, 
                c.empleado_id, 
                c.negocio_id,
                cl.nombre AS nombre, 
                cl.phone,
                s.tipo AS tipo, 
                s.duracion, 
                s.precio,
                n.nombrenegocio AS nombrenegocio,
                e.nombreempleado AS nombreempleado  -- Agregar esta línea
            FROM citas c
            INNER JOIN cliente cl ON c.cliente_id = cl.id
            INNER JOIN servicios s ON c.servicio_id = s.id
            INNER JOIN empleados e ON c.empleado_id = e.id
            INNER JOIN negocio n ON c.negocio_id = n.id
            WHERE c.empleado_id = ? 
            AND c.estado IN ('confirmada', 'rechazada', 'completada','no_asistio')
            ORDER BY c.fecha DESC, c.hora DESC";


    $stmt = $pdo->prepare($sql);

    if (!$stmt) {
        throw new Exception("Error en la preparación de la consulta: " . implode(" ", $pdo->errorInfo()));
    }

    $stmt->execute([$empleado_id]);
    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Si no hay citas, mostrar un mensaje
    if (!$citas) {
        $_SESSION['error'] = "No se encontraron citas en estos estados.";
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Error al recuperar las citas: " . $e->getMessage();
}
// Mostrar mensajes de error o éxito
if (isset($_SESSION['error'])) {
    echo '<script>
            window.onload = function() {
                showNotification("error", "' . htmlspecialchars($_SESSION['error']) . '");
            };
          </script>';
    unset($_SESSION['error']);
}



?>


<!-- Contenido principal -->
<main class="pt-24 pb-16 px-4">
    <div class="max-w-7xl mx-auto">
        <!-- Encabezado -->
        <div class="mb-10 text-center">
            <h2 class="font-heading text-4xl font-bold text-gray-900 mb-3">Historial de Citas</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Revisa el historial completo de tus citas anteriores y servicios realizados.</p>
        </div>
        
        <!-- Filtros y Acciones -->
        <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                <div class="flex items-center space-x-4">
                    <a href="/a_1/public/empleado/inicio.php"
                        class="font-medium text-gray-500 hover:text-emerald-600 pb-1">Ver citas</a>
                    <a class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1" href="#">Historial</a>
                </div>
                
                <div class="flex space-x-3">
                    <div class="relative">
                        <select id="filtroServicio" onchange="filtrarTabla()" class="bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 appearance-none">
                            <option value="">Todos los servicios</option>
                            <?php
                            // Obtener servicios únicos para el filtro
                            $serviciosUnicos = [];
                            foreach ($citas as $cita) {
                                if (!in_array($cita['tipo'], $serviciosUnicos)) {
                                    $serviciosUnicos[] = $cita['tipo'];
                                    echo '<option value="' . htmlspecialchars($cita['tipo']) . '">' . htmlspecialchars($cita['tipo']) . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="relative">
                            <input type="text" id="buscarCliente" onkeyup="filtrarTabla()" placeholder="Buscar cliente" class="bg-white border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Filtros de estados -->
            <div class="flex flex-wrap gap-2 mb-4 p-6">
                <button onclick="filtrarPorPeriodo('todos')" class="filtro-periodo bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm">Todos</button>
                <button onclick="filtrarPorPeriodo('confirmada')" class="filtro-periodo bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Confirmadas</button>
                <button onclick="filtrarPorPeriodo('rechazada')" class="filtro-periodo bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Rechazadas </button>
                <button onclick="filtrarPorPeriodo('completada')" class="filtro-periodo bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Completadas</button>
                <button onclick="filtrarPorPeriodo('no_asistio')" class="filtro-periodo bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">No Asistidos</button>
            </div>
        </div>
        
        <!-- Tabla de historial -->
        <div class="bg-white rounded-2xl shadow-sm p-9 overflow-hidden">
            <div class="overflow-x-auto p-8">
                <table id="tablaCitas" class="min-w-full divide-y divide-gray-200 p-6">
                    <thead>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Servicio
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Duración
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Precio
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200 p-6">
                        <?php if (!empty($citas) && is_array($citas)):  ?>
                             <?php foreach ($citas as $cita): ?>
                                <tr class="hover:bg-gray-50" data-fecha="<?php echo $cita['fecha']; ?>" data-servicio="<?php echo htmlspecialchars($cita['tipo']); ?>" data-cliente="<?php echo htmlspecialchars($cita['nombre']); ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php 
                                            $fecha = new DateTime($cita['fecha']);
                                            echo $fecha->format('d M, Y'); 
                                        ?>
                                        <div class="text-xs text-gray-400"><?php echo $cita['hora']; ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            <?php 
                                                $nombre = $cita['nombre']; 
                                                $nombres = explode(' ', $nombre);

                                                // Obtenemos la primera inicial
                                                $inicial_1 = substr($nombre, 0, 1);

                                                // Verificamos si hay al menos dos palabras en el nombre
                                                if (count($nombres) > 1) {
                                                    // Si hay más de un nombre, obtenemos la inicial del segundo nombre
                                                    $inicial_2 = substr($nombres[1], 0, 1);
                                                } else {
                                                    // Si solo hay un nombre, dejamos la segunda inicial en blanco o algún valor por defecto
                                                    $inicial_2 = '';
                                                }

                                            // Mostramos las iniciales
                                            echo $inicial_1 . $inicial_2;
                                            ?>   
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <?php echo htmlspecialchars($cita['nombre']); ?>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <?php echo htmlspecialchars($cita['phone']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars($cita['tipo']); ?></div>
                                        <div class="text-xs text-gray-500">Estilista: <?php echo htmlspecialchars($cita['nombreempleado']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php
                                            $duracion = $cita['duracion'];
                                            list($horas, $minutos, $segundos) = explode(':', $duracion);
                                            $horas = (int)$horas;
                                            $minutos = (int)$minutos;
                                            $segundos = (int)$segundos;

                                            if ($horas > 0) {
                                                echo $horas . 'h ';
                                            }
                                            if ($minutos > 0 || $horas == 0) {
                                                echo $minutos . 'm';
                                            }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        $<?php echo number_format($cita['precio'], 0, ',', '.'); ?>
                                        </td>
                                    <td class="px-6 py-4 whitespace-nowrap ">
                                        <?php
                                            $estadoClase = '';
                                            $estadoTexto = $cita['estado'];
                                            
                                            switch ($cita['estado']) {
                                                case 'confirmada':
                                                    $estadoClase = 'bg-green-100 text-green-800';
                                                    $estadoTexto = 'confirmada';
                                                    break;
                                                case 'rechazada':
                                                    $estadoClase = 'bg-red-100 text-red-700';
                                                    $estadoTexto = 'rechazada';
                                                    break;
                                                case 'completada':
                                                    $estadoClase = 'bg-orange-100 text-orange-700';
                                                    $estadoTexto = 'completada';
                                                    break;
                                                case 'no_asistio':
                                                    $estadoClase = 'bg-yellow-100 text-yellow-700';
                                                    $estadoTexto = 'no_asistio';
                                                    break;
                                                default:
                                                    $estadoClase = 'bg-blue-100 text-blue-800';
                                                    break;
                                            }
                                        ?>
                                        <span class=" px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $estadoClase; ?>">
                                            <?php echo $estadoTexto; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium p-8">
                                        <button onclick="mostrarDetallesCita(<?php echo $cita['id']; ?>)" class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500 p-8">
                                    <div class="text-center py-12 animate__animated animate__fadeIn p-8">
                                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <h3 class="mt-6 text-2xl font-medium text-gray-900">No hay citas en el historial</h3>
                                        <p class="mt-2 text-gray-600 max-w-md mx-auto">Aprovecha para organizar otras tareas o revisar la Agenda de citas pendientes.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <?php
    // Definir el número de resultados por página
    $porPagina = 4;

    // Obtener la página actual desde la URL (por defecto será 1)
    $paginaActual = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calcular el índice de inicio
    $inicio = ($paginaActual - 1) * $porPagina;

    // Asegurarse de que $citas es un arreglo
    $citas = $citas ?? []; // Aquí debería estar el arreglo de citas

    // Total de resultados
    $totalResultados = count($citas);

    // Limitar los resultados a mostrar según la página actual
    $resultadosPagina = array_slice($citas, $inicio, $porPagina);
?>

<!-- Paginación -->
<div class="flex items-center justify-between mt-6">
    <div class="text-sm text-gray-500">
        <?php 
        // Mostrar los resultados que se están visualizando en la página actual
        echo $totalResultados > 0 ? ($inicio + 1) . '-' . min($inicio + $porPagina, $totalResultados) : '0'; 
        ?> de <?php echo $totalResultados; ?> resultados
    </div>
    <div class="flex space-x-2">
        <!-- Botón Anterior -->
        <button 
            class="bg-white border border-gray-300 text-gray-500 px-4 py-2 rounded-lg disabled:opacity-50" 
            <?php if ($paginaActual == 1) echo 'disabled'; ?>
            onclick="window.location.href='?page=<?php echo max(1, $paginaActual - 1); ?>'">
            Anterior
        </button>

        <!-- Botón Página 1 -->
        <button 
            class="bg-emerald-600 text-white px-4 py-2 rounded-lg <?php if ($paginaActual == 1) echo 'bg-emerald-500'; ?>"
            onclick="window.location.href='?page=1'">
            1
        </button>

        <!-- Botón Página 2 -->
        <?php if ($totalResultados > 4): ?>
            <button 
                class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-emerald-50 <?php if ($paginaActual == 2) echo 'bg-emerald-500 text-white'; ?>"
                onclick="window.location.href='?page=2'">
                2
            </button>
        <?php endif; ?>

        <!-- Botón Siguiente -->
        <button 
            class="bg-white border border-gray-300 text-gray-500 px-4 py-2 rounded-lg disabled:opacity-50" 
            <?php if ($paginaActual >= ceil($totalResultados / $porPagina)) echo 'disabled'; ?>
            onclick="window.location.href='?page=<?php echo $paginaActual + 1; ?>'">
            Siguiente
        </button>
    </div>
</div>

        </div>
    </div>
</main>

<!-- Modal de detalles de cita -->
<div id="citaModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="p-6" id="modalContent">
            <!-- El contenido se cargará dinámicamente aquí -->
            <div class="flex justify-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-600"></div>
            </div>
        </div>
    </div>
</div>

<script>
   // Función para mostrar el modal con los detalles de la cita
function mostrarDetallesCita(citaId) {
    const modal = document.getElementById('citaModal');
    const modalContent = document.getElementById('modalContent');
    
    // Mostrar el modal
    modal.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
    modal.setAttribute('aria-hidden', 'false'); // Mejora de accesibilidad
    
    // Cargar el contenido
    fetch(`/a_1/public/empleado/detalle_citas.php?id=${citaId}`)
        .then(response => response.text())
        .then(data => {
            modalContent.innerHTML = data;
        })
        .catch(error => {
            modalContent.innerHTML = `<div class="text-red-600 p-4">Error al cargar los detalles: ${error.message}</div>`;
        });
}

// Función para cerrar el modal
function cerrarModal() {
    const modal = document.getElementById('citaModal');
    modal.classList.add('hidden');
    document.body.classList.remove('overflow-hidden');
    modal.setAttribute('aria-hidden', 'true'); // Mejora de accesibilidad
}

// Cerrar el modal al hacer clic fuera del contenido
document.getElementById('citaModal').addEventListener('click', function(e) {
    if (e.target === this) {
        cerrarModal();
    }
});

// Añadir evento de teclado para cerrar con ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !document.getElementById('citaModal').classList.contains('hidden')) {
        cerrarModal();
    }
});

// Filtrar por servicio, cliente o estado
function filtrarTabla() {
    // Obtener los valores de los filtros
    const servicioFiltro = document.getElementById('filtroServicio').value.toLowerCase();
    const clienteFiltro = document.getElementById('buscarCliente').value.toLowerCase();
    
    // Obtener todas las filas de la tabla
    const filas = document.querySelectorAll('#tablaCitas tbody tr');
    
    // Recorrer cada fila de la tabla
    filas.forEach(fila => {
        // Obtener los valores de las celdas de cada fila
        const fecha = fila.querySelector('td:nth-child(1)').textContent.toLowerCase();
        const cliente = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
        const servicio = fila.querySelector('td:nth-child(3)').textContent.toLowerCase();
        
        // Comprobar si la fila cumple con los filtros
        const coincideServicio = servicio.includes(servicioFiltro);
        const coincideCliente = cliente.includes(clienteFiltro);
        
        // Mostrar u ocultar la fila dependiendo de si cumple con los filtros
        if (coincideServicio && coincideCliente) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}

// Filtrar por periodo (todos, confirmadas, rechazadas, completadas, no asistidos)
function filtrarPorPeriodo(estado) {
    const filas = document.querySelectorAll('#tablaCitas tbody tr');
    
    filas.forEach(fila => {
        // Obtener el estado de la cita desde la columna "Estado"
        const estadoCita = fila.querySelector('td:nth-child(6)').textContent.toLowerCase();
        
        // Mostrar u ocultar la fila dependiendo del estado seleccionado
        if (estado === 'todos' || estadoCita.includes(estado)) {
            fila.style.display = '';
        } else {
            fila.style.display = 'none';
        }
    });
}




</script>


<?php
include_once '../templates/footerempleado.php';
?>
<script>
    function showNotification(type, message) {
        const notification = document.createElement('div');
        notification.classList.add('notification', type);
        notification.innerHTML = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        setTimeout(() => {
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000); // El mensaje desaparecerá después de 5 segundos
    }
</script>

</script>
<style>
    .notification {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 16px;
        margin: 10px;
        border-radius: 8px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.5s ease-in-out, visibility 0.5s ease-in-out;
        z-index: 1000;
    }

    .notification.show {
        opacity: 1;
        visibility: visible;
    }

    .notification.success {
        background-color: #4caf50;
        color: white;
    }

    .notification.error {
        background-color: #f44336;
        color: white;
    }

    .notification.rejected {
        background-color: #ff9800;  /* Naranja */
        color: white;
    }
</style>
