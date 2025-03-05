<?php
require '../../config/confg.php';
// Supón que ya tienes una conexión a la base de datos y un ID de negocio
$negocio_id = $_GET['id']; // o lo que sea necesario para identificar el negocio

// Recuperamos los datos del negocio
$stmt = $pdo->prepare("SELECT * FROM negocio WHERE id = ?");
$stmt->execute([$negocio_id]);
$negocio = $stmt->fetch();

// Recuperamos los días de operación y servicios ofrecidos
$servicios = explode(", ", $negocio['servicios']);
$dias_operacion = explode(", ", $negocio['dias_operacion']);


session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../public/LoginAdmin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Negocio</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

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
                <a href="/a_1/public/admin/index.php" class="text-gray-800 hover:text-pink-500">Inicio</a>
                <a href="perfil.html" class="text-pink-500">Mi Perfil</a>
            </div>
        </div>

        <div id="mobileMenu" class="hidden md:hidden pb-4">
            <div class="flex flex-col space-y-4">
                <a href="/a_1/public/admin/index.php" class="text-gray-800 hover:text-pink-500">Inicio</a>
                <a href="perfil.html" class="text-pink-500">Mi Perfil</a>
            </div>
        </div>
    </div>
</nav>

<body class="bg-gray-100">
    <div class="flex items-center justify-center min-h-screen pt-4"> <!-- Cambié pt-4 a pt-0 aquí -->
        <div class="relative flex flex-col space-y-10 bg-white shadow-2xl rounded-2xl md:flex-row ">
            <div class="flex flex-col justify-center p-8 md:p-14  space-y-20">
                <h2 class="text-center text-2xl md:text-3xl font-bold mb-4 text-[#001A33]">Editar Negocio</h2>
                <form action="../../actions/update.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="tipo" value="negocio">
    <input type="hidden" name="id" value="<?= $negocio['id'] ?>"> <!-- ID del negocio -->
    <div class="py-4 flex flex-col items-center">
        <div class="relative group">
        <img id="preview" src="../../uploads/<?= $negocio['logo'] ?>" alt="Logo del negocio" class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 group-hover:opacity-75 bg-black bg-opacity-40" />
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100">
                <label for="logo" class="text-white px-4 py-2 rounded-lg cursor-pointer">
                    Cambiar foto
                </label>
            </div>
        </div>
        <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewImage(event)" />
    </div>
    <input type="text" name="nombrenegocio" value="<?= $negocio['nombrenegocio'] ?>" required class="w-full p-2 border rounded mb-2">
    <input type="text" name="ubicaciondelnegocio" value="<?= $negocio['ubicaciondelnegocio'] ?>" required class="w-full p-2 border rounded mb-2">
    <input type="tel" name="phonenegocio" value="<?= $negocio['phonenegocio'] ?>" required class="w-full p-2 border rounded mb-2">
    <input type="email" name="emailnegocio" value="<?= $negocio['emailnegocio'] ?>" required class="w-full p-2 border rounded mb-2">

    <!-- Recuperar y marcar los días de operación -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <label for="dias_operacion" class="block text-gray-700 font-bold mb-2">Días de operación:</label>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
            <?php
            $dias_semana = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
            foreach ($dias_semana as $dia) {
                $checked = in_array($dia, $dias_operacion) ? 'checked' : '';
                echo "<label class='inline-flex items-center'>
                        <input type='checkbox' name='dias_operacion[]' value='$dia' class='form-checkbox h-5 w-5 text-blue-600' $checked>
                        <span class='ml-2 text-gray-700'>" . ucfirst($dia) . "</span>
                      </label>";
            }
            ?>
        </div>
    </div>
    <!-- Recuperar y marcar el horario de operación -->
    <div class="bg-white p-4 rounded-lg shadow-md">
        <label for="horas_operacion" class="block text-gray-700 font-bold mb-2">Horario de operación:</label>
        <div class="flex flex-wrap items-center space-x-2">
            <span class="text-gray-700">De</span>
            <input type="time" id="horas_operacion" name="horas_operacion" required class="form-input px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= $negocio['horas_operacion'] ?>">
            <span class="text-gray-700">a</span>
            <input type="time" id="horas_fin" name="horas_fin" required class="form-input px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= $negocio['horas_fin'] ?>">
        </div>
    </div>
    <select name="tipodenegocio" required class="w-full p-2 border rounded mb-2">
        <option value="barberia" <?= $negocio['tipodenegocio'] == 'barberia' ? 'selected' : '' ?>>Barbería</option>
        <option value="estetica" <?= $negocio['tipodenegocio'] == 'estetica' ? 'selected' : '' ?>>Estética</option>
    </select>
<!-- Recuperar y marcar los servicios ofrecidos -->
<div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <label for="servicios" class="block text-gray-700 font-bold mb-2">Servicios ofrecidos:</label>
        <div class="space-y-2">
            <?php
            $todos_servicios = ['cortes', 'barberia', 'manicure', 'pedicure', 'maquillaje', 'tratamientos_faciales', 'depilacion', 'masajes'];
            foreach ($todos_servicios as $servicio) {
                $checked = in_array($servicio, $servicios) ? 'checked' : '';
                echo "<label class='inline-flex items-center'>
                        <input type='checkbox' name='servicios[]' value='$servicio' class='form-checkbox h-5 w-5 text-blue-600' $checked>
                        <span class='ml-2 text-gray-700'>" . ucfirst($servicio) . "</span>
                      </label>";
            }
            ?>
            <div class="mt-2 flex items-center">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="servicios[]" value="otro" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700">Otro servicio:</span>
                </label>
                <input type="text" name="otro_servicio" class="ml-2 form-input px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Especificar" value="<?= in_array('otro', $servicios) ? '' : '' ?>">
            </div>
        </div>
    </div>

    <!-- Recuperar y marcar el método de pago -->
    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
        <label for="metodo_de_pago_id" class="block text-gray-700 font-bold mb-2">Método de pago aceptado:</label>
        <div class="space-y-2">
            <?php
            $metodos_pago = ['1' => 'Efectivo', '2' => 'Tarjeta de débito', '3' => 'Tarjeta Crédito', '4' => 'PayPal', '5' => 'Mercado Pago', '6' => 'Efectivo y Tarjeta'];
            foreach ($metodos_pago as $id => $metodo) {
                $checked = $negocio['metodo_de_pago_id'] == $id ? 'checked' : '';
                echo "<label class='inline-flex items-center'>
                        <input type='radio' name='metodo_de_pago_id' value='$id' class='form-radio h-5 w-5 text-blue-600' $checked>
                        <span class='ml-2 text-gray-700'>$metodo</span>
                      </label>";
            }
            ?>
        </div>
    </div>

    <button type="submit" class="bg-blue-500 text-white p-2 rounded">Actualizar Negocio</button>
</form>
                <a href="index.php" class="text-blue-500 mt-4 block">Volver</a>
            </div>
        </div>
    </div>

    <script>
       function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
        document.getElementById("preview").src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}


        function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}
    </script>
</body>
</html>
