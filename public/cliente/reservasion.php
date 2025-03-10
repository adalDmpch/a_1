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
            $corte_id,
            $forma_de_pago_id,
            $negocio_id,
            $fecha,
            $hora,
            $notas,
            $cliente['nombre']
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
        header("Location: reservasion.php");
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
            
            <!-- Barra de progreso para los pasos -->
            <div class="px-8 pt-6">
                <div class="flex justify-between mb-4">
                    <div class="step-indicator active" id="step-indicator-1">
                    </div>
                    <div class="step-line" id="step-line-1-2"></div>
                    <div class="step-indicator" id="step-indicator-2">
                    </div>
                    <div class="step-line" id="step-line-2-3"></div>
                    <div class="step-indicator" id="step-indicator-3">
                    </div>
                </div>
            </div>
            
            <div class="p-8">
                <form method="POST" id="appointment-form" class="space-y-8">
                    <!-- Paso 1: Selección de Negocio y Servicio -->
                    <div id="step-1" class="step-content">
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
                            </div>
                            
                            <div class="flex justify-end mt-6">
                                <button type="button" id="next-step-1" class="px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                                    Siguiente</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 2: Selección de Especialista -->
                    <div id="step-2" class="step-content hidden">
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">Selección del Especialista</h3>
                            
                            <div id="empleados-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <!-- Los empleados se cargarán aquí con AJAX -->
                                <div class="text-center text-gray-500 py-8 col-span-full">
                                    Por favor selecciona un negocio primero para ver los especialistas disponibles
                                </div>
                            </div>
                            
                            <!-- Campo oculto para guardar el empleado seleccionado -->
                            <input type="hidden" id="empleado_id" name="empleado_id" required>
                            
                            <div class="flex justify-between mt-6">
                                <button type="button" id="prev-step-2" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                          Anterior
                                </button>
                                <button type="button" id="next-step-2" class="px-6 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-colors">
                                    Siguiente</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Paso 3: Información adicional (Fecha, Hora, Método de Pago, Notas) -->
                    <div id="step-3" class="step-content hidden">
                        <!-- Fecha y Hora -->
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
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
                        <div class="bg-gray-50 p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
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

                        <div class="flex justify-between mt-6">
                            <button type="button" id="prev-step-3" class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors">
                                Anterior
                            </button>
                            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-600 hover:to-teal-700 transition-all shadow-lg flex items-center">

                                Confirmar Reserva
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.step-indicator {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    z-index: 1;
}

.step-number {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background-color: #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    color: #64748b;
    margin-bottom: 8px;
    transition: all 0.3s ease;
}

.step-title {
    font-size: 10px;
    color: #64748b;
    font-weight: 500;
    transition: all 0.3s ease;
}

.step-indicator.active .step-number {
    background-color: #10b981;
    color: white;
}

.step-indicator.active .step-title {
    color: #10b981;
    font-weight: 600;
}

.step-indicator.completed .step-number {
    background-color: #10b981;
    color: white;
}

.step-line {
    flex-grow: 1;
    height: 0px;
    background-color: #e2e8f0;
    margin-top: 15px;
    transition: all 0.3s ease;
}

.step-line.active {
    background-color: #10b981;
}

/* Estilos para las tarjetas de especialistas */
.employee-card {
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    padding: 1rem;
    cursor: pointer;
    transition: all 0.2s ease;
    background-color: white;
}

.employee-card:hover {
    border-color: #10b981;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.employee-card.selected {
    border-color: #10b981;
    background-color: #ecfdf5;
}

.employee-photo {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    margin-bottom: 0.75rem;
}

.employee-name {
    font-weight: 600;
    font-size: 1rem;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.employee-role {
    font-size: 0.875rem;
    color: #6b7280;
    margin-bottom: 0.75rem;
}

.employee-specialty {
    display: inline-block;
    background-color: #f3f4f6;
    border-radius: 9999px;
    padding: 0.25rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: #4b5563;
}
</style>

<script>
// Funciones para manejar la navegación entre pasos
document.addEventListener('DOMContentLoaded', function() {
    // Elementos de pasos
    const step1 = document.getElementById('step-1');
    const step2 = document.getElementById('step-2');
    const step3 = document.getElementById('step-3');
    
    // Indicadores de pasos
    const stepIndicator1 = document.getElementById('step-indicator-1');
    const stepIndicator2 = document.getElementById('step-indicator-2');
    const stepIndicator3 = document.getElementById('step-indicator-3');
    
    // Líneas entre pasos
    const stepLine12 = document.getElementById('step-line-1-2');
    const stepLine23 = document.getElementById('step-line-2-3');
    
    // Botones de navegación
    const nextStep1 = document.getElementById('next-step-1');
    const prevStep2 = document.getElementById('prev-step-2');
    const nextStep2 = document.getElementById('next-step-2');
    const prevStep3 = document.getElementById('prev-step-3');
    
// Corrige la cadena de promesas en el evento change del negocio_id
document.getElementById('negocio_id').addEventListener('change', function() {
    const negocioId = this.value;
    const serviciosContainer = document.getElementById('servicios-container');
    const empleadosGrid = document.getElementById('empleados-grid');
    
    if (!negocioId) {
        serviciosContainer.innerHTML = `
            <select id="servicio_id" name="servicio_id" 
                class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" 
                required disabled>
                <option value="">Primero selecciona un negocio</option>
            </select>`;
            
        empleadosGrid.innerHTML = `
            <div class="text-center text-gray-500 py-8 col-span-full">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                <p class="mt-4 text-gray-600 font-medium">Selecciona un negocio para ver los especialistas disponibles</p>
            </div>`;
        return;
    }
    
    // Cargar servicios
    fetch(`get_servicios.php?negocio_id=${negocioId}`)
        .then(response => {
            if (!response.ok) throw new Error('Error de red');
            return response.text();
        })
        .then(data => serviciosContainer.innerHTML = data)
        .catch(error => {
            serviciosContainer.innerHTML = `
                <select class="w-full p-3 border border-red-300 bg-red-50 rounded-lg" disabled>
                    <option>Error al cargar servicios</option>
                </select>`;
        });

    // Cargar empleados
    fetch(`get_empleados_cards.php?negocio_id=${negocioId}`)
        .then(response => {
            if (!response.ok) throw new Error('Error de red');
            return response.text();
        })
        .then(data => {
            empleadosGrid.innerHTML = data;
            
            // Vincular eventos de selección
            const employeeCards = document.querySelectorAll('.employee-card');
            employeeCards.forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.employee-card').forEach(c => {
                        c.classList.remove('selected');
                    });
                    this.classList.add('selected');
                    document.getElementById('empleado_id').value = this.dataset.employeeId;
                });
            });
        })
        .catch(error => {
            empleadosGrid.innerHTML = `
                <div class="text-center text-red-500 py-8 col-span-full">
                    <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="mt-2">Error al cargar especialistas</p>
                </div>`;
        });
});
    // Función para mostrar el modal con un mensaje personalizado
function showModal(message) {
    // Si el modal ya existe, solo actualiza el mensaje y lo muestra
    let modal = document.getElementById('warningModal');
    if (!modal) {
        // Crear el modal dinámicamente
        modal = document.createElement('div');
        modal.id = 'warningModal';
        modal.classList = 'fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 transition-opacity duration-300 ease-in-out';
        modal.innerHTML = `
            <div class="bg-white p-6 rounded-xl shadow-2xl max-w-sm text-center transform scale-95 transition-transform duration-300 ease-in-out">
                <svg class="w-12 h-12 mx-auto text-red-500 animate-bounce" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p id="modalMessage" class="text-gray-800 mt-4 font-semibold">${message}</p>
                <button id="closeModal" class="mt-6 px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Aceptar</button>
            </div>
        `;
        document.body.appendChild(modal);
        
        // Animación de entrada
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 50);

        // Evento para cerrar el modal
        document.getElementById('closeModal').addEventListener('click', function() {
            // Animación de salida antes de remover el modal
            modal.classList.add('opacity-0');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => modal.remove(), 300);
        });
    } else {
        // Si ya existe, solo actualiza el mensaje
        document.getElementById('modalMessage').innerText = message;
        modal.classList.remove('hidden', 'opacity-0');
        modal.querySelector('div').classList.remove('scale-95');
    }
}

// Evento para pasar al paso 2
nextStep1.addEventListener('click', function() {
    const negocioId = document.getElementById('negocio_id').value;
    const servicioId = document.getElementById('servicio_id').value;
    const corteId = document.getElementById('corte_id').value;
    
    if (!negocioId || !servicioId || !corteId) {
        showModal('Por favor completa todos los campos del paso 1');
        return;
    }
    
    step1.classList.add('hidden');
    step2.classList.remove('hidden');
    
    stepIndicator1.classList.add('completed');
    stepIndicator2.classList.add('active');
    stepLine12.classList.add('active');
});

// Evento para regresar al paso 1
prevStep2.addEventListener('click', function() {
    step2.classList.add('hidden');
    step1.classList.remove('hidden');
    
    stepIndicator2.classList.remove('active');
    stepLine12.classList.remove('active');
});

// Evento para pasar al paso 3
nextStep2.addEventListener('click', function() {
    const empleadoId = document.getElementById('empleado_id').value;
    
    if (!empleadoId) {
        showModal('Por favor selecciona un especialista');
        return;
    }
    
    step2.classList.add('hidden');
    step3.classList.remove('hidden');
    
    stepIndicator2.classList.add('completed');
    stepIndicator3.classList.add('active');
    stepLine23.classList.add('active');
});

// Evento para regresar al paso 2
prevStep3.addEventListener('click', function() {
    step3.classList.add('hidden');
    step2.classList.remove('hidden');
    
    stepIndicator3.classList.remove('active');
    stepLine23.classList.remove('active');
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
});
</script>
<?php
include_once '../templates/footercliente.php';
?>
