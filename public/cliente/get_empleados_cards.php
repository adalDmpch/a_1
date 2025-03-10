<?php
require "../../config/confg.php";

if (isset($_GET["negocio_id"])) {
    $negocio_id = $_GET["negocio_id"];
    
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE negocio_id = ?");
    $stmt->execute([$negocio_id]);
    
    if ($stmt->rowCount() > 0) {
        $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($empleados as $empleado) {
            // Determinar la ruta de la imagen de perfil (o usar una por defecto)
            $profileImage = !empty($empleado['foto']) ? $empleado['foto'] : '/assets/images/FondoPeluqueria.jpg';
            
            echo '<div class="employee-card" data-employee-id="' . $empleado['id'] . '">
                    <div class="flex flex-col items-center text-center">
                        <img src="' . $profileImage . '" alt="Foto de ' . $empleado['nombreempleado'] . '" class="employee-photo">
                        <h4 class="employee-name">' . $empleado['nombreempleado'] . '</h4>';
            
            // Verificar si existe la columna 'cargo'
            if (isset($empleado['cargo'])) {
                echo '<p class="employee-role">' . $empleado['cargo'] . '</p>';
            }
            
            echo '</div>
                </div>';
        }
    } else {
        echo '<div class="text-center text-gray-500 py-8 col-span-full">
                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="mt-2">No hay especialistas disponibles para este negocio</p>
              </div>';
    }
} else {
    echo '<div class="text-center text-gray-500 py-8 col-span-full">
            <p>Por favor selecciona un negocio para ver los especialistas disponibles</p>
          </div>';
}
?>