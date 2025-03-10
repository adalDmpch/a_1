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

// Obtener tipos de cortes
$sqlCortes = "SELECT * FROM cortes";
$cortes = $pdo->query($sqlCortes)->fetchAll(PDO::FETCH_ASSOC);

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $negocio_id = $_POST['negocio_id'];
    $servicio_id = $_POST['servicio_id'];
    $empleado_id = $_POST['empleado_id'];
    $corte_id = $_POST['corte_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $forma_de_pago_id = $_POST['payment'];
    $notas = $_POST['notas'];

    try {
        // Iniciar transacción
        $pdo->beginTransaction();
        
        $sqlInsert = "INSERT INTO citas (
            cliente_id, empleado_id, servicio_id, corte_id, 
            forma_de_pago_id, negocio_id, fecha, hora, 
            notas, estado, nombre_cliente
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'pendiente', ?)";
        
        $stmtInsert = $pdo->prepare($sqlInsert);
        $stmtInsert->execute([
            $cliente['id'],
            $empleado_id,
            $servicio_id,
            $corte_id,               // Correcto - corte_id
            $forma_de_pago_id,       // Correcto - forma_de_pago_id
            $negocio_id,            // Correcto - negocio_id
            $fecha,                 // Correcto - fecha
            $hora,                  // Correcto - hora
            $notas,                 // Correcto - notas
            $cliente['nombre']      // Correcto - nombre_cliente
        ]);
        
        // 2. Insertar en historial_cortes
        $sqlHistorial = "INSERT INTO historial_cortes (
            cliente_id, corte_id, fecha
        ) VALUES (?, ?, ?)";
        
        $stmtHistorial = $pdo->prepare($sqlHistorial);
        $stmtHistorial->execute([
            $cliente['id'],
            $corte_id,
            $fecha
        ]);
        
        // Confirmar transacción
        $pdo->commit();
        
        $_SESSION['success'] = "Cita reservada exitosamente!";
        header("Location: reservacion.php");
        exit();
    } catch (PDOException $e) {
        // Revertir transacción en caso de error
        $pdo->rollBack();
        $error = "Error al reservar la cita: " . $e->getMessage();
    }
}

include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>

<div class="lg:col-span-3 space-y-6">
    <div class="max-w-4xl mx-auto">
        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p><strong>Error:</strong> <?= $error ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-md" role="alert">
                <div class="flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p><strong>Éxito:</strong> <?= $_SESSION['success'] ?></p>
                </div>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 p-6">
                <h2 class="text-3xl font-bold text-white">Reserva tu cita</h2>
                <p class="text-emerald-100 mt-2">Completa el formulario para agendar tu próxima visita</p>
            </div>
            
            <div class="p-8">
                <form method="POST" class="space-y-8">
                    <!-- Selección de Negocio -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Información del servicio</h3>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <span>Selecciona el Negocio</span>
                                </label>
                                <select id="negocio_id" name="negocio_id" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                                    <option value="">Selecciona un negocio</option>
                                    <?php foreach ($negocios as $negocio): ?>
                                        <option value="<?= $negocio['id'] ?>"><?= $negocio['nombrenegocio'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Servicios (se actualiza con AJAX) -->
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Servicio</span>
                                </label>
                                <div id="servicios-container">
                                    <select id="servicio_id" name="servicio_id" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required disabled>
                                        <option value="">Primero selecciona un negocio</option>
                                    </select>
                                </div>
                            </div>
                            <!-- Selección de Corte -->
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"/>
                                    </svg>
                                    <span>Tipo de Corte</span>
                                </label>
                                <select id="corte_id" name="corte_id" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                                    <option value="">Selecciona un tipo de corte</option>
                                    <?php foreach ($cortes as $corte): ?>
                                        <option value="<?= $corte['id'] ?>"><?= $corte['tipo'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Empleados (se actualiza con AJAX) -->
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                    <span>Selecciona al Especialista</span>
                                </label>
                                <div id="empleados-container">
                                    <select id="empleado_id" name="empleado_id" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required disabled>
                                        <option value="">Primero selecciona un negocio</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha y Hora -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Fecha y hora</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Fecha</span>
                                </label>
                                <input type="date" name="fecha" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                            </div>

                            <div class="space-y-2">
                                <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Hora</span>
                                </label>
                                <input type="time" name="hora" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                            </div>
                        </div>
                    </div>

                    <!-- Método de Pago -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Método de Pago</h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                            <?php foreach ($metodosPago as $metodo): ?>
                                <label class="relative flex flex-col bg-white p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-emerald-500 transition-colors">
                                    <input type="radio" name="payment" value="<?= $metodo['id'] ?>" class="absolute top-3 right-3 h-4 w-4 accent-emerald-600" required>
                                    <div class="flex flex-col items-center justify-center p-2">
                                        <svg class="w-8 h-8 text-emerald-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                        </svg>
                                        <span class="text-sm font-medium"><?= $metodo['tipo'] ?></span>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Notas -->
                    <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Notas Adicionales</h3>
                        
                        <div class="space-y-2">
                            <label class="flex items-center gap-2 text-sm font-medium text-gray-700">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                </svg>
                                <span>Información adicional para el especialista</span>
                            </label>
                            <textarea name="notas" class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all h-32" placeholder="Escribe aquí cualquier detalle importante o requerimiento especial..."></textarea>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="pt-4">
                        <button type="submit" class="w-full px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white text-lg font-semibold rounded-xl hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Reservar Cita
                        </button>
                    </div>
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

// Añadir animación a las tarjetas de método de pago
const paymentCards = document.querySelectorAll('input[name="payment"]');
paymentCards.forEach(card => {
    card.addEventListener('change', function() {
        // Quitar selección anterior
        document.querySelectorAll('input[name="payment"]').forEach(input => {
            input.closest('label').classList.remove('border-emerald-500', 'bg-emerald-50');
        });
        
        // Añadir selección al actual
        this.closest('label').classList.add('border-emerald-500', 'bg-emerald-50');
    });
});
</script>

<?php
include_once '../templates/footercliente.php';
?>