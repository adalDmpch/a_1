<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT cliente.* FROM cliente 
                          INNER JOIN usuarios ON cliente.id = usuarios.cliente_id 
                          WHERE usuarios.id = ?");
    $stmt->execute([$user_id]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cliente) {
        throw new Exception("Perfil no encontrado");
    }

    // Manejar mensajes de éxito/error
    $success = $_SESSION['success'] ?? null;
    $error = $_SESSION['error'] ?? null;
    unset($_SESSION['success'], $_SESSION['error']);

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

$pageTitle = 'Editar Perfil - Noir Elite';
include_once '../templates/headercliente.php';
include_once '../templates/navbarcliente.php';
include_once '../templates/navbarclient.php';
?>

<div class="lg:col-span-3 space-y-6">
    <div class="max-w-4xl mx-auto space-y-8">
        <?php if ($success): ?>
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-lg animate-fade-in">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm text-green-700 font-medium"><?= $success ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($error): ?>
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg animate-fade-in">
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="ml-3 text-sm text-red-700 font-medium"><?= $error ?></p>
            </div>
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 hover:shadow-3xl">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white px-8 py-6">
                <h2 class="text-3xl font-bold tracking-tight">Editar Perfil</h2>
                <p class="mt-1 text-emerald-100 opacity-90">Actualiza tu información personal en Noir Elite</p>
            </div>

            <form action="../../actions/actualizar_perfil.php" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                <input type="hidden" name="cliente_id" value="<?= $cliente['id'] ?>">

                <div class="space-y-8">
                    <!-- Sección Información Básica -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-l-4 border-emerald-500 pl-3">Información Personal</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nombre -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="text" name="nombre" id="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-gray-400"
                                           placeholder="Ej: Juan Pérez"
                                           required>
                                    <div class="absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($cliente['email_cliente']) ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-gray-400"
                                           placeholder="ejemplo@email.com"
                                           required>
                                    <div class="absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Contacto -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-l-4 border-emerald-500 pl-3">Datos de Contacto</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Teléfono -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Teléfono</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="tel" name="telefono" id="telefono" value="<?= htmlspecialchars($cliente['phone']) ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all placeholder-gray-400"
                                           placeholder="Ej: 123456789"
                                           pattern="[0-9]{9,15}"
                                           required>
                                    <div class="absolute inset-y-0 right-3 flex items-center">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>

                            <!-- Fecha Nacimiento -->
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Fecha de Nacimiento</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="<?= htmlspecialchars($cliente['fecha']) ?>"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all text-gray-700"
                                           max="<?= date('Y-m-d', strtotime('-18 years')) ?>"
                                           required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección Foto de Perfil -->
                    <div class="space-y-6">
                        <h3 class="text-xl font-semibold text-gray-800 border-l-4 border-emerald-500 pl-3">Imagen de Perfil</h3>
                        
                        <div class="flex flex-col sm:flex-row items-center gap-8">
                            <div class="relative group cursor-pointer">
                                <img src="<?= getProfileImage($cliente['foto_de_perfil']) ?>" 
                                     alt="Foto de perfil"
                                     class="w-48 h-48 rounded-full object-cover shadow-xl border-4 border-emerald-50 transform transition-all duration-300 group-hover:scale-105 group-hover:border-emerald-100">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-full transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-12 h-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="flex-1 w-full">
                                <div class="border-2 border-dashed border-gray-300 rounded-2xl p-6 transition-all duration-300 hover:border-emerald-500 hover:bg-gray-50">
                                    <div class="text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <div class="mt-4 text-sm text-gray-600">
                                            <label class="relative cursor-pointer">
                                                <span class="font-medium text-emerald-600 hover:text-emerald-500">Sube una foto</span>
                                                <input type="file" name="foto_perfil" accept="image/*" class="sr-only">
                                            </label>
                                            <p class="mt-1">o arrastra y suelta</p>
                                        </div>
                                        <p class="mt-2 text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-end gap-4 mt-12">
                    <a href="perfil.php" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-lg text-white bg-emerald-600 hover:bg-emerald-700 transition-all duration-200 transform hover:scale-[1.02]">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                        </svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<?php
include_once '../templates/footercliente.php';

// Función auxiliar para manejar imágenes
function getProfileImage($path) {
    if ($path && file_exists("../../uploads/" . basename($path))) {
        return "../uploads/" . htmlspecialchars(basename($path));
    }
    return "../assets/default-profile.jpg";
}
?>