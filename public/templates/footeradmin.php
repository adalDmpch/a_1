<?php
// templates/footeradmin.php
include_once '../templates/mode.php';
?>
<footer class="bg-green-100/90 text-green-700 p-4 text-center mt-auto shadow-lg border-t border-green-200">
    <p>
        <i class="fas fa-copyright mr-1"></i> 2025 Bella Hair. Todos los derechos reservados.
    </p>
</footer>

<script>
    // Funcionalidad del menú hamburguesa
    const menuButton = document.getElementById('menuButton');
    const mobileMenu = document.getElementById('mobileMenu');
    
    menuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });
    
    // Mejorar la confirmación de eliminación con SweetAlert2
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const tipo = this.getAttribute('data-tipo');
            const nombre = this.getAttribute('data-nombre');
            const tipoCapitalizado = tipo.charAt(0).toUpperCase() + tipo.slice(1);
            
            Swal.fire({
                title: `¿Eliminar ${tipo}?`,
                html: `¿Estás seguro que deseas eliminar a <strong>${nombre}</strong>?<br>Esta acción no se puede deshacer.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#047857', // Verde más oscuro para botón
                cancelButtonColor: '#d33',
                confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Sí, eliminar',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Cancelar',
                focusConfirm: false,
                allowOutsideClick: () => !Swal.isLoading(),
                showClass: {
                    popup: 'animate__animated animate__fadeInDown'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `../../actions/delete.php?id=${id}&tipo=${tipo}`;
                }
            });
        });
    });
    
    // Añadir animaciones a los iconos al pasar el ratón
    document.querySelectorAll('.icon-container').forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            const iconElement = this.querySelector('i');
            if (iconElement) {
                iconElement.classList.add('animate__animated', 'animate__headShake');
                
                // Remover las clases de animación después de que termine
                setTimeout(() => {
                    iconElement.classList.remove('animate__animated', 'animate__headShake');
                }, 1000);
            }
        });
    });
</script>
