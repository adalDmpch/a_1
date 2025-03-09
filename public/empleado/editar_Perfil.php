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
?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Editar Perfil - Noir Elite - Barbería & Estilistas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');
    </style>
</head>

<body class="bg-gray-50 font-[Inter]">
    <!-- Navbar -->
    <nav  class="bg-white border-b-2 border-emerald-500/20 shadow-sm">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                        <span class="text-emerald-600">NOIR</span>
                        <span class="text-gray-800">ELITE</span>
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <!-- <a href="" class="text-gray-600 hover:text-gray-900">Volver al Dashboard</a> -->
                    <a href="../empleado/perfil.php" class="flex items-center space-x-3 p-3 text-red-600 hover:bg-red-100  px-3 py-2 rounded-lg">
                        <svg class="w-6 h-6 text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                        </svg>
                        <span>Inicio</span>
                    </a>
                </div>

                <button id="menuButton" class="md:hidden text-gray-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

       <!-- Menú móvil -->
       <div id="mobileMenu" class="md:hidden hidden bg-white border-b border-gray-200 py-4">
            <div class="max-w-7xl mx-auto px-4 space-y-3">
                <a href="../empleado/perfil.php" class="flex items-center space-x-3 p-3 text-red-600 hover:bg-red-100  px-3 py-2 rounded-lg">
                        <svg class="w-6 h-6 text-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M3 12l9-9 9 9M4 10v10a2 2 0 002 2h12a2 2 0 002-2V10" />
                        </svg>
                        <span>Inicio</span>
                    </a>
            </div>
        </div>
    </nav>






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


    <!-- Footer -->
    <footer class="bg-black text-white py-12 mt-auto">
        <div class="max-w-7xl mx-auto px-4">
                <div class="grid md:grid-cols-4 gap-8">
                    <div class="space-y-4">
                        <h4 class="font-heading text-xl font-semibold">NOIR</h4>
                        <p class="text-gray-400">Barbería y Estilismo Moderno</p>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-4">Horario</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li>Lun-Vie: 9am - 8pm</li>
                            <li>Sábado: 9am - 6pm</li>
                            <li>Domingo: Cerrado</li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-4">Contacto</h5>
                        <ul class="space-y-2 text-gray-400">
                            <li>Av. Libertador 1234</li>
                            <li>hola@noir.com</li>
                            <li>+54 11 5678-9012</li>
                        </ul>
                    </div>

                    <div>
                        <h5 class="font-semibold mb-4">Síguenos</h5>
                        <div class="flex space-x-4">
                            <a href="#"
                                class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                                </svg>
                            </a>
                            <a href="#"
                                class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                    <p class="text-gray-400">&copy; 2024 Noir Barbería. Todos los derechos reservados.</p>
                </div>
            </div>
    </footer>

    <script>
        // Menú móvil
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });



        function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}



        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
