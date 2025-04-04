<?php
session_start(); // Iniciar sesión para usar $_SESSION
$mensaje = "";

require '../config/confg.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Recoger datos del formulario
        $nombre = $_POST["nombre"];
        $fecha = $_POST["fecha"];
        $genero = $_POST["genero"];
        $email = $_POST["email"];
        $tel = $_POST["tel"];
        $direccion = $_POST["direccion"];
        $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

        // **Verificar si el usuario ya existe**
        $query_check = "SELECT id FROM usuarios WHERE email_usuario = :email";
        $stmt = $pdo->prepare($query_check);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->fetch()) {
            throw new Exception("El correo electrónico ya está registrado. Usa otro.");
        }

        // Manejo de imagen de perfil como datos binarios
        if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
            // Leer el contenido binario de la imagen
            $foto_binaria = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
            
            if ($foto_binaria === false) {
                throw new Exception("Error al leer la imagen.");
            }
        } else {
            throw new Exception("Error al subir la imagen. Por favor, intenta de nuevo.");
        }

        // Iniciar transacción para asegurar que todas las operaciones se completen
        $pdo->beginTransaction();

        // Insertamos primero el usuario sin cliente_id
        $query_insert_user = "INSERT INTO usuarios (email_usuario, password, rol, activo) 
                              VALUES (:email, :password, 'cliente', true) RETURNING id";
        $stmt = $pdo->prepare($query_insert_user);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $usuario_id = $stmt->fetchColumn();

        // Insertamos el cliente con la imagen como datos binarios
        $query_insert_cliente = "INSERT INTO cliente (nombre, fecha, genero, email_cliente, phone, address, foto_de_perfil) 
                                 VALUES (:nombre, :fecha, :genero, :email, :tel, :direccion, :foto_de_perfil) RETURNING id";
        $stmt = $pdo->prepare($query_insert_cliente);
        $stmt->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $stmt->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $stmt->bindValue(':genero', $genero, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
        $stmt->bindValue(':direccion', $direccion, PDO::PARAM_STR);
        
        // Esta es la parte importante: usar bindParam con PDO::PARAM_LOB para guardar datos binarios
        $stmt->bindParam(':foto_de_perfil', $foto_binaria, PDO::PARAM_LOB);
        $stmt->execute();
        $cliente_id = $stmt->fetchColumn();

        // Asociamos cliente_id con el usuario
        $query_update_user = "UPDATE usuarios SET cliente_id = :cliente_id WHERE id = :usuario_id";
        $stmt = $pdo->prepare($query_update_user);
        $stmt->bindValue(':cliente_id', $cliente_id, PDO::PARAM_INT);
        $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        // Confirmar transacción
        $pdo->commit();

        // Guardamos el mensaje de éxito en la sesión
        $_SESSION['mensaje'] = "Registro exitoso. ¡Bienvenido!";
        
        // Redirigir para limpiar $_POST y evitar duplicados
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();

    } catch (PDOException $e) {
        // Revertir cambios si hay error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $mensaje = "Error en la conexión: " . $e->getMessage();
    } catch (Exception $e) {
        // Revertir cambios si hay error
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $mensaje = $e->getMessage();
    }
}

// Mostrar mensaje de éxito en la página de redirección
if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']); // Limpiar el mensaje después de mostrarlo
}
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .auth-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.08);
        }
        
        .input-field:focus {
            box-shadow: 0 0 0 3px rgba(0,0,0,0.05);
        }
    </style>
    <title>BELLA - Registro </title>
</head>
<body class="font-body bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-sm fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                    <span class="text-emerald-600">BELLA</span> 
                    <span class="text-gray-800">HAIR</span>
                </h1>
                </div>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="#ayuda" class="text-gray-600 hover:text-gray-900">Asistencia</a>
                    <a href="../public/login.php" class="bg-gray-900 text-white px-5 py-2 rounded-full hover:bg-gray-800">Acceder</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="pt-24 pb-12 max-w-4xl mx-auto px-4">
        <div class="auth-card rounded-2xl p-8 md:p-12">

            <h2 class="font-heading text-3xl font-bold text-gray-900 mb-8">Registro Cliente</h2>

            <!-- Mensaje de éxito o error -->
            <?php if ($mensaje): ?>
                <div class="p-4 mb-6 text-white rounded-lg <?= strpos($mensaje, 'exitoso') !== false ? 'bg-green-500' : 'bg-red-500' ?>">
                    <?= $mensaje ?>
                </div>
            <?php endif; ?>
            

            <form class="space-y-8" method="POST" enctype="multipart/form-data">
                <div class="space-y-6">
                    <h3 class="font-heading text-xl text-gray-900 mb-4">Información Personal</h3>
                        
                    <!-- Imagen de perfil -->
                    <div class="py-4 flex flex-col items-center">
                        <div class="relative group w-32 h-32 rounded-full border-4 border-gray-200 bg-black bg-opacity-40 flex items-center justify-center">
                            <img id="preview" src="" alt="Foto de perfil" class="w-full h-full rounded-full object-cover hidden" onerror="this.classList.add('hidden')" onload="this.classList.remove('hidden')" />
                            <span id="alt-text" class="text-gray-200 text-sm absolute">Foto de perfil</span>
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                <label for="foto_de_perfil" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full transition-all duration-200 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </label>
                            </div>  
                        </div>
                        <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" class="hidden" onchange="previewImage(event)" />
                    </div>


                    <div class="grid md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo*</label>
                            <input type="text" name="nombre" required
                            class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha de Nacimiento</label>
                            <input type="date" name="fecha" required 
                                   class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Género</label>
                        <select name="genero" required class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                            <option value="">Seleccionar</option>
                            <option value="Masculino" >Masculino</option>
                            <option value="Femenino" >Femenino</option>
                            <option value="Otro" >Otro</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="font-heading text-xl text-gray-900 mb-4">Datos de Contacto</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico*</label>
                        <input type="email" name="email" required 
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="tel" name="tel" required 
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="font-heading text-xl text-gray-900 mb-4">Ubicación</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dirección</label>
                        <input type="text" name="direccion" required 
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="font-heading text-xl text-gray-900 mb-4">Seguridad</h3>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña*</label>
                        <input type="password" name="password" required
                               class="input-field w-full px-4 py-3 border border-gray-200 rounded-lg">
                    </div>
                    
                </div>

                <div class="border-t pt-6">
                    <label class="flex items-start space-x-3">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-gray-900 focus:ring-gray-900">
                        <span class="text-sm text-gray-600">
                            Acepto los <a href="#" class="text-gray-900 underline">Términos de Servicio</a> y 
                            <a href="#" class="text-gray-900 underline">Política de Privacidad</a>
                        </span>
                    </label>
                </div>

                <button type="submit" class="w-full bg-gray-900 text-white py-4 rounded-lg hover:bg-gray-800 transition-colors font-medium">
                    Crear Cuenta
                </button>

                <div class="text-center mt-6">
                    <p class="text-gray-600">¿Ya tienes cuenta? 
                        <a href="../public/login.php" class="text-emerald-600 font-medium hover:underline">Inicia sesión aquí</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</body>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-400">&copy; 2024 BELLA HAIR Barbería. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview');
            const altText = document.getElementById('alt-text'); // Capturar el texto alternativo

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden'); // Mostrar imagen
                    if (altText) altText.classList.add('hidden'); // Ocultar el texto
                }
                reader.readAsDataURL(file);
            }
        }

    </script>
</html>
