<?php
require '../../config/confg.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener datos del cliente
$sql = "SELECT e.* FROM cliente e 
        INNER JOIN usuarios u ON e.id = u.cliente_id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("No se encontró información del cliente.");
}

// Obtener negocios
$sqlNegocios = "SELECT * FROM negocio";
$negocios = $pdo->query($sqlNegocios)->fetchAll(PDO::FETCH_ASSOC);

// Obtener métodos de pago
$sqlMetodosPago = "SELECT * FROM metodo_de_pago";
$metodosPago = $pdo->query($sqlMetodosPago)->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $negocio_id = $_POST['negocio_id'];
    $servicio_id = $_POST['servicio_id'];
    $empleado_id = $_POST['empleado_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $forma_de_pago_id = $_POST['payment'];
    $notas = $_POST['notas'];

    try {
        $sqlInsert = "INSERT INTO citas (
            cliente_id, empleado_id, servicio_id, 
            forma_de_pago_id, negocio_id, fecha, hora, 
            notas, estado, nombre_cliente
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?)";
        
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            $cliente['id'],
            $empleado_id,
            $servicio_id,
            $forma_de_pago_id,
            $negocio_id,
            $fecha,
            $hora,
            $notas,
            $cliente['nombre']
        ]);
        
        $_SESSION['success'] = "Cita reservada exitosamente!";
        header("Location: reservacion.php");
        exit();
    } catch (PDOException $e) {
        $error = "Error al reservar la cita: " . $e->getMessage();
    }
}

include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>

<div class="lg:col-span-3 space-y-6">
    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="dashboard-card p-6">
        <h2 class="text-3xl font-light text-gray-800 mb-8">Reserva tu cita</h2>
        <form method="POST">
            <!-- Selección de Negocio -->
            <div class="space-y-2 mb-6">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Selecciona el Negocio
                </label>
                <select id="negocio_id" name="negocio_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required>
                    <option value="">Selecciona un negocio</option>
                    <?php foreach ($negocios as $negocio): ?>
                        <option value="<?= $negocio['id'] ?>"><?= $negocio['nombrenegocio'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Servicios (se actualiza con AJAX) -->
            <div class="space-y-2 mb-6">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    Servicio
                </label>
                <div id="servicios-container">
                    <select id="servicio_id" name="servicio_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required disabled>
                        <option value="">Primero selecciona un negocio</option>
                    </select>
                </div>
            </div>

            <!-- Empleados (se actualiza con AJAX) -->
            <div class="space-y-2 mb-6">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Selecciona al Especialista
                </label>
                <div id="empleados-container">
                    <select id="empleado_id" name="empleado_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required disabled>
                        <option value="">Primero selecciona un negocio</option>
                    </select>
                </div>
            </div>

            <!-- Fecha y Hora -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Fecha
                    </label>
                    <input type="date" name="fecha" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Hora
                    </label>
                    <input type="time" name="hora" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required>
                </div>
            </div>

            <!-- Método de Pago -->
            <div class="space-y-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900">Método de Pago</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach ($metodosPago as $metodo): ?>
                        <label class="relative flex flex-col bg-white p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                            <input type="radio" name="payment" value="<?= $metodo['id'] ?>" class="absolute top-3 right-3" required>
                            <span class="text-sm font-medium"><?= $metodo['tipo'] ?></span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Notas -->
            <div class="space-y-2 mb-6">
                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                    Notas Adicionales
                </label>
                <textarea name="notas" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg h-32"></textarea>
            </div>

            <button type="submit" class="w-full px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-500 text-white rounded-xl hover:from-emerald-600 hover:to-teal-600 transition-all">
                Reservar Cita
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('negocio_id').addEventListener('change', function() {
    const negocioId = this.value;
    
    // Cargar servicios
    fetch(`get_servicios.php?negocio_id=${negocioId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('servicios-container').innerHTML = data;
        });

    // Cargar empleados
    fetch(`get_empleados.php?negocio_id=${negocioId}`)
        .then(response => response.text())
        .then(data => {
            document.getElementById('empleados-container').innerHTML = data;
        });
});
</script>

<?php
include_once '../templates/footercliente.php';
?>