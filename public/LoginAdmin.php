<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <style>
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
        
        .input-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 to-gray-800 flex justify-center items-center h-screen overflow-hidden">
    <!-- Partículas de fondo -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="particles"></div>
    </div>
    
    <!-- Card principal -->
    <div class="glass p-8 rounded-xl shadow-2xl w-96 floating relative z-10">
        <!-- Logo/Ícono -->
        <div class="flex justify-center mb-6">
            <div class="w-20 h-20 rounded-full bg-black flex items-center justify-center shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
        </div>
        
        <h2 class="text-white text-2xl font-bold mb-6 text-center form-appear">My name is?</h2>
        
        <form action="../actions/login.php" method="POST" class="space-y-6">
            <div class="form-appear relative">
                <label class="block text-gray-300 mb-2 font-medium">Correo:</label>
                <div class="relative">
                    <input type="email" name="email" required class="input-field w-full p-3 rounded-lg bg-gray-800/50 text-white border border-gray-600 focus:border-black focus:outline-none transition-all duration-300">
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
                    <input type="password" name="password" required class="input-field w-full p-3 rounded-lg bg-gray-800/50 text-white border border-gray-600 focus:border-black focus:outline-none transition-all duration-300">
                    <div class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="form-appear">
                <button type="submit" class="btn-shine w-full bg-gradient-to-r from-black to-gray-800 hover:from-black hover:to-gray-900 text-white p-3 rounded-lg font-medium transition-all duration-300 transform hover:scale-[1.02]">
                    Iniciar Sesión
                </button>
            </div>
        </form>
        
        <!-- Círculos decorativos -->
        <div class="absolute -top-10 -left-10 w-20 h-20 rounded-full bg-black/20"></div>
        <div class="absolute -bottom-10 -right-10 w-20 h-20 rounded-full bg-gray-800/20"></div>
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
                particle.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
                particle.style.boxShadow = '0 0 10px rgba(255, 255, 255, 0.5)';
                
                // Animación
                particle.style.animation = `float ${duration}s ease-in-out ${delay}s infinite`;
                
                particles.appendChild(particle);
            }
        }
        
        // Animación de entrada
        window.addEventListener('DOMContentLoaded', () => {
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