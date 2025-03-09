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

 <!-- Contenido principal -->
 <main class="pt-24 pb-16 flex-grow">
        <div class="max-w-5xl mx-auto px-4">
            <?php if (isset($updateSuccess)): ?>
                <div id="successAlert" class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 mb-6 rounded-lg shadow-sm relative" role="alert">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="font-medium">¡Perfil actualizado correctamente!</p>
                    </div>
                    <button onclick="document.getElementById('successAlert').style.display='none'" class="absolute top-4 right-4 text-emerald-600 hover:text-emerald-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Portada y foto de perfil -->
                <div class="relative">
                    <div class="h-56 md:h-72 bg-gradient-to-r from-emerald-700 to-emerald-500 flex items-end">
                        <div class="absolute left-0 right-0 top-0 h-full bg-black opacity-10"></div>
                        <div class="absolute top-4 right-4">
                            <a href="../empleado/editar_Perfil.php" 
                                class="px-4 py-2 bg-white bg-opacity-90 text-emerald-700 rounded-lg shadow-md hover:bg-white transition-custom text-sm font-medium flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                                Editar Perfil
                            </a>
                        </div>
                    </div>
                    <div class="absolute -bottom-16 left-8 md:left-10">
                        <div class="h-32 w-32 rounded-full ring-4 ring-white bg-white shadow-md overflow-hidden">
                            <img src="../uploads/<?= htmlspecialchars(basename($empleado['foto_de_perfil'] ?? 'default.png')) ?>" alt="Foto de perfil"
                                class="h-full w-full object-cover" onchange="previewImage(event)">
                        </div>
                    </div>
                </div>

                <!-- Información del perfil -->
                <div class="pt-20 px-6 md:px-10 pb-8">
                    <div class="space-y-8">
                        <div>
                            <h2 id="displayName" class="text-2xl font-heading font-bold text-gray-800">
                                <?= htmlspecialchars($empleado['nombreempleado'] ?? 'Nombre no disponible') ?></h2>
                            <p class="text-emerald-600 font-medium mt-1">Estilista profesional</p>
                        </div>

                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-heading font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Información Personal</h3>
                                <div class="grid md:grid-cols-3 gap-6">
                                    <div class="bg-gray-50 rounded-lg p-4 flex flex-col">
                                        <span class="text-gray-500 text-xs uppercase font-medium tracking-wide mb-1">Teléfono</span>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span id="displayPhone" class="text-gray-800 font-medium"><?= htmlspecialchars($empleado['phoneempleado'] ?? 'Teléfono no disponible') ?></span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 flex flex-col">
                                        <span class="text-gray-500 text-xs uppercase font-medium tracking-wide mb-1">Correo electrónico</span>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                            <span id="displayEmail" class="text-gray-800 font-medium"><?= htmlspecialchars($empleado['email_empleado'] ?? 'Email no disponible') ?></span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 flex flex-col">
                                        <span class="text-gray-500 text-xs uppercase font-medium tracking-wide mb-1">Edad</span>
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            <span id="displayEdad" class="text-gray-800 font-medium"><?= htmlspecialchars($empleado['edad'] ?? 'No especificada') ?> años</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-heading font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Sobre mí</h3>
                                <div class="bg-gray-50 rounded-lg p-5">
                                    <p id="displayDescripcion" class="text-gray-700 leading-relaxed">
                                        <?= htmlspecialchars($empleado['descripcion'] ?? 'Descripción no disponible') ?>
                                    </p>
                                </div>
                            </div>
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

