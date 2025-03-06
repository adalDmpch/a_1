<?php
require '../../config/confg.php';


session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../LoginAdmin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Noir Elite - Agenda de Citas</title>
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

        .calendar-day {
            min-height: 100px;
            transition: all 0.2s;
        }

        .calendar-day:hover:not(.inactive) {
            background-color: #f0fdf4;
            border-color: #10b981;
        }

        .calendar-day.inactive {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .appointment {
            border-left: 3px solid #10b981;
        }
    </style>
</head>

<body class="font-body bg-gray-50">
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
                        Cerrar Sesion
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
                <a href="/a_1/public/empleado/perfil.php" class="block text-emerald-600 font-medium py-2">Perfil</a>
                <a href="../../actions/logout.php" class="block bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all inline-block mt-2">
                    Cerrar Sesion
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main class="pt-24 pb-16 px-4">
        <div class="max-w-7xl mx-auto">


            <!-- Filtros y Acciones -->
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                    <!-- Encabezado -->
                    <div class="flex-grow">
                        <h2 class="font-heading text-4xl text-center md:text-left font-bold text-gray-900 mb-3">Agenda
                            de Citas Pendientes</h2>
                        <p class="text-gray-600 max-w-2xl md:mx-0 mx-auto">Gestiona tus citas, consulta tu agenda y
                            organiza tu
                            tiempo de manera eficiente.</p>
                    </div>

                    <div class="flex items-center space-x-4 flex-shrink-0">
                        <a class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1" href="/templates/empleado/inicio.html">Ver
                            citas</a>
                        <a href="/a_1/public/empleado/agenda.php"
                            class="font-medium text-gray-500 hover:text-emerald-600 pb-1">Historial</a>
                    </div>
                </div>
            </div>
            <!-- Citas del día seleccionado -->
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-heading text-2xl font-bold mb-6">Citas para hoy: 26 de Febrero, 2025</h3>

                <div class="space-y-4">
                    <!-- Cita 1 -->
                    <div
                        class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all hover-zoom">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-lg">Ana Suárez</h4>
                                <p class="text-gray-600">Corte de cabello y peinado</p>
                                <div class="flex items-center mt-3 text-gray-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>9:30 - 10:30 AM</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-emerald-600 hover:bg-emerald-50 p-2 rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>
                                <button class="text-red-600 hover:bg-red-50 p-2 rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between">
                            <div class="text-sm">
                                <span class="text-gray-500">Teléfono:</span> +54 11 6543-2109
                            </div>
                            <div>
                                <span
                                    class="bg-emerald-100 text-emerald-800 text-xs px-3 py-1 rounded-full">Confirmada</span>
                            </div>
                        </div>
                    </div>

                    <!-- Cita 2 -->
                    <div
                        class="border border-gray-200 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all hover-zoom">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-medium text-lg">Pedro Martínez</h4>
                                <p class="text-gray-600">Afeitado y corte de barba</p>
                                <div class="flex items-center mt-3 text-gray-500">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>14:00 - 15:00 PM</span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-emerald-600 hover:bg-emerald-50 p-2 rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                </button>
                                <button class="text-red-600 hover:bg-red-50 p-2 rounded-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="mt-3 border-t border-gray-100 pt-3 flex justify-between">
                            <div class="text-sm">
                                <span class="text-gray-500">Teléfono:</span> +54 11 7890-1234
                            </div>
                            <div>
                                <span
                                    class="bg-yellow-100 text-yellow-800 text-xs px-3 py-1 rounded-full">Pendiente</span>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-black text-white py-12">
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