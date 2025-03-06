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



include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
?>


<!-- Contenido Principal -->
<main class="container mx-auto p-6 flex-grow">
    <!-- Tabla de Negocios -->
    <div class="bg-gray-100 p-6 rounded-xl shadow-xl mt-8">
        <h2 class="text-2xl font-bold mb-4 text-center text-bg-[#001A33]">
            <i class="fas fa-store text-[#001A33] mr-2"></i> Mis Negocios
        </h2>
        <a href="/a_1/public/admin/create_bussines.php" class="bg-[#001A33] text-white px-4 py-2 rounded-lg block text-left font-semibold hover:bg-[#001A33] transition w-max">
            <i class="fas fa-plus-circle mr-2"></i> Agregar Negocio
        </a>
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
                            <a href="/a_1/public/admin/edit_bussinnes.php?id=<?= $negocio['id'] ?>" class="text-green-600 font-bold icon-container inline-flex mr-1">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a> |
                            <a href="javascript:void(0)" class="text-red-600 font-bold delete-btn icon-container inline-flex ml-1" 
                               data-id="<?= $negocio['id'] ?>" 
                               data-tipo="negocio" 
                               data-nombre="<?= $negocio['nombrenegocio'] ?>">
                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla de Empleados -->
    <div class="bg-gray-100 p-6 rounded-xl shadow-xl mt-8">
        <h2 class="text-2xl font-bold mb-4 text-center text-[#001A33]">
            <i class="fas fa-users text-[#001A33] mr-2"></i> Mis Empleados
        </h2>
        <a href="/a_1/public/admin/create.php" class="bg-[#001A33] text-white px-4 py-2 rounded-lg block text-left font-semibold hover:bg-[#001A33] transition w-max">
            <i class="fas fa-user-plus mr-2"></i> Agregar Empleados
        </a>
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
                            <a href="/a_1/public/admin/edit.php?id=<?= $empleado['id'] ?>" class="text-green-600 font-bold icon-container inline-flex mr-1">
                                <i class="fas fa-edit mr-1"></i> Editar
                            </a> |
                            <a href="javascript:void(0)" class="text-red-600 font-bold delete-btn icon-container inline-flex ml-1" 
                               data-id="<?= $empleado['id'] ?>" 
                               data-tipo="empleado" 
                               data-nombre="<?= $empleado['nombreempleado'] ?>">
                                <i class="fas fa-trash-alt mr-1"></i> Eliminar
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<script>
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
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancelar',
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
</script>
<?php
include_once '../templates/footeradmin.php';
?>
<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
</body>
</html>