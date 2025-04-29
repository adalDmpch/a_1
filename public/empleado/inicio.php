<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'BELLA HAIR - Agenda de Citas';
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

// Mostrar mensajes de error o éxito
if (isset($_SESSION['error'])) {
    echo '<script>
            window.onload = function() {
                showNotification("error", "' . htmlspecialchars($_SESSION['error']) . '");
            };
          </script>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['exito'])) {
    echo '<script>
            window.onload = function() {
                showNotification("success", "' . htmlspecialchars($_SESSION['exito']) . '");
            };
          </script>';
    unset($_SESSION['exito']);
}

// Obtener todas las citas del empleado
$sql_pendientes = "SELECT c.*, s.tipo AS tipo, cl.nombre AS cliente_nombre, c.estado 
                FROM citas c 
                JOIN servicios s ON c.servicio_id = s.id 
                JOIN cliente cl ON c.cliente_id = cl.id 
                WHERE c.empleado_id = ? AND c.estado = 'pendiente' 
                ORDER BY c.hora ASC";

$sql_confirmadas = "SELECT c.*, s.tipo AS tipo, cl.nombre AS cliente_nombre, c.estado, c.hora_inicio_real, c.hora_fin_real
                FROM citas c 
                JOIN servicios s ON c.servicio_id = s.id 
                JOIN cliente cl ON c.cliente_id = cl.id 
                WHERE c.empleado_id = ? AND c.estado = 'confirmada' 
                ORDER BY c.fecha ASC, c.hora ASC";

$sql_completadas = "SELECT c.*, s.tipo AS tipo, cl.nombre AS cliente_nombre, c.estado, c.hora_inicio_real, c.hora_fin_real
                FROM citas c 
                JOIN servicios s ON c.servicio_id = s.id 
                JOIN cliente cl ON c.cliente_id = cl.id 
                WHERE c.empleado_id = ? AND c.estado = 'completada' 
                ORDER BY c.fecha DESC, c.hora DESC";

try {
    $stmt_pendientes = $pdo->prepare($sql_pendientes);
    $stmt_pendientes->execute([$empleado_id]);
    $citas_pendientes = $stmt_pendientes->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_confirmadas = $pdo->prepare($sql_confirmadas);
    $stmt_confirmadas->execute([$empleado_id]);
    $citas_confirmadas = $stmt_confirmadas->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt_completadas = $pdo->prepare($sql_completadas);
    $stmt_completadas->execute([$empleado_id]);
    $citas_completadas = $stmt_completadas->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $_SESSION['error'] = "Error al cargar las citas: " . $e->getMessage();
}
?>

<!-- Contenido principal -->
<main class="pt-24 pb-24 px-8">
    <div class="max-w-7xl mx-auto flex-grow">
        <!-- Encabezado y tabs de navegación -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <div class="flex-grow">
                    <h2 class="font-heading text-4xl text-center md:text-left font-bold text-gray-900 mb-3">
                        Panel de Citas
                    </h2>
                    <p class="text-gray-600 max-w-2xl md:mx-0 mx-auto">
                        Gestiona tus citas, consulta tu agenda y organiza tu tiempo de manera eficiente.
                    </p>
                </div>

                <div class="flex items-center space-x-4 flex-shrink-0">
                    <a class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1" href="/a_1/public/empleado/inicio.php">
                        Ver citas
                    </a>
                    <a href="/a_1/public/empleado/agenda.php" class="font-medium text-gray-500 hover:text-emerald-600 pb-1">
                        Historial
                    </a>
                </div>
            </div>
            
            <!-- Tabs de navegación entre los diferentes tipos de citas -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button onclick="showTab('pendientes')" class="tab-button active-tab border-emerald-500 text-emerald-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Pendientes <span class="ml-2 bg-emerald-100 text-emerald-700 py-0.5 px-2 rounded-full"><?php echo count($citas_pendientes); ?></span>
                    </button>
                    <button onclick="showTab('confirmadas')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Confirmadas <span class="ml-2 bg-green-100 text-green-700 py-0.5 px-2 rounded-full"><?php echo count($citas_confirmadas); ?></span>
                    </button>
                    <button onclick="showTab('completadas')" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Completadas <span class="ml-2 bg-purple-100 text-purple-700 py-0.5 px-2 rounded-full"><?php echo count($citas_completadas); ?></span>
                    </button>
                </nav>
            </div>
        </div>

        <!-- Notificaciones -->
        <div id="notification" class="fixed top-0 right-0 mt-8 mr-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg hidden">
            <p id="notification-text"></p>
        </div>

        <!-- Contenedor de citas pendientes -->
        <div id="tab-pendientes" class="tab-content bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h3 class="font-heading text-2xl font-bold mb-8">Citas pendientes de confirmación</h3>

            <div class="space-y-4">
                <?php if (count($citas_pendientes) > 0): ?>
                    <?php foreach ($citas_pendientes as $cita): ?>
                        <?php $hora = date("H:i", strtotime($cita['hora'])); ?>
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="font-medium font-heading text-2xl mb-2"><?php echo htmlspecialchars($cita['cliente_nombre']); ?></h2>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($cita['tipo']); ?></p>
                                    <div class="flex items-center mt-3 text-gray-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span><?php echo $hora; ?></span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <!-- Botones de acción -->
                                    <form method="post" action="../../actions/procesar_cita.php" style="display: inline;">
                                        <input type="hidden" name="id_cita" value="<?php echo $cita['id']; ?>">
                                        <input type="hidden" name="accion" value="aceptar">
                                        <button type="submit" class="text-emerald-600 hover:bg-emerald-50 p-2 rounded-full" title="Aceptar cita">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </form>
                                    
                                    <form method="post" action="../../actions/procesar_cita.php" style="display: inline;">
                                        <input type="hidden" name="id_cita" value="<?php echo $cita['id']; ?>">
                                        <input type="hidden" name="accion" value="rechazar">
                                        <button type="submit" class="text-red-600 hover:bg-red-50 p-2 rounded-full" title="Rechazar cita">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between">
                                <span class="bg-gray-100 text-gray-800 text-xs px-3 py-1 rounded-full">
                                    Pendiente
                                </span>
                                <span class="text-xs text-gray-500">
                                    <?php echo date("d/m/Y", strtotime($cita['fecha'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 animate__animated animate__fadeIn">
                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-6 text-2xl font-medium text-gray-900">No tienes citas pendientes</h3>
                        <p class="mt-2 text-gray-600 max-w-md mx-auto">Tu agenda está libre. Aprovecha para organizar otras tareas o revisar el historial de citas anteriores.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contenedor de citas confirmadas -->
        <div id="tab-confirmadas" class="tab-content bg-white rounded-2xl shadow-sm p-8 mb-8 hidden">
            <h3 class="font-heading text-2xl font-bold mb-8">Citas confirmadas</h3>

            <div class="space-y-4">
                <?php if (count($citas_confirmadas) > 0): ?>
                    <?php foreach ($citas_confirmadas as $cita): ?>
                        <?php 
                        $hora = date("H:i", strtotime($cita['hora']));
                        $servicioIniciado = !empty($cita['hora_inicio_real']); 
                        ?>
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="font-medium font-heading text-2xl mb-2"><?php echo htmlspecialchars($cita['cliente_nombre']); ?></h2>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($cita['tipo']); ?></p>
                                    <div class="flex items-center mt-3 text-gray-500">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Programada: <?php echo $hora; ?></span>
                                    </div>
                                    
                                    <?php if ($servicioIniciado): ?>
                                    <div class="flex items-center mt-2 text-blue-600">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                        <span>Inicio real: <?php echo date("H:i", strtotime($cita['hora_inicio_real'])); ?></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="mostrarDetallesCita(<?php echo $cita['id']; ?>)" class="text-emerald-600 hover:bg-emerald-50 p-2 rounded-full" title="Ver detalles">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between items-center">
                                <span class="bg-green-100 text-green-800 text-xs px-3 py-1 rounded-full">
                                    Confirmada
                                </span>
                                
                                <div class="ml-auto">
                                    <span class="text-xs text-gray-500 mr-4">
                                        <?php echo date("d/m/Y", strtotime($cita['fecha'])); ?>
                                    </span>
                                    <?php if (!$servicioIniciado): ?>
                                    <form method="post" action="../../actions/actulizar_modal.php" class="inline">
                                        <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">
                                        <input type="hidden" name="accion" value="iniciar_servicio">
                                        <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-blue-700">
                                            Iniciar Servicio
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <form method="post" action="../../actions/actulizar_modal.php" class="inline">
                                        <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">
                                        <input type="hidden" name="accion" value="finalizar_servicio">
                                        <button type="submit" class="bg-purple-600 text-white px-3 py-1 rounded-lg text-xs hover:bg-purple-700">
                                            Finalizar Servicio
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 animate__animated animate__fadeIn">
                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-6 text-2xl font-medium text-gray-900">No tienes citas confirmadas</h3>
                        <p class="mt-2 text-gray-600 max-w-md mx-auto">Tu agenda de citas confirmadas está vacía. Cuando aceptes nuevas citas, aparecerán aquí.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contenedor de citas completadas -->
        <div id="tab-completadas" class="tab-content bg-white rounded-2xl shadow-sm p-8 mb-8 hidden">
            <h3 class="font-heading text-2xl font-bold mb-8">Citas completadas</h3>

            <div class="space-y-4">
                <?php if (count($citas_completadas) > 0): ?>
                    <?php foreach ($citas_completadas as $cita): ?>
                        <?php $hora = date("H:i", strtotime($cita['hora'])); ?>
                        <div class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h2 class="font-medium font-heading text-2xl mb-2"><?php echo htmlspecialchars($cita['cliente_nombre']); ?></h2>
                                    <p class="text-gray-600"><?php echo htmlspecialchars($cita['tipo']); ?></p>
                                    <div class="grid grid-cols-1 gap-2 mt-3">
                                        <div class="flex items-center text-gray-500">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span>Programada: <?php echo $hora; ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-blue-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                            <span>Inicio: <?php echo date("H:i", strtotime($cita['hora_inicio_real'])); ?></span>
                                        </div>
                                        
                                        <div class="flex items-center text-purple-600">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            <span>Finalizado: <?php echo date("H:i", strtotime($cita['hora_fin_real'])); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="mostrarDetallesCita(<?php echo $cita['id']; ?>)" class="text-emerald-600 hover:bg-emerald-50 p-2 rounded-full" title="Ver detalles">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between items-center">
                                <span class="bg-purple-100 text-purple-800 text-xs px-3 py-1 rounded-full">
                                    Completada
                                </span>
                                <span class="text-xs text-gray-500">
                                    <?php echo date("d/m/Y", strtotime($cita['fecha'])); ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-12 animate__animated animate__fadeIn">
                        <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-6 text-2xl font-medium text-gray-900">No tienes citas completadas</h3>
                        <p class="mt-2 text-gray-600 max-w-md mx-auto">Cuando finalices tus servicios, las citas completadas aparecerán aquí.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php include_once '../templates/footerempleado.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<script>
    // Función para mostrar y ocultar los tabs
    function showTab(tabName) {
        // Ocultar todos los contenidos de tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Mostrar el contenido del tab seleccionado
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Actualizar clases para los botones de tabs
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active-tab', 'border-emerald-500', 'text-emerald-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Actualizar el botón seleccionado
        event.currentTarget.classList.remove('border-transparent', 'text-gray-500');
        event.currentTarget.classList.add('active-tab', 'border-emerald-500', 'text-emerald-600');
    }
    
    // Función para mostrar notificaciones
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
        }, 5000);
    }
    
    // Función para mostrar detalles de la cita (implementar según necesidades)
    function mostrarDetallesCita(id) {
        // Aquí se puede implementar la lógica para mostrar los detalles de la cita
        // Por ejemplo, abrir un modal con más información
        console.log("Mostrar detalles de la cita ID: " + id);
    }
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
        background-color: #ff9800;
        color: white;
    }
    
    /* Estilos para los tabs */
    .active-tab {
        border-color: #10b981;
        color: #10b981;
    }
    
    .tab-content {
        transition: all 0.3s ease;
    }
    
    /* Animación para las tarjetas de citas */
    .border {
        transition: all 0.2s ease-in-out;
    }
</style>