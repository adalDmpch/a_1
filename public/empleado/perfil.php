<?php
session_start();
require '../../config/confg.php';

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

// Obtener el user_id desde la sesión
$user_id = $_SESSION['user_id'];

// Consulta para obtener los datos del empleado basado en usuario_id
$sql = "SELECT e.* FROM empleados e 
        INNER JOIN usuarios u ON e.id = u.empleado_id 
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontraron datos
if (!$empleado) {
    die("No se encontró información en la tabla empleados para este usuario.");
}


$pageTitle = 'Perfil - Noir Elite - Barbería & Estilistas';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarempleado.php';

?>

<main class="pt-28 pb-16 max-w-6xl mx-auto px-4">
    <?php if (isset($updateSuccess)): ?>
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
            <p>¡Perfil actualizado correctamente!</p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-2xl shadow">
        <!-- Portada y foto de perfil -->
        <div class="relative h-48 md:h-64 bg-gradient-to-r from-emerald-600 to-emerald-400 rounded-t-2xl">
            <div class="absolute -bottom-16 left-6 md:left-8">
                <div class="h-32 w-32 rounded-full border-4 border-white bg-gray-200 overflow-hidden">
                    <img src="../uploads/<?= htmlspecialchars(basename($empleado['foto_de_perfil'] ?? 'default.png')) ?>" alt="Foto de perfil"
                            class="h-full w-full object-cover">
                </div>
            </div>
        </div>

        <!-- Información del perfil -->
        <div class="pt-20 px-6 md:px-8 pb-8">
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h2 id="displayName" class="text-2xl font-heading font-bold"><?= htmlspecialchars($empleado['nombreempleado'] ?? 'Nombre no disponible') ?></h2>
                    </div>
                    <a href="/a_1/public/empleado/editar_Perfil.php" 
                        class="px-5 py-2 bg-emerald-600 text-white rounded-full hover:bg-emerald-700 transition-all">
                        Editar Perfil
                    </a>
                </div>

                <div class="space-y-4">
                    <div>
                        <h3 class="text-2xl font-heading font-bold">Información Personal</h3>
                        <div class="grid md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <p class="text-gray-500 text-sm">Teléfono</p>
                                <p id="displayPhone" class="text-gray-700"><?= htmlspecialchars($empleado['phoneempleado'] ?? 'Teléfono no disponible') ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Correo electrónico</p>
                                <p id="displayEmail" class="text-gray-700"><?= htmlspecialchars($empleado['email_empleado'] ?? 'Email no disponible') ?></p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Edad</p>
                                <p id="displayEdad" class="text-gray-700"><?= htmlspecialchars($empleado['edad'] ?? 'No especificada') ?> años</p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold mb-2">Descripción</h3>
                        <p id="displayDescripcion" class="text-gray-700">
                            <?= htmlspecialchars($empleado['descripcion'] ?? 'Descripción no disponible') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
include_once '../templates/footerempleado.php';
?>
<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

