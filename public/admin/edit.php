<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID de empleado no proporcionado");
}

$id = $_GET['id'];

try {
    // Obtener datos del empleado
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE id = ?");
    $stmt->execute([$id]);
    $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$empleado) {
        die("Empleado no encontrado");
    }

    // Obtener métodos de pago
    $stmt = $pdo->query("SELECT id, tipo FROM metodo_de_pago");
    $metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener lista de negocios
    $stmt = $pdo->query("SELECT id, nombrenegocio FROM negocio");
    $negocios = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
include_once '../templates/mode.php';
?>


<div class="flex items-center justify-center min-h-screen py-8">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-2xl w-full">
        <h2 class="text-center text-3xl font-bold mb-6 text-[#001A33]">Editar Empleado</h2>
        <form action="../../actions/update.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="tipo" value="empleado">
        <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
            
        <div class="py-4 flex flex-col items-center">
            <!-- Imagen de perfil -->
            <div class="py-4 flex flex-col items-center">
    <div class="relative group w-32 h-32 rounded-full border-4 border-gray-200 bg-black bg-opacity-40 flex items-center justify-center">
        <img id="preview" src="/a_1/actions/mostrar_img.php?id=<?php echo $empleado['id']; ?>" alt="Foto de perfil" class="w-full h-full rounded-full object-cover hidden" onerror="this.classList.add('hidden')" onload="this.classList.remove('hidden')" />
        <span id="alt-text" class="text-gray-200 text-sm absolute">Cambiar foto</span>
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
        </div>
            

            <!-- Nombre y Teléfono -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nombreempleado" value="<?= $empleado['nombreempleado'] ?>" required class="input-field" />
                <input type="tel" name="phoneempleado" value="<?= $empleado['phoneempleado'] ?>" pattern="\d{10}" maxlength="10" required class="input-field" />
            </div>

            <!-- Email y Contraseña -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="email" name="email_empleado" value="<?= $empleado['email_empleado'] ?>" required class="input-field" />
                <input type="password" name="contra_empleados" placeholder="Nueva Contraseña (Opcional)" class="input-field" />
            </div>

            <!-- Edad y Negocio -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="number" name="edad" value="<?= $empleado['edad'] ?>" min="18" required class="input-field" />
                <select name="negocio_id" required class="input-field">
                    <option value="">Asigna un Negocio</option>
                    <?php foreach ($negocios as $negocio): ?>
                        <option value="<?= $negocio['id'] ?>" <?= $negocio['id'] == $empleado['negocio_id'] ? 'selected' : '' ?>><?= htmlspecialchars($negocio['nombrenegocio']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Disponibilidad y Método de Cobro -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <select name="disponibilidad" required class="input-field">
                    <option value="mañana" <?= $empleado['disponibilidad'] == 'mañana' ? 'selected' : '' ?>>Mañana - 6am-11am</option>
                    <option value="tarde" <?= $empleado['disponibilidad'] == 'tarde' ? 'selected' : '' ?>>Tarde - 12pm-17pm</option>
                    <option value="noche" <?= $empleado['disponibilidad'] == 'noche' ? 'selected' : '' ?>>Noche - 17pm-23pm</option>
                </select>

                <select name="metodo_de_cobro" required class="input-field">
                    <option value="">Método de Cobro</option>
                    <?php foreach ($metodos_pago as $metodo): ?>
                        <option value="<?= htmlspecialchars($metodo['id']) ?>" <?= $empleado['metodo_de_cobro'] == $metodo['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($metodo['tipo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Descripción -->
            <div class="mb-4 space-y-2">
                <textarea name="descripcion" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><?= $empleado['descripcion'] ?></textarea>
            </div>

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded w-full">Actualizar</button>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('preview');
        const altText = document.getElementById('alt-text'); // Capturar el texto alternativo

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.classList.remove('hidden'); // Mostrar imagen
                if (altText) altText.classList.add('hidden'); // Ocultar el texto
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