<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NOIR ELITE - Acceso Exclusivo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');

        .font-heading {
            font-family: 'Poppins', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }
        
        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .form-appear {
            opacity: 0;
            transform: translateY(20px);
        }
        
        .input-field:focus {
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
        
        .btn-shine {
            position: relative;
            overflow: hidden;
        }
        
        .btn-shine::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.3) 50%,
                rgba(255, 255, 255, 0) 100%
            );
            transform: rotate(30deg);
            animation: shine 3s infinite;
        }
        
        @keyframes shine {
            0% {
                left: -100%;
            }
            20% {
                left: 100%;
            }
            100% {
                left: 100%;
            }
        }
        
        .floating {
            animation: float 4s ease-in-out infinite;
        }
        
        @keyframes float {
            0% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
            100% {
                transform: translateY(0px);
            }
        }
        
        .barber-phrase::before {
            content: """;
            font-size: 4rem;
            position: absolute;
            left: -1rem;
            top: -2rem;
            color: #e5e7eb;
            font-family: 'Poppins', sans-serif;
        }

        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
    </style>
</head>
<body class="font-body bg-gradient-to-br from-gray-900 to-gray-800 min-h-screen">
    <!-- Partículas de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="particles"></div>
    </div>
    
    <!-- Navbar -->
    <nav class="bg-black/80 backdrop-filter backdrop-blur-lg border-b border-gray-800 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold">
                        <span class="text-emerald-500">NOIR</span>
                        <span class="text-white">ELITE</span>
                    </h1>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#ayuda" class="text-gray-600 hover:text-black">Ayuda</a>
                    <a href=""
                        class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all">
                        Reservar
                    </a>
                </div>

                <button id="menuButton" class="md:hidden text-white">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="min-h-screen flex items-center justify-center pt-20">
        <div class="max-w-6xl w-full grid md:grid-cols-2 gap-12 px-4 py-12">
            <!-- Left Column - Barber Content -->
            <div class="hidden md:flex md:flex-col md:justify-center space-y-8">
                <!-- Frases Inspiradoras -->
                <div class="space-y-12">
                    <!-- Frase 1 -->
                    <div class="relative pl-8 border-l-4 border-emerald-500 transform hover:scale-[1.01] transition-all duration-300">
                        <p class="text-3xl font-playfair font-medium leading-tight text-gray-100 mb-6">
                            La excelencia en barbería no es un acto,<br>
                            <span class="text-emerald-400 font-semibold">es un arte que se cultiva</span>
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-px bg-gradient-to-r from-emerald-500 to-transparent flex-1"></div>
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-sm">
                                - Marco Rossi, Maestro Barbero
                            </span>
                        </div>
                    </div>

                    <!-- Frase 2 -->
                    <div class="relative pl-8 border-l-4 border-emerald-500 transform hover:scale-[1.01] transition-all duration-300">
                        <p class="text-3xl font-playfair font-medium leading-tight text-gray-100 mb-6">
                            Detrás de cada gran look<br>
                            <span class="text-emerald-400 font-semibold">late la pasión por el detalle</span>
                        </p>
                        <div class="flex items-center gap-4">
                            <div class="h-px bg-gradient-to-r from-emerald-500 to-transparent flex-1"></div>
                            <span class="font-medium text-gray-400 uppercase tracking-widest text-sm">
                                - Luca Bianchi, Estilista Senior
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Promoción -->
                <div class="glass p-6 rounded-lg border border-gray-700">
                    <h3 class="font-heading text-xl mb-4 text-white">¡Primera Reserva!</h3>
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
            <div class="flex items-center justify-center">
                <div class="glass p-8 rounded-xl shadow-2xl w-full max-w-md floating relative z-10 form-appear">
                    <!-- Logo/Ícono -->
                    <div class="flex justify-center mb-6">
                        <div class="w-20 h-20 rounded-full bg-emerald-900 flex items-center justify-center shadow-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="text-center mb-8">
                        <h1 class="font-heading text-3xl font-bold mb-2">
                            <span class="text-emerald-400">NOIR</span>
                            <span class="text-white">ELITE</span>
                        </h1>
                        <h2 class="text-white text-xl font-bold">Acceso Exclusivo</h2>
                        <p class="text-gray-400 text-sm mt-2">Gestiona tus reservas y preferencias de estilo</p>
                    </div>
                    
                    <form action="../actions/login.php" method="POST" class="space-y-6">
                        <div class="form-appear relative">
                            <label class="block text-gray-300 mb-2 font-medium">Correo electrónico:</label>
                            <div class="relative">
                                <input type="email" name="email" required class="input-field w-full p-3 rounded-lg bg-gray-800/50 text-white border border-gray-600 focus:border-emerald-500 focus:outline-none transition-all duration-300" placeholder="ejemplo@noir.com">
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="form-appear relative">
                            <label class="block text-gray-300 mb-2 font-medium">Contraseña:</label>
                            <div class="relative">
                                <input type="password" name="password" required class="input-field w-full p-3 rounded-lg bg-gray-800/50 text-white border border-gray-600 focus:border-emerald-500 focus:outline-none transition-all duration-300" placeholder="••••••••">
                                <div class="input-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between text-sm">
                            <div class="text-gray-300">
                                <a href="#olvide" class="text-emerald-400 hover:text-emerald-300 transition-colors">
                                    ¿Contraseña olvidada?
                                </a>
                            </div>
                        </div>

                        <div class="form-appear">
                            <button type="submit" class="btn-shine w-full bg-gradient-to-r from-emerald-700 to-emerald-900 hover:from-emerald-800 hover:to-emerald-950 text-white p-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-[1.02]">
                                Iniciar Sesión
                            </button>
                        </div>
                    </form>
                    
                    <!-- Sección Ayuda -->
                    <div class="mt-6 pt-6 border-t border-gray-700">
                        <div class="flex items-center gap-2 text-sm text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            ¿Necesitas ayuda?
                            <a href="#ayuda" class="text-emerald-400 font-semibold hover:text-emerald-300">Guía de acceso</a>
                            ó
                            <a href="#contacto" class="text-emerald-400 font-semibold hover:text-emerald-300">Contactar soporte</a>
                        </div>
                    </div>
                    
                    <!-- Círculos decorativos -->
                    <div class="absolute -top-10 -left-10 w-20 h-20 rounded-full bg-emerald-900/20"></div>
                    <div class="absolute -bottom-10 -right-10 w-20 h-20 rounded-full bg-emerald-800/20"></div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Crear partículas aleatorias
        function createParticles() {
            const particles = document.querySelector('.particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                
                // Tamaño aleatorio
                const size = Math.random() * 5 + 1;
                
                // Posición aleatoria
                const posX = Math.random() * 100;
                const posY = Math.random() * 100;
                
                // Velocidad aleatoria para la animación
                const duration = Math.random() * 20 + 10;
                const delay = Math.random() * 5;
                
                // Estilo base
                particle.style.position = 'absolute';
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.borderRadius = '50%';
                particle.style.left = `${posX}%`;
                particle.style.top = `${posY}%`;
                particle.style.backgroundColor = 'rgba(16, 185, 129, 0.2)'; // Emerald color
                particle.style.boxShadow = '0 0 10px rgba(16, 185, 129, 0.5)';
                
                // Animación
                particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                
                particles.appendChild(particle);
            }
        }
        
        // Menú móvil
        document.addEventListener('DOMContentLoaded', () => {
            const menuButton = document.getElementById('menuButton');
            
            if (menuButton) {
                menuButton.addEventListener('click', () => {
                    const mobileMenu = document.createElement('div');
                    mobileMenu.className = 'fixed inset-0 bg-black/90 z-50 flex flex-col items-center justify-center';
                    mobileMenu.innerHTML = `
                        <button class="absolute top-5 right-5 text-white">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="flex flex-col items-center space-y-8">
                            <a href="#ayuda" class="text-gray-300 hover:text-white text-xl">Ayuda</a>
                            <a href="reserva" class="bg-emerald-600 text-white px-8 py-3 rounded-full hover:bg-emerald-700 transition-all text-xl">
                                Reservar
                            </a>
                        </div>
                    `;
                    
                    document.body.appendChild(mobileMenu);
                    
                    const closeButton = mobileMenu.querySelector('button');
                    closeButton.addEventListener('click', () => {
                        document.body.removeChild(mobileMenu);
                    });
                    
                    const links = mobileMenu.querySelectorAll('a');
                    links.forEach(link => {
                        link.addEventListener('click', () => {
                            document.body.removeChild(mobileMenu);
                        });
                    });
                });
            }
            
            // Crear partículas
            createParticles();
            
            // Animación de la tarjeta y elementos del formulario
            const card = document.querySelector('.glass');
            const formElements = document.querySelectorAll('.form-appear');
            
            gsap.from(card, {
                duration: 1.2,
                y: 50,
                opacity: 0,
                ease: "power3.out"
            });
            
            gsap.to(formElements, {
                duration: 0.7,
                y: 0,
                opacity: 1,
                stagger: 0.15,
                delay: 0.5,
                ease: "power3.out"
            });
        });
    </script>
</body>
</html>