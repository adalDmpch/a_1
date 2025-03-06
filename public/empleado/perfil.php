<?php
session_start();
require '../../config/confg.php';

// Verificar si el usuario está logueado y tiene el rol correcto
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../LoginAdmin.php");
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


?>



<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Perfil - Noir Elite - Barbería & Estilistas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');

        .font-heading {
            font-family: 'Poppins', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        .hover-zoom {
            transition: transform 0.3s ease;
        }

        .hover-zoom:hover {
            transform: scale(1.03);
        }
    </style>
</head>

<body >
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                        <span class="text-emerald-600">NOIR</span>
                        <span class="text-gray-800">ELITE</span>
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="/a_1/public/empleado/inicio.php" class="text-gray-600 hover:text-emerald-600">Inicio</a>
                    <a href="/a_1/public/empleado/agenda.php" class="text-gray-600 hover:text-emerald-600">Agendas</a>
                    <a href="/a_1/public/empleado/perfil.php" class="text-emerald-600 font-medium">Perfil</a>
                    <a href="../../actions/logout.php" class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all">
                        Cerrar Sesión
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
                <a href="/a_1/public/empleado/inicio.php" class="block text-gray-600 hover:text-emerald-600 py-2">Inicio</a>
                <a href="/a_1/public/empleado/agenda.php" class="block text-gray-600 hover:text-emerald-600 py-2">Agendas</a>
                <a href="/a_1/public/empleado/perfil.php" class="block text-emerald-600 py-2">Perfil</a>
                <a href="../../actions/logout.php"
                    class="block bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all inline-block mt-2">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal - con espacio para el navbar fijo -->
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

</html>