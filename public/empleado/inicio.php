<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

// Verifica que $_SESSION["user_id"] esté configurada correctamente
// var_dump($_SESSION["user_id"]); // Esto debería mostrar el valor de user_id

$pageTitle = 'BELLA HAIR - Agenda de Citas';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarempleado.php';

// Obtener el correo del usuario autenticado
$sqlCorreo = "SELECT email_usuario FROM usuarios WHERE id = ?";
$stmtCorreo = $pdo->prepare($sqlCorreo);
$stmtCorreo->execute([$_SESSION["user_id"]]);
$usuario = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

// Verifica que la consulta fue exitosa
if (!$usuario) {
    $_SESSION['error'] = "No se encontró el usuario.";
    header("Location: ../login.php");
    exit();
}

$correo = $usuario['email_usuario']; // Correo del usuario autenticado

// Verifica el valor del correo
// var_dump($correo); // Ahora esto debe mostrar el valor de $correo

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

$empleado_id = $empleado['id']; // ID real del empleado
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

if (isset($_SESSION['rechazo'])) {
    echo '<script>
            window.onload = function() {
                showNotification("rejected", "' . htmlspecialchars($_SESSION['rechazo']) . '");
            };
          </script>';
    unset($_SESSION['rechazo']);
}
  
?>

<!-- Contenido principal -->
<main class="pt-24 pb-24 px-8">
    <div  class="max-w-7xl mx-auto flex-grow">
        <!-- Filtros y Acciones -->
        <div class="bg-white rounded-2xl shadow-sm p-12 mb-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <!-- Encabezado -->
                <div class="flex-grow">
                    <h2 class="font-heading text-4xl text-center md:text-left font-bold text-gray-900 mb-3">Agenda
                        de Citas Pendientes</h2>
                    <p class="text-gray-600 max-w-2xl md:mx-0 mx-auto">Gestiona tus citas, consulta tu agenda y
                        organiza tu tiempo de manera eficiente.</p>
                </div>

                <div class="flex items-center space-x-4 flex-shrink-0">
                    <a class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1" href="/a_1/public/empleado/inicio.php">Ver
                        citas</a>
                    <a href="/a_1/public/empleado/agenda.php"
                        class="font-medium text-gray-500 hover:text-emerald-600 pb-1">Historial</a>
                </div>
            </div>
        </div>
        <div id="notification" class="fixed top-0 right-0 mt-8 mr-8 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg hidden">
            <p id="notification-text"></p>
        </div>
        <!-- Citas pendientes -->
        <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
            <h3 class="font-heading text-2xl font-bold mb-8">Mis citas pendientes</h3>

            <div class="space-y-4">
                <?php
                // Consultar todas las citas pendientes del empleado actual
                $sql = "SELECT c.*, s.tipo AS tipo, cl.nombre AS cliente_nombre, c.estado 
                        FROM citas c 
                        JOIN servicios s ON c.servicio_id = s.id 
                        JOIN cliente cl ON c.cliente_id = cl.id 
                        WHERE c.empleado_id = ? AND c.estado = 'pendiente' 
                        ORDER BY c.hora ASC";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$empleado_id]); // Usar el ID del empleado
                    $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($citas) > 0) {
                        foreach ($citas as $cita) {
                            $hora = date("H:i", strtotime($cita['hora']));
                            $estado_clase = 'bg-gray-100 text-gray-800'
                            ?>
                            <div class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h2 class="font-medium font-heading text-2xl font-bold mb-2"><?php echo htmlspecialchars($cita['cliente_nombre']); ?></h2>
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
                                        <?php if ($cita['estado'] == 'pendiente'): ?>
                                            <!-- Botón para aceptar cita -->
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
                                            
                                            <!-- Botón para rechazar cita -->
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
                                        <?php endif; ?>
                                    </div>

                                </div>
                                <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between">
                                    <span class="<?php echo $estado_clase; ?> text-xs px-3 py-1 rounded-full">
                                        Pendiente
                                    </span>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        ?>
                        <div class="text-center py-12 animate__animated animate__fadeIn">
                            <svg class="w-32 h-32 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-6 text-2xl font-medium text-gray-900">No tienes citas pendientes</h3>
                            <p class="mt-2 text-gray-600 max-w-md mx-auto">Tu agenda está libre. Aprovecha para organizar otras tareas o revisar el historial de citas anteriores.</p>
                        </div>
                        <?php
                    }
                } catch (PDOException $e) {
                    echo '<div class="text-center py-8">
                            <p class="text-red-600">Error al cargar las citas: ' . $e->getMessage() . '</p>
                          </div>';
                }
                ?>
            </div>
        </div>
    </div>
</main>

<?php include_once '../templates/footerempleado.php'; ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
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

