<?php
require '../../config/confg.php';

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

session_start();
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
    <title>Noir Elite - Historial de Citas</title>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
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
            <!-- Encabezado -->
            <div class="mb-10 text-center">
                <h2 class="font-heading text-4xl font-bold text-gray-900 mb-3">Historial de Citas</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Revisa el historial completo de tus citas anteriores y servicios realizados.</p>
            </div>
            
            <!-- Filtros y Acciones -->
            <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                    <div class="flex items-center space-x-4">
                        <button class="font-medium text-gray-500 hover:text-emerald-600 pb-1">Ver agenda</button>
                        <button class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1">Historial</button>
                    </div>
                    
                    <div class="flex space-x-3">
                        <div class="relative">
                            <select class="bg-white border border-gray-300 text-gray-700 py-2 pl-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 appearance-none">
                                <option>Todos los servicios</option>
                                <option>Corte de cabello</option>
                                <option>Barba</option>
                                <option>Coloración</option>
                                <option>Tratamientos</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <div class="relative">
                                <input type="text" placeholder="Buscar cliente" class="bg-white border border-gray-300 text-gray-700 py-2 pl-10 pr-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 w-full">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Filtros de fechas -->
                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm">Todos</button>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Última semana</button>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Último mes</button>
                    <button class="bg-white border border-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm hover:bg-emerald-50 hover:border-emerald-200">Últimos 3 meses</button>
                    
                    <div class="flex items-center gap-2 ml-auto">
                        <span class="text-gray-500 text-sm">Desde:</span>
                        <input type="date" class="border border-gray-200 rounded-lg p-2 text-sm">
                        <span class="text-gray-500 text-sm">Hasta:</span>
                        <input type="date" class="border border-gray-200 rounded-lg p-2 text-sm">
                        <button class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">Aplicar</button>
                    </div>
                </div>
            </div>
            
            <!-- Tabla de historial -->
            <div class="bg-white rounded-2xl shadow-sm p-6 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Servicio
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Duración
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Fila 1 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    25 Feb, 2025 
                                    <div class="text-xs text-gray-400">15:30</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            DP
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Diego Pérez</div>
                                            <div class="text-sm text-gray-500">+54 11 2345-6789</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Corte + Barba</div>
                                    <div class="text-xs text-gray-500">Estilista: Carlos</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    1h 00m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $5,500
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                            
                            <!-- Fila 2 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    24 Feb, 2025
                                    <div class="text-xs text-gray-400">11:00</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            ML
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">María López</div>
                                            <div class="text-sm text-gray-500">+54 11 8765-4321</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Coloración + Peinado</div>
                                    <div class="text-xs text-gray-500">Estilista: Laura</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    2h 30m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $9,800
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                            
                            <!-- Fila 3 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    23 Feb, 2025
                                    <div class="text-xs text-gray-400">17:45</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            JR
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Juan Rodríguez</div>
                                            <div class="text-sm text-gray-500">+54 11 4545-7878</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Corte Fade</div>
                                    <div class="text-xs text-gray-500">Estilista: Martín</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    45m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $3,800
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                            
                            <!-- Fila 4 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    22 Feb, 2025
                                    <div class="text-xs text-gray-400">14:30</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            AG
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Ana González</div>
                                            <div class="text-sm text-gray-500">+54 11 3344-5566</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Tratamiento Capilar</div>
                                    <div class="text-xs text-gray-500">Estilista: Laura</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    1h 15m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $7,200
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                        Cancelada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                            
                            <!-- Fila 5 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    21 Feb, 2025
                                    <div class="text-xs text-gray-400">10:15</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            LM
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Luis Martínez</div>
                                            <div class="text-sm text-gray-500">+54 11 8899-0011</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Corte Ejecutivo</div>
                                    <div class="text-xs text-gray-500">Estilista: Carlos</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    30m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $3,200
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                            
                            <!-- Fila 6 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    19 Feb, 2025
                                    <div class="text-xs text-gray-400">16:00</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                            PF
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">Patricia Flores</div>
                                            <div class="text-sm text-gray-500">+54 11 7788-9900</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Corte + Peinado</div>
                                    <div class="text-xs text-gray-500">Estilista: Laura</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    1h 00m
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                    $4,800
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Completada
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="text-emerald-600 hover:text-emerald-800 mr-3">Ver detalles</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Paginación -->
                <div class="flex items-center justify-between mt-6">
                    <div class="text-sm text-gray-500">
                        Mostrando 1-6 de 42 resultados
                    </div>
                    <div class="flex space-x-2">
                        <button class="bg-white border border-gray-300 text-gray-500 px-4 py-2 rounded-lg disabled:opacity-50">
                            Anterior
                        </button>
                        <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg">
                            1
                        </button>
                        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-emerald-50">
                            2
                        </button>
                        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-emerald-50">
                            3
                        </button>
                        <button class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-emerald-50">
                            Siguiente
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Resumen de estadísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-8">
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="text-gray-500 mb-2">Total de citas</div>
                    <div class="text-3xl font-bold">42</div>
                    <div class="text-emerald-600 text-sm mt-2">+12% vs mes anterior</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="text-gray-500 mb-2">Ingresos totales</div>
                    <div class="text-3xl font-bold">$178,500</div>
                    <div class="text-emerald-600 text-sm mt-2">+8% vs mes anterior</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="text-gray-500 mb-2">Servicio más popular</div>
                    <div class="text-xl font-bold">Corte + Barba</div>
                    <div class="text-emerald-600 text-sm mt-2">18 reservas</div>
                </div>
                
                <div class="bg-white rounded-2xl p-6 shadow-sm">
                    <div class="text-gray-500 mb-2">Clientes frecuentes</div>
                    <div class="text-3xl font-bold">8</div>
                    <div class="text-emerald-600 text-sm mt-2">+2 nuevos este mes</div>
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
                        <a href="#" class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">

                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/>
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