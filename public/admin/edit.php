<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../LoginAdmin.php");
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
?>


<div class="flex items-center justify-center min-h-screen py-8">
    <div class="bg-white shadow-2xl rounded-2xl p-8 max-w-2xl w-full">
        <h2 class="text-center text-3xl font-bold mb-6 text-[#001A33]">Editar Empleado</h2>
        <form action="../../actions/update.php" method="POST" enctype="multipart/form-data" class="space-y-4">
        <input type="hidden" name="tipo" value="empleado">
            <input type="hidden" name="id" value="<?= $empleado['id'] ?>">
            
            <!-- Foto de Perfil -->
            <div class="py-4 flex flex-col items-center">
        <div class="relative group">
        <img id="preview" src="../../uploads/<?= $empleado['foto_de_perfil'] ?>" alt="Fotodeperfil" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 group-hover:opacity-75 bg-black bg-opacity-40" />
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <label for="foto_de_perfil" class="text-white px-4 py-2 rounded-lg cursor-pointer">
                    Cambiar foto
                </label>
            </div>
        </div>
        <input type="file" id="foto_de_perfil" name="foto_de_perfil" accept="image/*" class="hidden" onchange="previewImage(event)" />
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