<?php
require '../../config/confg.php';


session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

$pageTitle = 'Noir Elite - Agenda de Citas';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarempleado.php';
?>


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
<?php
include_once '../templates/footerempleado.php';
?>
<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

