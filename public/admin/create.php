<?php
require '../../config/confg.php';

// Initialize error messages array
$errors = [];

// Check if there's an error in the session
session_start();
if (isset($_SESSION['error_message'])) {
    $errors['email'] = $_SESSION['error_message'];
    unset($_SESSION['error_message']); // Clear the error after displaying
}

try {
    $stmt = $pdo->query("SELECT id, tipo FROM metodo_de_pago");
    $metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener la lista de negocios
    $stmt = $pdo->query("SELECT id, nombrenegocio FROM negocio");
    $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
include_once '../templates/mode.php';
?>

<div class="flex items-center justify-center min-h-screen py-8">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-2xl w-full">
        <h2 class="text-center text-3xl font-bold mb-6 text-[#001A33]">Agregar Empleado</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form action="../../actions/store.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <input type="hidden" name="tipo" value="empleado">
            <!-- Imagen de perfil -->
            <div class="py-4 flex flex-col items-center">
                <div class="relative group w-32 h-32 rounded-full border-4 border-gray-200 bg-black bg-opacity-40 flex items-center justify-center">
                    <img id="preview" src="" alt="Foto de perfil" class="w-full h-full rounded-full object-cover hidden" onerror="this.classList.add('hidden')" onload="this.classList.remove('hidden')" />
                    <span id="alt-text" class="text-gray-200 text-sm absolute">Foto de perfil</span>
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100">
                        <label for="foto_de_perfil" class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-50 rounded-full transition-all duration-200 cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white opacity-0 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </label>
                    </div>  
                </div>
                <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" class="hidden" onchange="previewImage(event)" />
            </div>
            <!-- Nombre y Teléfono -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nombreempleado" placeholder="Nombre Completo" required class="input-field" />
                <input type="tel" name="phoneempleado" placeholder="Teléfono" pattern="\d{10}" maxlength="10" required class="input-field" />
            </div>

            <!-- Email y Contraseña -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <input type="email" name="email_empleado" placeholder="Correo Electrónico" required class="input-field" />
                    <?php if (isset($errors['email'])): ?>
                        <span class="text-red-500 text-sm mt-1">Este correo ya está en uso</span>
                    <?php endif; ?>
                </div>
                <input type="password" name="contra_empleados" placeholder="Contraseña" required class="input-field" />
            </div>

            <!-- Edad  -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="number" name="edad" placeholder="Edad" min="18" required class="input-field" />
                <div class="flex flex-col">
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

            <!-- Descripción -->
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
        const preview = document.getElementById('preview');
        const altText = document.getElementById('alt-text');

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (altText) altText.classList.add('hidden');
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