<?php

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');

        .font-heading {
            font-family: 'Poppins', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }

        .barber-phrase::before {
            content: "“";
            font-size: 4rem;
            position: absolute;
            left: -1rem;
            top: -2rem;
            color: #e5e7eb;
            font-family: 'Poppins', sans-serif;
        }

        .help-tooltip {
            transition: all 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }

        .help-button:hover .help-tooltip {
            opacity: 1;
            visibility: visible;
        }
    </style>
    <title>Login Cliente</title>
</head>

<body class="font-body bg-white">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                        <span class="text-emerald-600">NOIR</span>
                        <span class="text-gray-800">ELITE</span>
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#ayuda" class="text-gray-600 hover:text-black">Ayuda</a>
                    <a href=""
                        class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all">
                        Reservar
                    </a>
                </div>

                <button id="menuButton" class="md:hidden text-gray-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center pt-20">
        <div class="max-w-6xl w-full grid md:grid-cols-2 gap-12 px-4 py-12">
            <!-- Left Column - Barber Content -->
            <div class="hidden md:block space-y-8 border-r pr-8">

                <!-- Frases Inspiradoras -->
                <div class="space-y-12">
                    <!-- Frase 1 -->
                    <div
                        class="relative pl-8 border-l-4 border-black transform hover:scale-[1.01] transition-all duration-300">

                        <p class="text-3xl font-playfair font-medium leading-tight text-gray-800 mb-6">
                            La excelencia en barbería no es un acto,<br>
                            <span class="text-black font-semibold">es un arte que se cultiva</span>
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-px bg-gradient-to-r from-black to-transparent flex-1"></div>
                            <span class="font-medium text-gray-500 uppercase tracking-widest text-sm">
                                - Marco Rossi, Maestro Barbero
                            </span>
                        </div>
                    </div>

                    <!-- Frase 2 -->
                    <div
                        class="relative pl-8 border-l-4 border-black transform hover:scale-[1.01] transition-all duration-300">
                        <p class="text-3xl font-playfair font-medium leading-tight text-gray-800 mb-6">
                            Detrás de cada gran look<br>
                            <span class="text-black font-semibold">late la pasión por el detalle</span>
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-px bg-gradient-to-r from-black to-transparent flex-1"></div>
                            <span class="font-medium text-gray-500 uppercase tracking-widest text-sm">
                                - Luca Bianchi, Estilista Senior
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Promoción -->
                <div class="bg-black text-white p-6 rounded-lg">
                    <h3 class="font-heading text-xl mb-4">¡Primera Reserva!</h3>
                    <p class="text-gray-300 mb-4">
                        Regístrate ahora y obtén un 20% de descuento en tu primer servicio
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="../public/registro.php"
                            class="bg-white text-black px-4 py-2 rounded-full hover:bg-gray-100 transition-all">
                            Crear Cuenta
                        </a>
                        <span class="text-sm text-gray-400">Válido por 7 días</span>
                    </div>
                </div>
            </div>

            <!-- Right Column - Login Form -->
            <div class="space-y-8">
                <!-- Encabezado -->
                <div class="text-center">
                    <img src="../assets/images/logobarber.png.jpg" alt="Logo Noir"
                        class="mx-auto h-24 w-24 rounded-full border-2 border-gray-200">
                    <div class="flex flex-col items-center justify-center text-center w-full mx-auto px-4 py-6">
                        <div class="flex items-center justify-center space-x-2 sm:space-x-3">
                            <h1 class="font-heading text-3xl font-bold">
                                <span class="text-emerald-600">NOIR</span>
                                <span class="text-gray-800">ELITE</span>
                            </h1>
                        </div>
                        <h2 class="mt-6 font-heading text-3xl font-bold text-gray-900 w-full">
                            Acceso Exclusivo
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 max-w-md mx-auto">
                            Gestiona tus reservas y preferencias de estilo
                        </p>
                    </div>

                </div>


                <!-- Formulario -->
                <form action="../actions/login.php" method="POST" class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Correo electrónico
                        </label>
                        <input type="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all"
                            placeholder="ejemplo@noir.com">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contraseña
                        </label>
                        <input type="password" name="password" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-black transition-all"
                            placeholder="••••••••">
                    </div>

                    <div class="flex items-center justify-between">

                        <div class="text-sm">
                            <a href="#olvide" class="font-medium text-black hover:text-gray-700">
                                ¿Contraseña olvidada?
                            </a>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-black text-white px-4 py-3 rounded-full font-medium hover:bg-gray-800 transition-colors">
                        Iniciar Sesión
                    </button>

                </form>

                <!-- Sección Ayuda -->
                <div class="border-t pt-6">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        ¿Necesitas ayuda?
                        <a href="#ayuda" class="text-black font-semibold hover:underline">Guía de acceso</a>
                        ó
                        <a href="#contacto" class="text-black font-semibold hover:underline">Contactar soporte</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Menú móvil
        const menuButton = document.getElementById('menuButton');
        const mobileMenu = document.getElementById('mobileMenu');

        menuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Mostrar/ocultar tooltip ayuda
        const helpButton = document.querySelector('.help-button');
        const helpTooltip = document.querySelector('.help-tooltip');

        helpButton.addEventListener('mouseenter', () => {
            helpTooltip.classList.remove('opacity-0', 'invisible');
            helpTooltip.classList.add('opacity-100', 'visible');
        });

        helpButton.addEventListener('mouseleave', () => {
            helpTooltip.classList.remove('opacity-100', 'visible');
            helpTooltip.classList.add('opacity-0', 'invisible');
        });
    </script>
</body>

</html>