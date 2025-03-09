<?php
require '../../config/confg.php';
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Consulta para obtener todos los servicios ordenados por tipo
$stmt = $pdo->query("SELECT id, tipo FROM servicios ORDER BY tipo ASC");
$servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
$stmt = $pdo->query("SELECT id, tipo FROM metodo_de_pago");
$metodos_pago = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once '../templates/headeradmin.php';
include_once '../templates/navbaradmin.php';
?>

    <div class="flex items-center justify-center min-h-screen pt-4"> <!-- Cambié pt-4 a pt-0 aquí -->
        <div class="relative flex flex-col space-y-10 bg-white shadow-2xl rounded-2xl md:flex-row ">
            <div class="flex flex-col justify-center p-8 md:p-14  space-y-20">
                <h2 class="text-center text-2xl md:text-3xl font-bold mb-4 text-[#001A33]">Agregar Negocio</h2>
                <form action="../../actions/store.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="negocio">

                    <div class="py-4 flex flex-col items-center">
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
                    </div>

                    <input type="text" name="nombrenegocio" placeholder="Nombre del Negocio" required class="w-full p-2 border rounded mb-2">
                    <input type="adress" name="ubicaciondelnegocio" placeholder="Dirección" required class="w-full p-2 border rounded mb-2">
                    <input type="tel" name="phonenegocio" placeholder="Número de Teléfono" required class="w-full p-2 border rounded mb-2" pattern="[0-9]{10}" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    <input type="email" name="emailnegocio" placeholder="Correo electronico" required class="w-full p-2 border rounded mb-2">
                    <!-- Input para seleccionar días laborables -->
                    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                    <label for="dias_operacion" class="block text-gray-700 font-bold mb-2">Días de operación:</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="lunes" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Lunes</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="martes" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Martes</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="miercoles" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Miércoles</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="jueves" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Jueves</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="viernes" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Viernes</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="sabado" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Sábado</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="dias_operacion[]" value="domingo" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Domingo</span>
                        </label>
                    </div>
                    </div>

                    <!-- Input para horario de trabajo -->
                    <div class="bg-white p-4 rounded-lg shadow-md">
                    <label for="horas_operacion" class="block text-gray-700 font-bold mb-2">Horario de operación:</label>
                    <div class="flex flex-wrap items-center space-x-2">
                        <span class="text-gray-700">De</span>
                        <input type="time" id="horas_operacion" name="horas_operacion" required class="form-input px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span class="text-gray-700">a</span>
                        <input type="time" id="horas_fin" name="horas_fin" required class="form-input px-4 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    </div>
                    <select name="tipodenegocio" required class="w-full p-2 border rounded mb-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="barberia">Barbería</option>
                        <option value="estetica">Estética</option>
                    </select>
                    <!-- Input para seleccionar servicios ofrecidos -->
                    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
    <label for="servicios" class="block text-gray-700 font-bold mb-2">Servicios ofrecidos:</label>
    <div class="space-y-2">
        <?php if (empty($servicios)): ?>
            <p class="text-gray-500 italic">No hay servicios disponibles en este momento.</p>
        <?php else: ?>
            <?php foreach ($servicios as $servicio): ?>
                <label class="inline-flex items-center">
                    <input type="checkbox" name="servicios[]" value="<?= $servicio['id'] ?>" class="form-checkbox h-5 w-5 text-blue-600">
                    <span class="ml-2 text-gray-700"><?= htmlspecialchars($servicio['tipo']) ?></span>
                </label>
            <?php endforeach; ?>
        <?php endif; ?>
                    <!-- Input para seleccionar métodos de pago -->
                    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
    <label for="metodo_de_pago_id" class="block text-gray-700 font-bold mb-2">Método de pago aceptado:</label>
    <div class="space-y-2">
        <?php foreach ($metodos_pago as $metodo): ?>
            <label class="inline-flex items-center">
                <input type="radio" name="metodo_de_pago_id" value="<?= htmlspecialchars($metodo['id']) ?>" class="form-radio h-5 w-5 text-blue-600">
                <span class="ml-2 text-gray-700"><?= htmlspecialchars($metodo['tipo']) ?></span>
            </label>
        <?php endforeach; ?>
    </div>
</div>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Guardar</button>
                </form>
                <a href="/a_1/public/admin/index.php" class="text-blue-500 mt-4 block">Volver</a>
            </div>
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

        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>

<?php
include_once '../templates/footeradmin.php';
?>