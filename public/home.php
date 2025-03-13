<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>BELLA - Barbería & Estilistas</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');
        
        .font-heading { font-family: 'Poppins', sans-serif; }
        .font-body { font-family: 'Inter', sans-serif; }
        
        .hover-zoom {
            transition: transform 0.3s ease;
        }
        .hover-zoom:hover {
            transform: scale(1.03);
        }
    </style>
</head>
<body class="font-body bg-white">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <h1 class="font-heading text-3xl font-bold text-gray-900">
                    <span class="text-emerald-600">BELLA</span> 
                    <span class="text-gray-800">HAIR</span>
                </h1>
                </div>
                                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="../public/home.php" class="text-gray-600  hover:text-emerald-600">Inicio</a>
                    <a href="../public/servicios.php" class="text-gray-600 hover:text-emerald-600">Servicios</a>
                    <a href="../public/home.php#equipo" class="text-gray-600 hover:text-emerald-600">Equipo</a>
                    <a href="../public/home.php#contacto" class="text-gray-600 hover:text-emerald-600">Contacto</a>
                    <a href="../public/login.php" class="bg-black text-white px-6 py-2 rounded-full hover:bg-gray-800 transition-all">
                        Reservar
                    </a>
                </div>
                
                <button id="menuButton" class="md:hidden text-gray-600">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-20 relative h-screen flex items-center">
        <img src="../assets/images/FondoPeluqueria.jpg" alt="Barbería" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="relative max-w-7xl mx-auto px-4 text-center">
            <h1 class="font-heading text-5xl md:text-6xl text-white mb-6 leading-tight">
                Precision y Estilo<br>en Cada Detalle
            </h1>
            <p class="text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
                Experiencia premium en cortes modernos y cuidados personalizados
            </p>
            <a href="/templates/reservation.html" class="inline-block bg-white text-black px-8 py-3 rounded-full hover:bg-gray-100 transition-all font-semibold">
                Reservar Ahora
            </a>
        </div>
    </div>

    <!-- Equipo -->
    <section class="py-20 bg-white" id="equipo">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="font-heading text-4xl font-bold mb-4">Nuestro Equipo</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Profesionales experimentados listos para brindarte el mejor servicio.</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Estilista 1 -->
                <div class="text-center hover-zoom">
                    <div class="h-80 bg-gray-300 rounded-xl mb-4 overflow-hidden">
                        <!-- Aquí iría la imagen del estilista -->
                        <img src="../assets/images/barbero1.webp" alt="Barbero" class="w-full h-full object-cover">
                    </div>
                    <h3 class="font-heading text-xl font-semibold">Alejandro Pérez</h3>
                    <p class="text-emerald-600">Master Barber</p>
                </div>
                
                <!-- Estilista 2 -->
                <div class="text-center hover-zoom">
                    <div class="h-80 bg-gray-300 rounded-xl mb-4 overflow-hidden">
                        <!-- Aquí iría la imagen del estilista -->
                        <img src="../assets/images/barbero2.jpg" alt="Barbero" class="w-full h-full object-cover">

                    </div>
                    <h3 class="font-heading text-xl font-semibold">Carlos Mendoza</h3>
                    <p class="text-emerald-600">Estilista Senior</p>
                </div>
                
                <!-- Estilista 3 -->
                <div class="text-center hover-zoom">
                    <div class="h-80 bg-gray-300 rounded-xl mb-4 overflow-hidden">
                        <!-- Aquí iría la imagen del estilista -->
                        <img src="../assets/images/barbero3.jpg" alt="Barbero" class="w-full h-full object-cover">

                    </div>
                    <h3 class="font-heading text-xl font-semibold">Martín Rodríguez</h3>
                    <p class="text-emerald-600">Experto en Barba</p>
                </div>
                

            </div>
        </div>
    </section>
    


 <section class="py-16 bg-gray-50" id="servicios">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-4xl font-bold text-center mb-12">Nuestros Servicios Especializados</h2>
        
        <!-- Tarjeta Principal del SERVICIO-->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-12">
            <div class="grid md:grid-cols-2">
                <div class="p-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">BELLA HAIR - Tu Salón de Belleza de Confianza</h3>
                    <p class="text-gray-600 mb-6">
                        Con más de una década de experiencia, BELLA HAIR se ha convertido en el referente de belleza y estilo en la ciudad. Nuestro equipo de estilistas profesionales está certificado en las últimas técnicas y tendencias internacionales.
                    </p>
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Productos premium importados</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Personal altamente capacitado</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-emerald-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Ambiente moderno y relajante</span>
                        </div>
                    </div>
                    <div class="bg-emerald-600 p-4 rounded-lg">
                        <p class="text-white font-semibold">¡Primera visita! 20% de descuento en cualquier servicio</p>
                    </div>
                </div>
                <div class="relative h-64 md:h-auto">
                    <img src="../assets/images/SERVICIOS.jpg" alt="Salón" class="absolute inset-0 w-full h-full object-cover"/>
                </div>
            </div>
        </div>
    </div>
    </section>

    <section class="py-16 bg-gray-50" id="contacto">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center mb-16">
                <span class="text-gray-500 font-semibold">Contáctanos</span>
                <h2 class="font-heading text-3xl text-black mt-2">Comparte tus aventuras con nosotros</h2>
            </div>
    
            <!-- Sección de mensajes de éxito o error -->
            <div class="flex justify-center mb-6" id="messageSection">
                <div class="lg:w-2/3 w-full">
                    <!-- Mensaje de éxito -->
                    <div id="successMessage" class="alert alert-success alert-dismissible fade show" role="alert" style="display:none;">
                        Tu mensaje se ha enviado con éxito.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
    
                    <!-- Mensaje de error -->
                    <div id="errorMessage" class="alert alert-danger alert-dismissible fade show" role="alert" style="display:none;">
                        Ha ocurrido un error al enviar el mensaje. Por favor, inténtalo de nuevo.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                </div>
            </div>
    
            <!-- Sección de formulario de contacto -->
            <div class="flex justify-center">
                <div class="lg:w-4/4 w-full py-2 px-6" >
                    <div class="contact-form bg-white p-4 rounded-lg shadow-lg">
                        <form id="contactForm" action="enviarMensaje.php" method="POST" onsubmit="return validateForm()">
                            <div class="grid grid-cols- sm:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <input type="text" class="form-control w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" name="nombre" placeholder="Nombre" required>
                                    <small id="nameError" class="form-text text-red-500"></small>
                                </div>
                                <div class="form-group">
                                    <input type="email" class="form-control w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" name="correo" placeholder="Correo" required>
                                    <small id="emailError" class="form-text text-red-500"></small>
                                </div>
                            </div>
                          <!-- Sección del asunto -->
                        <div class="form-section mb-4">
                            <label for="subject" class="block text-sm font-medium text-white">Motivo</label>
                            <input type="text" class="form-control w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="subject" name="asunto" placeholder="Motivo" required>
                            <small id="subjectError" class="form-text text-red-500"></small>
                        </div>

                        <!-- Sección del mensaje -->
                        <div class="form-section mb-4">
                            <textarea class="form-control w-full p-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" id="message" name="mensaje" rows="5" placeholder="Mensaje" required></textarea>
                            <small id="messageError" class="form-text text-red-500"></small>
                        </div>

                            <div class="text-center">
                                <button type="submit" class="bg-[#001A33] text-white py-2 px-6 rounded-md mt-3 hover:bg-[#001A33] focus:outline-none focus:ring-2 focus:ring-blue-500">Enviar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
    
    <!-- Footer -->
    <footer class="bg-black text-white py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="space-y-4">
                    <h4 class="font-heading text-xl font-semibold">BELLA</h4>
                    <p class="text-gray-400">Barbería y Estilismo Moderno</p>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4">Horario</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li>Lun-Vie: 9am - 8pm</li>
                        <li>Sábado: 9am - 6pm</li>
                        <li>Domingo: Cerrado</li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4">Contacto</h5>
                    <ul class="space-y-2 text-gray-400">
                        <li>Av. Libertador 1234</li>
                        <li>hola@bella.com</li>
                        <li>+54 11 5678-9012</li>
                    </ul>
                </div>
                
                <div>
                    <h5 class="font-semibold mb-4">Síguenos</h5>
                    <div class="flex space-x-4">
                        <a href="#" class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="prev-step px-6 py-3 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 hover:scale-105 transform transition-all duration-200 flex items-center">

                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3 8h-1.35c-.538 0-.65.221-.65.778v1.222h2l-.209 2h-1.791v7h-3v-7h-2v-2h2v-2.308c0-1.769.931-2.692 3.029-2.692h1.971v3z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 BELLA Barbería. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>


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
    </script>
</body>
</html>