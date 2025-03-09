<?php
require '../../config/confg.php';
session_start();

// $stmt = $pdo->query("SELECT * FROM empleados");
// $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $stmt = $pdo->query("SELECT * FROM negocio");
// $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $stmt = $pdo->prepare("
//     SELECT negocio.*, metodo_de_pago.tipo AS metodo_de_pago
//     FROM negocio
//     LEFT JOIN metodo_de_pago ON negocio.metodo_de_pago_id = metodo_de_pago.id
// ");
// $stmt->execute();
// $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// $stmt = $pdo->prepare("
//     SELECT empleados.*, negocio.nombrenegocio AS nombre_negocio
//     FROM empleados
//     LEFT JOIN negocio ON empleados.negocio_id = negocio.id
// ");
// $stmt->execute();
// $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../public/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Servicios - Noir Elite Barbería</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');
        
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .hover-zoom {
            transition: transform 0.3s ease;
        }
        .hover-zoom:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body class="font-body bg-white">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Menú móvil -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-b border-gray-200 px-4 py-4">
            <div class="flex flex-col space-y-4">
                <a href="inicio.html" class="text-gray-600 hover:text-emerald-600">Inicio</a>
                <a href="servicios.html" class="text-emerald-600 font-medium">Mis servicios</a>
                <a href="Aendas.html" class="text-gray-600 hover:text-emerald-600">Agendas</a>
                <a href="/templates/perfil.html" class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all text-center">
                    Perfil
                </a>
            </div>
        </div>
    </nav>

    <!-- Header de Servicios -->
    <section class="pt-32 pb-12 md:pt-40 md:pb-20 bg-gradient-to-r from-black to-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="font-heading text-4xl md:text-5xl font-bold mb-4">Nuestros Servicios</h2>
            <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                Descubre nuestra gama completa de servicios de barbería y estilismo personal diseñados para el hombre moderno.
            </p>
        </div>
    </section>

    <!-- Categorías de Servicios -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex overflow-x-auto pb-4 space-x-4 mb-8 scrollbar-hide">
                <button class="flex-shrink-0 bg-emerald-600 text-white px-6 py-2 rounded-full">
                    Todos
                </button>
                <button class="flex-shrink-0 bg-gray-100 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-200">
                    Cortes
                </button>
                <button class="flex-shrink-0 bg-gray-100 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-200">
                    Barbas
                </button>
                <button class="flex-shrink-0 bg-gray-100 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-200">
                    Tratamientos
                </button>
                <button class="flex-shrink-0 bg-gray-100 text-gray-800 px-6 py-2 rounded-full hover:bg-gray-200">
                    Paquetes
                </button>
            </div>

            <!-- Servicios - Cortes -->
            <div class="mb-16">
                <h3 class="font-heading text-2xl font-bold mb-6 flex items-center">
                    <span class="bg-emerald-100 text-emerald-600 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                    </span>
                    Cortes de Cabello
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Servicio 1 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Corte Clásico</h4>
                                <span class="font-semibold text-emerald-600">$350</span>
                            </div>
                            <p class="text-gray-600 mb-4">Corte tradicional con tijeras, incluye lavado y peinado.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">30 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Fade Moderno</h4>
                                <span class="font-semibold text-emerald-600">$400</span>
                            </div>
                            <p class="text-gray-600 mb-4">Degradado perfecto con diseño personalizado, incluye lavado y acabado.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">45 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 3 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Corte + Diseño</h4>
                                <span class="font-semibold text-emerald-600">$450</span>
                            </div>
                            <p class="text-gray-600 mb-4">Corte personalizado con diseño exclusivo o patrón en laterales o nuca.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">50 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 4 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Niños (hasta 12 años)</h4>
                                <span class="font-semibold text-emerald-600">$300</span>
                            </div>
                            <p class="text-gray-600 mb-4">Corte especial para niños, con paciencia y cuidado extra.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">30 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Servicios - Barbas -->
            <div class="mb-16">
                <h3 class="font-heading text-2xl font-bold mb-6 flex items-center">
                    <span class="bg-emerald-100 text-emerald-600 p-2 rounded-lg mr-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path>
                        </svg>
                    </span>
                    Servicios de Barba
                </h3>

                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Servicio 1 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Perfilado de Barba</h4>
                                <span class="font-semibold text-emerald-600">$200</span>
                            </div>
                            <p class="text-gray-600 mb-4">Definición y limpieza de contornos para una barba bien cuidada.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">20 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 2 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Afeitado Tradicional</h4>
                                <span class="font-semibold text-emerald-600">$350</span>
                            </div>
                            <p class="text-gray-600 mb-4">Afeitado clásico con navaja, toallas calientes y masaje facial.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">40 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>

                    <!-- Servicio 3 -->
                    <div class="bg-gray-50 rounded-xl p-6 flex gap-4 hover-zoom shadow-sm">
                        <div class="w-20 h-20 bg-emerald-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-heading text-xl font-semibold">Tratamiento Completo</h4>
                                <span class="font-semibold text-emerald-600">$250</span>
                            </div>
                            <p class="text-gray-600 mb-4">Lavado, acondicionado, peinado y aceite para barba con aroma a elección.</p>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">30 min</span>
                                <a href="Aendas.html" class="text-emerald-600 font-medium hover:text-emerald-700">Reservar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>

    <!-- Preguntas Frecuentes -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4">
            <h3 class="font-heading text-3xl font-bold mb-8 text-center">Preguntas Frecuentes</h3>
            
            <div class="space-y-4">
                <!-- Pregunta 1 -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-lg mb-2">¿Necesito reservar con anticipación?</h4>
                    <p class="text-gray-600">Recomendamos reservar con al menos 24 horas de anticipación para garantizar tu turno, especialmente los fines de semana. Sin embargo, también aceptamos clientes sin cita previa según disponibilidad.</p>
                </div>
                
                <!-- Pregunta 2 -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-lg mb-2">¿Puedo cambiar o cancelar mi reserva?</h4>
                    <p class="text-gray-600">Sí, puedes cambiar o cancelar tu reserva hasta 4 horas antes de tu cita sin costo. Si cancelas con menos tiempo, podría aplicarse un cargo del 50% del servicio.</p>
                </div>
                
                <!-- Pregunta 3 -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h4 class="font-semibold text-lg mb-2">¿Tienen productos a la venta?</h4>
                    <p class="text-gray-600">Sí, vendemos una
                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>

</body>