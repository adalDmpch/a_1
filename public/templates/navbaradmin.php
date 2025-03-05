<?php
// templates/navbaradmin.php
?>
<!-- Navbar -->

<style>
    /* Añadir a tus estilos existentes */
    #mobileMenu {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.6s ease-in-out, opacity 0.6s ease-in-out, padding 0.6s ease-in-out;
        opacity: 0;
        padding-top: 0;
        padding-bottom: 0;
    }
    
    #mobileMenu.active {
        max-height: 300px; /* Ajusta según el tamaño de tu menú */
        opacity: 1;
        padding-bottom: 1rem;
        padding-top: 0.5rem;
    }
</style>

<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <img src="../../assets/images/logo.png" alt="Logo" class="h-12 w-12 rounded-full"/>
                <span class="text-2xl font-bold text-gray-800 ml-2">Bella Hair</span>
            </div>
            
            <button id="menuButton" class="md:hidden text-gray-800 hover:text-pink-500">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <div class="hidden md:flex items-center space-x-8">
                <a href="index.php" class="icon-container group">
                    <i class="fas fa-home text-xl mr-2 text-gray-800 group-hover:text-pink-500"></i>
                    <span class="text-gray-800 group-hover:text-pink-500">Inicio</span>
                </a>
                <a href="perfil.html" class="icon-container rotate-effect">
                    <i class="fas fa-user-cog text-xl mr-2 text-[#001A33]"></i>
                    <span class="text-[#001A33]">Mi Perfil</span>
                </a>
                <a href="../../actions/logout.php" class="icon-container pulse-effect">
                    <i class="fas fa-sign-out-alt text-xl mr-2 text-red-500"></i>
                    <span class="text-red-500">Cerrar Sesión</span>
                </a>
            </div>
        </div>
        
        <div id="mobileMenu" class="md:hidden pb-0">
            <div class="flex flex-col space-y-4">
                <a href="index.php" class="flex items-center py-2">
                    <i class="fas fa-home text-xl mr-2 text-gray-800"></i>
                    <span class="text-gray-800 hover:text-pink-500">Inicio</span>
                </a>
                <a href="perfil.html" class="flex items-center py-2">
                    <i class="fas fa-user-cog text-xl mr-2 text-[#001A33]"></i>
                    <span class="text-[#001A33]">Mi Perfil</span>
                </a>
                <a href="../../actions/logout.php" class="flex items-center py-2">
                    <i class="fas fa-sign-out-alt text-xl mr-2 text-red-500"></i>
                    <span class="text-red-500">Cerrar Sesión</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    // Reemplaza el script actual del menú hamburguesa con esto
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('active');
        
        // Animar el ícono del menú (opcional)
        const icon = menuButton.querySelector('i');
        if (icon) {
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        }
    });
</script>