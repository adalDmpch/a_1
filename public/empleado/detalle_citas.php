<?php
// Se requiere el archivo de configuración
require '../../config/confg.php';

// Asegurarse de que el usuario tiene permisos
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../public/login.php");
    exit();
}

// Obtener el ID de la cita
$cita_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consulta SQL para obtener los detalles de la cita
$sql = "SELECT 
            c.id, 
            c.fecha, 
            c.hora, 
            c.estado,
            c.notas, 
            cl.nombre AS nombre, 
            cl.phone AS phone, 
            cl.email_cliente AS cliente_email,
            s.tipo AS servicio, 
            s.duracion, 
            s.precio,
            e.nombreempleado AS empleado_id
        FROM citas c
        INNER JOIN cliente cl ON c.cliente_id = cl.id
        INNER JOIN servicios s ON c.servicio_id = s.id
        INNER JOIN empleados e ON c.empleado_id = e.id
        WHERE c.id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$cita_id]);
$cita = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró la cita
if (!$cita) {
    exit('Cita no encontrada.');
}

?>

<!-- Cabecera del modal -->
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold text-gray-900">Detalles de la cita </h2>
    <button onclick="cerrarModal()" class="text-gray-500 hover:text-gray-700">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</div>

<!-- Estado y fecha -->
<div class="flex flex-col md:flex-row justify-between mb-6">
    <div>
        <div class="text-sm text-gray-500 mb-1">Estado:</div>
        <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full 
            <?php 
                // Cambiar el color según el estado
                if ($cita['estado'] === 'confirmada') {
                    echo 'bg-green-100 text-green-800'; // Verde para confirmada
                } elseif ($cita['estado'] === 'rechazada') {
                    echo 'bg-red-100 text-red-800'; // Rojo para rechazada
                } elseif ($cita['estado'] === 'completada') {
                    echo 'bg-orange-100 text-orange-800'; // Naranja para completada
                } elseif ($cita['estado'] === 'no_asistio') {
                    echo 'bg-yellow-100 text-yellow-800'; // Amarillo para no asistió
                } else {
                    echo 'bg-gray-100 text-gray-800'; // Gris en caso de un estado desconocido
                }
            ?>">
            <?php echo $cita['estado']; ?>
        </span>
    </div>
    <div class="mt-4 md:mt-0 text-right">
        <div class="text-sm text-gray-500 mb-1">Fecha y hora:</div>
        <div class="text-lg font-medium"><?php echo $cita['fecha']; ?> a las <?php echo $cita['hora']; ?></div>
    </div>
</div>


<!-- Información del cliente y servicio -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
    <!-- Información del cliente -->
    <div>
        <h3 class="text-lg font-medium text-gray-900 mb-4">Información del cliente</h3>
        <div class="flex items-center mb-4">
            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium text-xl">
                <?php echo substr($cita['nombre'], 0, 1) . substr(explode(' ', $cita['nombre'])[1], 0, 1); ?>
            </div>
            <div class="ml-4">
                <div class="text-xl font-medium text-gray-900"><?php echo $cita['nombre']; ?></div>
                <div class="text-gray-500"><?php echo $cita['phone']; ?></div>
                <div class="text-gray-500"><?php echo $cita['cliente_email']; ?></div>
            </div>
        </div>
    </div>
    
    <!-- Información del servicio -->
    <div >
        <h3 class="text-lg TEXT-CENTER font-medium text-gray-900 mb-4 ">Detalles del servicio</h3>
        <div class="space-y-3 ">
            <div class="flex justify-between">
                <div class="text-gray-500">Servicio:</div>
                <div class="font-medium text-right"><?php echo $cita['servicio']; ?></div>
            </div>
            <div class="flex justify-between">
                <div class="text-gray-500">Estilista:</div>
                <div class="font-medium text-right"><?php echo $cita['empleado_id']; ?></div>
            </div>
            <div class="flex justify-between">
                <div class="text-gray-500">Duración:</div>
                <div class="font-medium text-right"><?php echo $cita['duracion']; ?></div>
            </div>
            <div class="flex justify-between">
                <div class="text-gray-500">Precio:</div>
                <div class="font-medium text-right"><?php echo $cita['precio']; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Notas -->
<div class="mb-8">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Notas del servicio</h3>
    <div class="bg-gray-50 p-4 rounded-lg">
        <p class="text-gray-700"><?php echo $cita['notas']; ?></p>
    </div>
</div>

<!-- Acciones -->
<div class="flex justify-end space-x-3 pt-4 border-t">
    <form action="../../actions/actulizar_modal.php" method="POST">
        <input type="hidden" name="cita_id" value="<?php echo $cita['id']; ?>">

        <!-- Mostrar solo el botón "Cerrar" si el estado no es "confirmada" -->
        <?php if ($cita['estado'] !== 'confirmada'): ?>
            <button type="button" onclick="cerrarModal()" class="bg-gray-200 text-gray-800 px-6 py-2 rounded-lg hover:bg-gray-300">
                Cerrar
            </button>
        <?php endif; ?>

        <!-- Mostrar los botones "Completar Cita" y "No asistió" solo si el estado no es "rechazada", "completada" o "no_asistio" -->
        <?php if (!in_array($cita['estado'], ['rechazada', 'completada', 'no_asistio'])): ?>
            <button type="submit" name="estado" value="completada" class="bg-emerald-600 text-white px-6 py-3 rounded-lg hover:bg-emerald-700">
                Completar Cita
            </button>
            <button type="submit" name="estado" value="no_asistio" class="bg-orange-600 text-white px-6 py-3 rounded-lg hover:bg-orange-700">
                No asistió
            </button>
        <?php endif; ?>
    </form>


    <?php 
        if (isset($mensaje)) {
            echo '<p>' . $mensaje . '</p>';
        }
    ?>
</div>

