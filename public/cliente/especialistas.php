<?php
require '../../config/confg.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT e.*, n.nombrenegocio, n.id as negocio_id 
        FROM empleados e
        JOIN negocio n ON e.negocio_id = n.id";
$empleados = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>

<main class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold text-center mb-8">Nuestros Especialistas</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($empleados as $empleado): ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                <div class="h-48 bg-gray-100 overflow-hidden">
                    <img src="<?= $empleado['foto_de_perfil'] ? htmlspecialchars($empleado['foto_de_perfil']) : '../assets/default-avatar.jpg' ?>" 
                         alt="<?= htmlspecialchars($empleado['nombreempleado']) ?>"
                         class="w-full h-full object-cover">
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2"><?= htmlspecialchars($empleado['nombreempleado']) ?></h3>
                    <p class="text-gray-600 mb-4"><?= htmlspecialchars($empleado['descripcion']) ?></p>
                    
                    <div class="flex items-center justify-between">
                        <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm">
                            <?= htmlspecialchars($empleado['nombrenegocio']) ?>
                        </span>
                        <a href="reservacion.php?empleado_id=<?= $empleado['id'] ?>&negocio_id=<?= $empleado['negocio_id'] ?>"
                           class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors">
                            Reservar
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php
include_once '../templates/footercliente.php';
?>