<?php
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
    <title>Agregar Negocio</title>
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
                <h2 class="text-center text-2xl md:text-3xl font-bold mb-4 text-[#001A33]">Agregar Negocio</h2>
                <form action="../../actions/store.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="tipo" value="negocio">
                <div class="py-4 flex flex-col items-center">
    <!-- Foto de Perfil -->
    <div class="flex flex-col items-center">
                <label for="logo" class="cursor-pointer">
                    <img id="preview" src="" alt="Logo" class="w-32 h-32 rounded-full object-cover border-4 border-gray-300" />
                    <input type="file" id="logo" name="logo" accept="image/*" class="hidden" onchange="previewImage(event)" />
                </label>
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
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="cortes" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Cortes de cabello</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="barberia" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Barbería</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="manicure" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Manicure</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="pedicure" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Pedicure</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="maquillaje" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Maquillaje</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="tratamientos_faciales" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Tratamientos faciales</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="depilacion" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Depilación</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="checkbox" name="servicios[]" value="masajes" class="form-checkbox h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Masajes</span>
                        </label>
                        <!-- Campo para agregar otro servicio personalizado -->
                        <div class="mt-2 flex items-center">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="servicios[]" value="otro" class="form-checkbox h-5 w-5 text-blue-600">
                            <span class="ml-2 text-gray-700">Otro servicio:</span>
                        </label>
                        <input type="text" name="otro_servicio" class="ml-2 form-input px-2 py-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Especificar">
                        </div>
                    </div>
                    </div>
                    <!-- Input para seleccionar métodos de pago -->
                    <div class="bg-white p-4 rounded-lg shadow-md mb-4">
                    <label for="metodo_de_pago_id" class="block text-gray-700 font-bold mb-2">Método de pago aceptado:</label>
                    <div class="space-y-2">
                        <!-- Radio buttons para método de pago (solo se puede seleccionar uno) -->
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="1" class="form-radio h-5 w-5 text-blue-600" checked>
                        <span class="ml-2 text-gray-700">Efectivo</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="2" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Tarjeta de débito</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="3" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Tarjeta Credito</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="2" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">PayPal</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="5" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Mercado Pago</span>
                        </label>
                        <label class="inline-flex items-center">
                        <input type="radio" name="metodo_de_pago_id" value="6" class="form-radio h-5 w-5 text-blue-600">
                        <span class="ml-2 text-gray-700">Efectivo y Tarjeta</span>
                        </label>
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
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.getElementById('preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}
    </script>
</body>
</html>
