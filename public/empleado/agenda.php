<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../public/login.php");
    exit();
}

$pageTitle = 'BELLA HAIR - Historial de Citas';
include_once '../templates/headeremleado.php';
include_once '../templates/navbarempleado.php';

?>


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
                <a href="/a_1/public/empleado/inicio.php"
                        class="font-medium text-gray-500 hover:text-emerald-600 pb-1">Ver citas</a>
                    <a class="font-medium text-emerald-600 border-b-2 border-emerald-600 pb-1" href="/a_1/public/empleado/agenda.php">
                         Historial</a>
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

<?php
include_once '../templates/footerempleado.php';
?>
<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

