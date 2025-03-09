<?php
require '../../config/confg.php';
// Iniciar sesión para manejar datos del usuario
session_start();

// Verificar si el usuario tiene rol de administrador
if (!isset($_SESSION['user_id']) || $_SESSION['rol'] !== 'admin') {
    // Redirigir a la página de login si no es administrador
    header('Location: login.php');
    exit;
}


// Obtener información del administrador actual
$admin_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT email_usuario, rol, fecha_creacion, activo FROM usuarios WHERE id = :id");
$stmt->bindParam(':id', $admin_id);
$stmt->execute();
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no existe la información, crear un arreglo con datos predeterminados
if (!$admin) {
    $admin = [
        'nombre' => 'Administrador',
        'correo' => $_SESSION['email'] ?? 'admin@example.com',
        'rol' => 'admin',
        'nivel' => 'Completo',
        'ultimo_acceso' => date('Y-m-d H:i:s'),
        'cuenta_creada' => date('Y-m-d')
    ];
} else {
    // Adaptar los campos de la BD a la estructura que necesitamos
    $admin['correo'] = $admin['email_usuario'];
    $admin['nivel'] = $admin['activo'] ? 'Completo' : 'Restringido';
    $admin['ultimo_acceso'] = date('Y-m-d H:i:s'); // Esto debería venir de algún registro de sesiones
    $admin['cuenta_creada'] = $admin['fecha_creacion'];
}

// Obtener actividades recientes (últimas 5 modificaciones en las tablas principales)
// Esta es una implementación básica, puedes adaptarla según tus necesidades

// Consulta para obtener los últimos cambios en "negocio"
$stmt = $pdo->prepare("SELECT 'negocio' AS tipo, 'fa-building' AS icono, 
                        'Modificación' AS accion, nombrenegocio AS detalle, 
                        current_date AS fecha, current_time AS hora 
                        FROM negocio ORDER BY id DESC LIMIT 2");
$stmt->execute();
$actividades_negocio = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los últimos cambios en "empleados"
$stmt = $pdo->prepare("SELECT 'empleado' AS tipo, 'fa-user-tie' AS icono, 
                        'Modificación' AS accion, nombreempleado AS detalle, 
                        current_date AS fecha, current_time AS hora 
                        FROM empleados ORDER BY id DESC LIMIT 2");
$stmt->execute();
$actividades_empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Consulta para obtener los últimos cambios en "usuarios"
$stmt = $pdo->prepare("SELECT 'sistema' AS tipo, 'fa-user-shield' AS icono, 
                        'Acceso' AS accion, email_usuario AS detalle, 
                        current_date AS fecha, current_time AS hora 
                        FROM usuarios ORDER BY fecha_creacion DESC LIMIT 1");
$stmt->execute();
$actividades_usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Combinar todas las actividades
$actividades = array_merge($actividades_negocio, $actividades_empleados, $actividades_usuarios);

// Ordenar por fecha y hora (esto es una simulación, en un caso real usarías timestamps)
usort($actividades, function($a, $b) {
    $date_a = $a['fecha'] . ' ' . $a['hora'];
    $date_b = $b['fecha'] . ' ' . $b['hora'];
    return strcmp($date_b, $date_a);
});

// Limitar a 5 actividades
$actividades = array_slice($actividades, 0, 5);

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
include_once '../templates/mode.php';
?>

<!-- Referencias CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

<style>
    .gradient-bg {
        background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
    }
    
    .profile-icon {
        transition: all 0.5s ease;
    }
    
    .profile-card:hover .profile-icon {
        transform: scale(1.1) rotate(10deg);
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(37, 99, 235, 0); }
        100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0); }
    }
    
    .activity-item {
        transition: all 0.3s ease;
    }
    
    .activity-item:hover {
        transform: translateX(5px);
    }
    
    .negocio-bg { background-color: rgba(59, 130, 246, 0.15); }
    .empleado-bg { background-color: rgba(16, 185, 129, 0.15); }
    .sistema-bg { background-color: rgba(139, 92, 246, 0.15); }
    
    .negocio-text { color: rgb(37, 99, 235); }
    .empleado-text { color: rgb(5, 150, 105); }
    .sistema-text { color: rgb(124, 58, 237); }
</style>

<!-- Contenedor principal con fondo mejorado -->
<div class="mx-auto p-6 mt-8 mb-16 bg-gray-50 min-h-screen">
    <!-- Encabezado de bienvenida con animación -->
    <div class="animate__animated animate__fadeIn animate__delay-1s max-w-5xl mx-auto mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Bienvenido</h1>
        <p class="text-gray-600">Panel de administración | <span class="text-blue-600"><?php echo date('l, d F Y'); ?></span></p>
    </div>
    
    <!-- Card de perfil principal con animaciones y diseño mejorado -->
    <div class="profile-card bg-white rounded-xl shadow-lg p-8 mb-10 max-w-5xl mx-auto overflow-hidden animate__animated animate__fadeInUp">
        <div class="flex flex-col md:flex-row">
            <!-- Avatar con efecto gradient -->
            <div class="mb-8 md:mb-0 md:mr-10 flex flex-col items-center">
                <div class="w-32 h-32 rounded-full gradient-bg flex items-center justify-center text-white text-5xl mb-4 profile-icon pulse">
                    <i class="fas fa-user-shield"></i>
                </div>
                <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-base font-medium mt-2">
                    <?php echo ucfirst($admin['rol']); ?>
                </span>
                <div class="mt-4 flex space-x-2">
                    <button class="bg-gray-200 hover:bg-gray-300 p-2 rounded-full transition">
                        <i class="fas fa-cog text-gray-600"></i>
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 p-2 rounded-full transition">
                        <i class="fas fa-bell text-gray-600"></i>
                    </button>
                    <button class="bg-gray-200 hover:bg-gray-300 p-2 rounded-full transition">
                        <i class="fas fa-envelope text-gray-600"></i>
                    </button>
                </div>
            </div>
            
            <!-- Información de perfil con diseño mejorado -->
            <div class="flex-1">
                <h2 class="text-2xl font-bold text-gray-800 mb-4 pb-2 border-b"></h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-envelope mr-3 text-blue-600"></i>
                            <span class="text-gray-600 font-medium">Correo Electrónico</span>
                        </div>
                        <p class="text-gray-800 pl-7"><?php echo $admin['correo']; ?></p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-user-tag mr-3 text-blue-600"></i>
                            <span class="text-gray-600 font-medium">Nivel de Acceso</span>
                        </div>
                        <p class="text-gray-800 pl-7"><?php echo $admin['nivel']; ?></p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-clock mr-3 text-blue-600"></i>
                            <span class="text-gray-600 font-medium">Último Acceso</span>
                        </div>
                        <p class="text-gray-800 pl-7"><?php echo $admin['ultimo_acceso']; ?></p>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition">
                        <div class="flex items-center mb-1">
                            <i class="fas fa-calendar mr-3 text-blue-600"></i>
                            <span class="text-gray-600 font-medium">Cuenta Creada</span>
                        </div>
                        <p class="text-gray-800 pl-7"><?php echo $admin['cuenta_creada']; ?></p>
                    </div>
                </div>
                
                <!-- <div class="mt-8 flex flex-wrap gap-4">
                    <a href="editar_perfil.php" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-edit mr-2"></i> Editar Perfil
                    </a>
                    <a href="cambiar_password.php" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition duration-200 flex items-center">
                        <i class="fas fa-key mr-2"></i> Cambiar Contraseña
                    </a>
                </div>-->
            </div>
        </div>
    </div>

    
    <!-- Actividad reciente detallada -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-5xl mx-auto mb-10 fade-in">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-history mr-2 text-blue-600"></i> Actividad Reciente
            <span class="ml-auto text-sm text-gray-500">Mostrando últimas 5 actividades</span>
        </h3>
        
        <div class="space-y-4">
            <?php foreach ($actividades as $actividad): ?>
                <div class="activity-item flex items-start p-4 rounded-lg hover:bg-gray-50 border-l-4 border-<?php 
                    echo ($actividad['tipo'] === 'negocio') ? 'blue' : (($actividad['tipo'] === 'empleado') ? 'green' : 'purple'); 
                ?>-500">
                    
                    <div class="p-3 rounded-full <?php echo $actividad['tipo']; ?>-bg mr-4">
                        <i class="fas <?php echo $actividad['icono']; ?> <?php echo $actividad['tipo']; ?>-text"></i>
                    </div>
                    
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <h4 class="font-medium text-gray-800">
                                <?php echo $actividad['accion']; ?> de <?php echo ucfirst($actividad['tipo']); ?>
                            </h4>
                            <span class="text-sm text-gray-500">
                                <?php echo $actividad['fecha']; ?> - <?php echo $actividad['hora']; ?>
                            </span>
                        </div>
                        <p class="text-gray-600 mt-1"><?php echo $actividad['detalle']; ?></p>
                        
                        <?php if ($actividad['accion'] === 'Modificación'): ?>
                            <div class="mt-2 text-sm">
                                <a href="#" class="text-blue-600 hover:underline">Ver detalles del cambio</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="mt-6 text-center">
            <a href="historial_actividades.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center justify-center">
                Ver historial completo <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
    
    <!-- Accesos Rápidos -->
    <div class="bg-white rounded-lg shadow-md p-6 max-w-5xl mx-auto mb-10 fade-in">
        <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
            <i class="fas fa-bolt mr-2 text-blue-600"></i> Accesos Rápidos
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="create_bussines.php" class="bg-blue-50 hover:bg-blue-100 p-4 rounded-lg text-center transition">
                <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-plus-circle text-blue-600"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-800">Nuevo Negocio</h4>
            </a>
            
            <a href="create.php" class="bg-green-50 hover:bg-green-100 p-4 rounded-lg text-center transition">
                <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-user-plus text-green-600"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-800">Nuevo Empleado</h4>
            </a>
            
            <a href="create_metodos_pago.php" class="bg-yellow-50 hover:bg-yellow-100 p-4 rounded-lg text-center transition">
                <div class="bg-yellow-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-credit-card text-yellow-600"></i>
                </div>
                <h4 class="text-sm font-medium text-gray-800">Métodos de Pago</h4>
            </a>
            

        </div>
    </div>
</div>

<!-- Script para animaciones adicionales -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animación para los elementos de actividad
    const activityItems = document.querySelectorAll('.activity-item');
    activityItems.forEach((item, index) => {
        setTimeout(() => {
            item.classList.add('animate__animated', 'animate__fadeInLeft');
        }, 150 * index);
    });
});
</script>

<?php
include_once '../templates/footeradmin.php';
?>