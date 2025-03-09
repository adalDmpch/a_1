<?php
require '../../config/confg.php';

try {
    $stmt = $pdo->query("SELECT id, tipo FROM metodo_de_pago");
    $metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

     // Obtener la lista de negocios
     $stmt = $pdo->query("SELECT id, nombrenegocio FROM negocio");
     $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
?>




<div class="flex items-center justify-center min-h-screen py-8">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-2xl w-full">
        <h2 class="text-center text-3xl font-bold mb-6 text-[#001A33]">Agregar Empleado</h2>
        <form action="../../actions/store.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="tipo" value="empleado">
            <!-- Foto de Perfil -->
            <div class="flex flex-col items-center">
                <label for="foto_de_perfil" class="cursor-pointer">
                    <img id="preview" src="" alt="Foto de perfil" class="w-32 h-32 rounded-full object-cover border-4 border-gray-300" />
                    <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" class="hidden" onchange="previewImage(event)" />
                </label>
            </div>

            <!-- Nombre y Teléfono -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nombreempleado" placeholder="Nombre Completo" required class="input-field" />
                <input type="tel" name="phoneempleado" placeholder="Teléfono" pattern="\d{10}" maxlength="10" required class="input-field" />
            </div>

            <!-- Email y Contraseña -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="email" name="email_empleado" placeholder="Correo Electrónico" required class="input-field" />
                <input type="password" name="contra_empleados" placeholder="Contraseña" required class="input-field" />
            </div>

            <!-- Edad  -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="number" name="edad" placeholder="Edad" min="18" required class="input-field" />
                <div class="flex flex-col">
        <label for="negocio_id" class="mb-1 font-medium text-gray-700"></label>
        <select name="negocio_id" required class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Asigna un Negocio</option>
            <?php foreach ($negocios as $negocio): ?>
                <option value="<?= $negocio['id'] ?>"><?= htmlspecialchars($negocio['nombrenegocio']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
            </div>

            <!-- metodo de pago y Disponibilidad -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <select name="disponibilidad" required class="input-field">
                    <option value="">Selecciona Disponibilidad</option>
                    <option value="mañana">Mañana - 6am-11am</option>
                    <option value="tarde">Tarde - 12pm-17pm</option>
                    <option value="noche">Noche - 17pm-23pm</option>
                </select>

                <select name="metodo_de_cobro" required class="input-field">
                    <option value="">Método de Cobro</option>
                    <?php foreach ($metodos_pago as $metodo): ?>
                         <option value="<?= htmlspecialchars($metodo['id']) ?>">
                             <?= htmlspecialchars($metodo['tipo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

           

            
           <!-- Negocio ID y Descripción -->
<div class="mb-4 space-y-2">
    <div class="mt-3">
        <textarea 
            name="descripcion" 
            placeholder="Descripción" 
            rows="3" 
            required 
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        ></textarea>
    </div>
</div>
            
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full">Guardar</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview').src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }
</script>

<style>
    .input-field {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        outline: none;
    }
</style>

<?php
include_once '../templates/footeradmin.php';
?>