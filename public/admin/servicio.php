<?php
require '../../config/confg.php';

// Obtener todos los servicios
$stmt = $pdo->query("SELECT * FROM servicios ORDER BY tipo ASC");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
?>

<!-- Contenido Principal -->
<main class="container mx-auto p-6 flex-grow">
    <!-- Encabezado de la página -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-[#0F5132]">
            <i class="fas fa-concierge-bell mr-2"></i> Gestión de Servicios
        </h1>
        <a href="/a_1/public/admin/index.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver 
        </a>
    </div>

    <!-- Formulario para agregar nuevo servicio -->
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8 border-l-4 border-[#0F5132]">
        <h2 class="text-2xl font-bold text-[#0F5132] mb-4">
            <i class="fas fa-plus-circle mr-2"></i> Agregar Nuevo Servicio
        </h2>
        <form action="../../actions/save_service.php" method="POST" class="max-w-lg">
            <div class="mb-4">
                <label for="nuevo_servicio" class="block text-gray-700 font-bold mb-2">
                    Tipo de Servicio:
                </label>
                <input type="text" name="nuevo_servicio" id="nuevo_servicio" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition"
                    placeholder="Ej: Corte de cabello, Manicure, Tratamiento facial...">
            </div>

            <div class="mb-4">
                <label for="duracion" class="block text-gray-700 font-bold mb-2">
                    Duración (minutos):
                </label>
                <input type="number" name="duracion" id="duracion" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition"
                    placeholder="Ej: 30, 60, 90...">
            </div>

            <div class="mb-4">
                <label for="precio" class="block text-gray-700 font-bold mb-2">
                    Precio ($):
                </label>
                <input type="number" step="0.01" name="precio" id="precio" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition"
                    placeholder="Ej: 25.00, 50.00, 75.50...">
            </div>
            
            <button type="submit" class="bg-[#0F5132] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#0A3622] transition 
                flex items-center justify-center w-full md:w-auto">
                <i class="fas fa-save mr-2"></i> Guardar Servicio
            </button>
        </form>
    </div>

    <!-- Tabla de servicios existentes -->
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8">
        <h2 class="text-2xl font-bold text-[#0F5132] mb-4">
            <i class="fas fa-list mr-2"></i> Servicios Disponibles
        </h2>
        
        <?php if (empty($servicios)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i> No hay servicios registrados. Agrega uno nuevo utilizando el formulario de arriba.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-gray-50 text-left rounded-lg shadow-lg overflow-hidden">
                    <thead class="bg-[#0F5132] text-white">
                        <tr>
                            <th class="p-3">Tipo de Servicio</th>
                            <th class="p-3">Duración (min)</th>
                            <th class="p-3">Precio ($)</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($servicios as $servicio): ?>
                        <tr class="border-b border-gray-200 hover:bg-green-50 transition">
                            <td class="p-3 font-medium"><?= $servicio['tipo'] ?></td>
                            <td class="p-3"><?= $servicio['duracion'] ?></td>
                            <td class="p-3">$<?= number_format($servicio['precio'], 2) ?></td>
                            <td class="p-3 text-center">
                                <button 
                                    onclick="openEditModalService(<?= $servicio['id'] ?>, '<?= htmlspecialchars($servicio['tipo'], ENT_QUOTES, 'UTF-8') ?>', <?= $servicio['duracion'] ?>, <?= $servicio['precio'] ?>)" 
                                    class="bg-gray-600 text-white px-3 py-1 rounded inline-flex items-center mr-2 hover:bg-gray-900 transition">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                                <button
                                    onclick="confirmDelete(<?= $servicio['id'] ?>, '<?= htmlspecialchars($servicio['tipo'], ENT_QUOTES, 'UTF-8') ?>')" 
                                    class="bg-red-600 text-white px-3 py-1 rounded inline-flex items-center hover:bg-red-700 transition">
                                    <i class="fas fa-trash-alt mr-1"></i> Eliminar
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal de Edición (Oculto por defecto) -->
<div id="editModalService" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h3 class="text-xl font-bold text-[#0F5132]">
                <i class="fas fa-edit mr-2"></i> Editar Servicio
            </h3>
            <button onclick="closeEditModalService()" class="text-gray-600 hover:text-red-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editFormService" action="../../actions/update_service.php" method="POST">
            <input type="hidden" id="editIdService" name="id">
            <div class="mb-4">
                <label for="editTipoService" class="block text-gray-700 font-bold mb-2">
                    Tipo de Servicio:
                </label>
                <input type="text" id="editTipoService" name="tipo" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition">
            </div>

            <div class="mb-4">
                <label for="editDuracionService" class="block text-gray-700 font-bold mb-2">
                    Duración (minutos):
                </label>
                <input type="number" id="editDuracionService" name="duracion" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition">
            </div>

            <div class="mb-4">
                <label for="editPrecioService" class="block text-gray-700 font-bold mb-2">
                    Precio ($):
                </label>
                <input type="number" step="0.01" id="editPrecioService" name="precio" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0F5132] focus:border-[#0F5132] transition">
            </div>
            
            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" onclick="closeEditModalService()" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#0F5132] text-white rounded-lg hover:bg-[#0A3622] transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para las funcionalidades -->
<script>
// Modal de edición
function openEditModalService(id, tipo, duracion, precio) {
    document.getElementById('editIdService').value = id;
    document.getElementById('editTipoService').value = tipo;
    document.getElementById('editDuracionService').value = duracion;
    document.getElementById('editPrecioService').value = precio;
    document.getElementById('editModalService').classList.remove('hidden');
}

function closeEditModalService() {
    document.getElementById('editModalService').classList.add('hidden');
}

// Confirmación de eliminación
function confirmDelete(id, nombre) {
    Swal.fire({
        title: '¿Eliminar servicio?',
        html: `¿Estás seguro que deseas eliminar el servicio <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#0F5132',
        cancelButtonColor: '#d33',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancelar',
        focusConfirm: false
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../../actions/delete_service.php?id=${id}`;
        }
    });
}

// Cerrar modal al hacer clic fuera
window.addEventListener('click', function(event) {
    const modal = document.getElementById('editModalService');
    if (event.target === modal) {
        closeEditModalService();
    }
});

// Mensajes de éxito o error (integración con parámetros GET)
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const tipo = urlParams.get('tipo') || 'success';
    
    if (mensaje) {
        Swal.fire({
            icon: tipo,
            title: tipo === 'success' ? '¡Operación exitosa!' : 'Error',
            text: mensaje,
            timer: 3000,
            timerProgressBar: true
        });
    }
});
</script>

<!-- Incluir SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">


<?php
include_once '../templates/footeradmin.php';
?>