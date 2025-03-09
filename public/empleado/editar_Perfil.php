<?php
require '../../config/confg.php';
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener la información actual del empleado y su email en usuarios
$sql = "SELECT e.*, u.email_usuario FROM empleados e
        LEFT JOIN usuarios u ON e.id = u.empleado_id
        WHERE u.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    die("No se encontró información en la tabla empleados para este usuario.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["update_profile"])) {
    $nombre = $_POST["nombreempleado"] ?? '';
    $telefono = $_POST["phoneempleado"] ?? '';
    $edad = $_POST["edad"] ?? '';
    $descripcion = $_POST["descripcion"] ?? '';
    $nuevo_email = $_POST["email_empleado"] ?? '';
    $foto_perfil = $empleado["foto_de_perfil"]; // Mantener la imagen anterior por defecto

    try {
        $pdo->beginTransaction();
    
        // Manejo de imagen de perfil (solo si se sube una nueva)
        if (!empty($_FILES['foto_de_perfil']['name']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
            $foto_nombre = basename($_FILES['foto_de_perfil']['name']);
            $uploads_dir = '../uploads/'; // Usamos la carpeta "public/uploads"

            // Verificar si la carpeta 'uploads' existe dentro de 'public'
            // Ya no la creamos si existe
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true);
            }  
             
            $foto_destino = $uploads_dir . $foto_nombre;

            if (move_uploaded_file($_FILES['foto_de_perfil']['tmp_name'], $foto_destino)) {
                $foto_perfil = $uploads_dir . $foto_nombre; // Solo cambiar si hay una nueva imagen
            } else {
                throw new Exception("Error al guardar la imagen.");
            }
        }

        // Actualizar empleados, asegurando que foto_de_perfil solo se actualiza si se subió una nueva imagen
        $sql_update_empleado = "UPDATE empleados SET nombreempleado = ?, phoneempleado = ?, edad = ?, descripcion = ?". 
                               (!empty($_FILES['foto_de_perfil']['name']) ? ", foto_de_perfil = ?" : "") . 
                               " WHERE id = ?";
        $params = [$nombre, $telefono, $edad, $descripcion];

        if (!empty($_FILES['foto_de_perfil']['name'])) {
            $params[] = $foto_perfil;
        }
        $params[] = $empleado["id"];

        $stmt = $pdo->prepare($sql_update_empleado);
        $stmt->execute($params);
    
        // Desvincular y actualizar email en empleados y usuarios
        $sql_unlink_email = "UPDATE empleados SET email_empleado = NULL WHERE email_empleado = ?";
        $pdo->prepare($sql_unlink_email)->execute([$empleado["email_usuario"]]);

        $sql_update_usuario = "UPDATE usuarios SET email_usuario = ? WHERE email_usuario = ?";
        $pdo->prepare($sql_update_usuario)->execute([$nuevo_email, $empleado["email_usuario"]]);

        $sql_relink_email = "UPDATE empleados SET email_empleado = ? WHERE id = ?";
        $pdo->prepare($sql_relink_email)->execute([$nuevo_email, $empleado["id"]]);

        $pdo->commit();
        echo "<script>alert('Perfil actualizado correctamente'); window.location.href='perfil.php';</script>";
    
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Error al actualizar el perfil: " . $e->getMessage());
    }
}

    $pageTitle = 'Editar Perfil - Noir Elite - Barbería & Estilistas';
    include_once '../templates/headeremleado.php';
    include_once '../templates/navbarsalir.php';

    ?>

<!-- Main content -->
<main class="pt-10  pb-10 max-w-5xl mx-auto px-4">
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <!-- Profile header with green background -->
        <div class="relative">
            <div class="bg-emerald-500 h-48 rounded-t-xl"></div>

            <div class="relative">
                <div class="absolute -bottom-12 left-8 group">
                    <img id="preview" 
                            src="<?= !empty($empleado['foto_de_perfil']) ? $empleado['foto_de_perfil'] : '../public/uploads/default.png' ?>" 
                            alt="Foto de perfil" 
                            class="h-32 w-32 rounded-full object-cover border-4 border-white">
                    
                        <label for="foto_de_perfil" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full transition-all duration-200 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                </div>
            </div>
        </div>

        <!-- Form container -->
        <div class="pt-16 px-6 pb-8">
            <form id="profileForm" method="POST" action="" enctype="multipart/form-data">
                <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" class="hidden" onchange="previewImage(event)">
                
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-2xl font-[Poppins] font-bold">Editar Perfil</h2>
                        <p class="text-gray-700 text-sm">Actualiza tu información personal</p>
                    </div>
                    <div class="flex space-x-3">
                        <a href="/a_1/public/empleado/perfil.php"
                            class="px-4 py-2 bg-red-200 text-red-700 rounded-full hover:bg-red-300 transition-all text-sm">
                            Cancelar
                        </a>
                        <button type="submit" name="update_profile"
                            class="px-4 py-2 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition-all text-sm">
                            Guardar
                        </button>
                    </div>
                </div>

                <div class="space-y-5">
                    <div class="grid md:grid-cols-2 gap-5">
                        <div>
                            <label for="nombreempleado" class="block text-sm font-medium text-gray-700 mb-1">Nombre completo</label>
                            <input type="text" id="nombreempleado" name="nombreempleado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                value="<?= htmlspecialchars($empleado['nombreempleado'] ?? '') ?>">
                        </div>
                        <div>
                            <label for="phoneempleado" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input type="tel" id="phoneempleado" name="phoneempleado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                value="<?= htmlspecialchars($empleado['phoneempleado'] ?? '') ?>">
                        </div>
                        <div>
                            <label for="email_empleado" class="block text-sm font-medium text-gray-700 mb-1">Correo electrónico</label>
                            <input type="email" id="email_empleado" name="email_empleado"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                value="<?= htmlspecialchars($empleado['email_usuario'] ?? '') ?>">
                        </div>
                        <div>
                            <label for="edad" class="block text-sm font-medium text-gray-700 mb-1">Edad</label>
                            <input type="number" id="edad" name="edad"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"
                                value="<?= htmlspecialchars($empleado['edad'] ?? '') ?>">
                        </div>
                    </div>
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea id="descripcion" name="descripcion" rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none"><?= htmlspecialchars($empleado['descripcion'] ?? '') ?></textarea>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>


<?php
    include_once '../templates/footerempleado.php';
?>
<!-- Agregar animaciones CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />


