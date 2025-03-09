<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}
// Obtener el user_id desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del cliente basado en usuario_id
$sql = "SELECT e.* FROM cliente e 
        INNER JOIN usuarios u ON e.id = u.cliente_id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);


// Verificar si se encontraron datos
if (!$cliente) {
    die("No se encontró información en la tabla cliente para este usuario.");
}

$pageTitle = 'Editar Perfil - Noir Elite';
include_once '../templates/headercliente.php';
include_once '../templates/navbaredit.php';
?>


<div class="bg-white shadow-xl rounded-2xl overflow-hidden">
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-6">
        <h2 class="text-2xl font-bold">Editar Perfil</h2>
        <p class="text-sm opacity-80">Actualiza tu información personal</p>
    </div>

    <form action="../../actions/actualizar_perfil.php" method="POST" class="p-6 sm:p-8 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nombre Completo -->
            <div>
                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                <input type="text" name="nombre" id="nombre" 
                        value="<?php echo htmlspecialchars($cliente['nombre']); ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                        required>
            </div>

            <!-- Correo Electrónico -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                <input type="email" name="email" id="email" 
                        value="<?php echo htmlspecialchars($cliente['email_cliente']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                        required>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Teléfono -->
            <div>
                <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Número de Teléfono</label>
                <input type="tel" name="telefono" id="telefono" 
                        value="<?php echo htmlspecialchars($cliente['phone']); ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                        required>
            </div>

            <!-- Fecha de Nacimiento -->
            <div>
                <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" 
                        value="<?php echo htmlspecialchars($cliente['fecha']); ?>" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all"
                        required>
            </div>
        </div>


        <!-- Cambiar Foto de Perfil -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
            <div class="flex items-center space-x-4">
            <img src="../uploads/<?= htmlspecialchars(basename($cliente['foto_de_perfil'] ?? 'default.png')) ?>" alt="Foto de perfil"
            class="w-40 h-40 rounded-full object-cover mb-6 shadow-xl border-4 border-emerald-100">
                <input type="file" name="foto_perfil" accept="image/*" 
                        class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>
        </div>

        <!-- Botones de Acción -->
        <div class="flex justify-end space-x-4 mt-6">
            <a href="../cliente/perfil.php" class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-all">
                Cancelar
            </a>
            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<?php
include_once '../templates/footercliente.php';
?>