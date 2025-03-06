<?php
require '../../config/confg.php';

// Obtener todos los métodos de pago
$stmt = $pdo->query("SELECT * FROM metodo_de_pago ORDER BY tipo ASC");
$metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- Encabezado de la página -->
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-[#001A33]">
            <i class="fas fa-credit-card mr-2"></i> Gestión de Métodos de Pago
        </h1>
        <a href="/a_1/public/admin/index.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold hover:bg-gray-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Volver 
        </a>
    </div>

    <!-- Formulario para agregar nuevo método de pago -->
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8 border-l-4 border-[#001A33]">
        <h2 class="text-2xl font-bold text-[#001A33] mb-4">
            <i class="fas fa-plus-circle mr-2"></i> Agregar Nuevo Método de Pago
        </h2>
        <form action="../../actions/save_pay.php" method="POST" class="max-w-lg">
            <div class="mb-4">
                <label for="nuevo_metodo_pago" class="block text-gray-700 font-bold mb-2">
                    Nombre del Método de Pago:
                </label>
                <input type="text" name="nuevo_metodo_pago" id="nuevo_metodo_pago" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#001A33] focus:border-[#001A33] transition"
                    placeholder="Ej: Tarjeta de Crédito, PayPal, Efectivo...">
            </div>
            
            <button type="submit" class="bg-[#001A33] text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-800 transition 
                flex items-center justify-center w-full md:w-auto">
                <i class="fas fa-save mr-2"></i> Guardar Método de Pago
            </button>
        </form>
    </div>

    <!-- Tabla de métodos de pago existentes -->
    <div class="bg-white p-6 rounded-xl shadow-xl mb-8">
        <h2 class="text-2xl font-bold text-[#001A33] mb-4">
            <i class="fas fa-list mr-2"></i> Métodos de Pago Disponibles
        </h2>
        
        <?php if (empty($metodos_pago)): ?>
            <div class="bg-blue-100 text-blue-700 p-4 rounded-lg">
                <i class="fas fa-info-circle mr-2"></i> No hay métodos de pago registrados. Agrega uno nuevo utilizando el formulario de arriba.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse bg-gray-50 text-left rounded-lg shadow-lg overflow-hidden">
                    <thead class="bg-[#001A33] text-white">
                        <tr>
                            
                            <th class="p-3">Método de Pago</th>
                            <th class="p-3 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($metodos_pago as $metodo): ?>
                        <tr class="border-b border-gray-200 hover:bg-gray-100 transition">
                            
                            <td class="p-3 font-medium"><?= $metodo['tipo'] ?></td>
                            <td class="p-3 text-center">
                                <button onclick="openEditModal(<?= $metodo['id'] ?>, '<?= $metodo['tipo'] ?>')" 
                                    class="bg-gray-600 text-white px-3 py-1 rounded inline-flex items-center mr-2 hover:bg-gray-900 transition">
                                    <i class="fas fa-edit mr-1"></i> Editar
                                </button>
                                <button onclick="confirmDelete(<?= $metodo['id'] ?>, '<?= $metodo['tipo'] ?>')" 
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
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden animate__animated animate__fadeIn">
    <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4 animate__animated animate__zoomIn">
        <div class="flex justify-between items-center mb-4 pb-2 border-b">
            <h3 class="text-xl font-bold text-[#001A33]">
                <i class="fas fa-edit mr-2"></i> Editar Método de Pago
            </h3>
            <button onclick="closeEditModal()" class="text-gray-600 hover:text-red-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <form id="editForm" action="../../actions/update_pay.php" method="POST">
            <input type="hidden" id="editId" name="id">
            <div class="mb-4">
                <label for="edit_tipo" class="block text-gray-700 font-bold mb-2">
                    Nombre del Método de Pago:
                </label>
                <input type="text" id="editTipo" name="tipo" required 
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#001A33] focus:border-[#001A33] transition">
            </div>
            
            <div class="flex justify-end space-x-3 mt-4">
                <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    <i class="fas fa-times mr-2"></i> Cancelar
                </button>
                <button type="submit" class="px-4 py-2 bg-[#001A33] text-white rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-save mr-2"></i> Guardar Cambios
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Script para las funcionalidades -->
<script>
    // Modal de edición
    function openEditModal(id, tipo) {
        document.getElementById('editId').value = id;
        document.getElementById('editTipo').value = tipo;
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
    
    // Confirmación de eliminación
    function confirmDelete(id, nombre) {
        Swal.fire({
            title: '¿Eliminar método de pago?',
            html: `¿Estás seguro que deseas eliminar el método de pago <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
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
                window.location.href = `../../actions/delete_pay.php?id=${id}`;
            }
        });
    }
    
    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
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

<!-- Agregar animaciones CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

<?php
include_once '../templates/footeradmin.php';
?>