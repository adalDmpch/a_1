<?php
require '../../config/confg.php';

$stmt = $pdo->query("SELECT * FROM empleados");
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM negocio");
$negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT negocio.*, metodo_de_pago.tipo AS metodo_de_pago
    FROM negocio
    LEFT JOIN metodo_de_pago ON negocio.metodo_de_pago_id = metodo_de_pago.id
");
$stmt->execute();
$negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("
    SELECT empleados.*, negocio.nombrenegocio AS nombre_negocio
    FROM empleados
    LEFT JOIN negocio ON empleados.negocio_id = negocio.id
");
$stmt->execute();
$empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../LoginAdmin.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Agregar SweetAlert2 para mejores confirmaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Navbar -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <img src="../../assets/images/logo.png" alt="Logo" class="h-12 w-12 rounded-full"/>
                <span class="text-2xl font-bold text-gray-800 ml-2">Bella Hair</span>
            </div>
            
            <button id="menuButton" class="md:hidden text-gray-800 hover:text-pink-500">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="text-gray-800 hover:text-pink-500">Inicio</a>
                <a href="perfil.html" class="text-[#001A33]">Mi Perfil</a>
                <a href="../../actions/logout.php" class="text-red-500">Cerrar Sesión</a>
            </div>
        </div>
        
        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-4">
                <a href="index.php" class="text-gray-800 hover:text-pink-500">Inicio</a>
                <a href="perfil.html" class="text-[#001A33]">Mi Perfil</a>
                <a href="../../actions/logout.php" class="text-red-500">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</nav>

<!-- Contenido Principal -->
<main class="container mx-auto p-6 flex-grow">
    <!-- Tabla de Negocios -->
    <div class="bg-gray-100 p-6 rounded-xl shadow-xl mt-8">
        <h2 class="text-2xl font-bold mb-4 text-center text-bg-[#001A33]">Mis Negocios</h2>
        <a href="/a_1/public/admin/create_bussines.php" class="bg-[#001A33] text-white px-4 py-2 rounded-lg block text-left font-semibold hover:bg-[#001A33] transition w-max">Agregar Negocio</a>
        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse bg-gray-200 text-black text-center rounded-lg shadow-lg overflow-hidden">
                <thead class="bg-[#001A33] text-white">
                    <tr>
                        <th class="p-3">Nombre</th>
                        <th class="p-3 hidden md:table-cell">Dirección</th>
                        <th class="p-3 hidden md:table-cell">Teléfono</th>
                        <th class="p-3 hidden md:table-cell">Email</th>
                        <th class="p-3 hidden lg:table-cell">Metodo de pago</th>
                        <th class="p-3 hidden lg:table-cell">Dias de trabajo</th>
                        <th class="p-3 hidden lg:table-cell">Hora de inicial</th>
                        <th class="p-3 hidden lg:table-cell">Hora de cierre</th>
                        <th class="p-3 hidden xl:table-cell">Servicios</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($negocios as $negocio): ?>
                    <tr class="border-b border-gray-400 hover:bg-gray-400 transition">
                        <td class="p-3"><?= $negocio['nombrenegocio'] ?></td>
                        <td class="p-3 hidden md:table-cell"><?= $negocio['ubicaciondelnegocio'] ?></td>
                        <td class="p-3 hidden md:table-cell"><?= $negocio['phonenegocio'] ?></td>
                        <td class="p-3 hidden md:table-cell"><?= $negocio['emailnegocio'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $negocio['metodo_de_pago'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $negocio['dias_operacion'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $negocio['horas_operacion'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $negocio['horas_fin'] ?></td>
                        <td class="p-3 hidden xl:table-cell"><?= $negocio['servicios'] ?></td>
                        <td class="p-3">
                            <a href="/a_1/public/admin/edit_bussinnes.php?id=<?= $negocio['id'] ?>" class="text-green-600 font-bold">Editar</a> |
                            <a href="javascript:void(0)" class="text-red-600 font-bold delete-btn" 
                               data-id="<?= $negocio['id'] ?>" 
                               data-tipo="negocio" 
                               data-nombre="<?= $negocio['nombrenegocio'] ?>">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Empleados -->
    <div class="bg-gray-100 p-6 rounded-xl shadow-xl mt-8">
        <h2 class="text-2xl font-bold mb-4 text-center text-[#001A33]">Mis Empleados</h2>
        <a href="/a_1/public/admin/create.php" class="bg-[#001A33] text-white px-4 py-2 rounded-lg block text-left font-semibold hover:bg-[#001A33] transition w-max">Agregar Empleados</a>
        <div class="overflow-x-auto mt-4">
            <table class="w-full border-collapse bg-gray-200 text-black text-center rounded-lg shadow-lg overflow-hidden">
                <thead class="bg-[#001A33] text-white">
                    <tr>
                        <th class="p-3">Nombre</th>
                        <th class="p-3 hidden md:table-cell">Sucursal</th>
                        <th class="p-3 hidden md:table-cell">Telefono</th>
                        <th class="p-3 hidden lg:table-cell">Email</th>
                        <th class="p-3 hidden lg:table-cell">Edad</th>
                        <th class="p-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empleados as $empleado): ?>
                    <tr class="border-b border-gray-400 hover:bg-gray-400 transition">
                        <td class="p-3"><?= $empleado['nombreempleado'] ?></td>
                        <td class="p-3 hidden md:table-cell"><?= $empleado['nombre_negocio'] ?></td>
                        <td class="p-3 hidden md:table-cell"><?= $empleado['phoneempleado'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $empleado['email_empleado'] ?></td>
                        <td class="p-3 hidden lg:table-cell"><?= $empleado['edad'] ?></td>
                        <td class="p-3">
                            <a href="/a_1/public/admin/edit.php?id=<?= $empleado['id'] ?>" class="text-green-600 font-bold">Editar</a> |
                            <a href="javascript:void(0)" class="text-red-600 font-bold delete-btn" 
                               data-id="<?= $empleado['id'] ?>" 
                               data-tipo="empleado" 
                               data-nombre="<?= $empleado['nombreempleado'] ?>">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer class="bg-[#001a33] text-white p-4 text-center mt-auto shadow-lg">
    <p>&copy; 2025 Nombre Empresa. Todos los derechos reservados.</p>
</footer>

<script>
    // Funcionalidad del menú hamburguesa
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
    
    // Mejorar la confirmación de eliminación con SweetAlert2
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tipo = this.getAttribute('data-tipo');
            const nombre = this.getAttribute('data-nombre');
            const tipoCapitalizado = tipo.charAt(0).toUpperCase() + tipo.slice(1);
            
            Swal.fire({
                title: `¿Eliminar ${tipo}?`,
                html: `¿Estás seguro que deseas eliminar a <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#001A33',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                allowOutsideClick: () => !Swal.isLoading(),
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../actions/delete.php?id=${id}&tipo=${tipo}`;
                }
            });
        });
    });
    
    // Función para mostrar detalles en dispositivos móviles
    document.querySelectorAll('tr').forEach(row => {
        if (!row.parentElement.tagName === 'THEAD') {
            row.addEventListener('click', function(e) {
                // Solo activar si se hace clic en la fila, no en los enlaces
                if (e.target.tagName !== 'A') {
                    
                }
            });
        }
    });
</script>

<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</body>
</html>