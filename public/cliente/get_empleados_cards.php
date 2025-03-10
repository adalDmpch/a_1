<?php
require '../../config/confg.php';

header('Content-Type: text/html; charset=utf-8');

try {
    // Validación robusta
    if (!isset($_GET['negocio_id'])) {
        throw new Exception('Parámetro requerido');
    }
    
    $negocio_id = filter_var($_GET['negocio_id'], FILTER_VALIDATE_INT);
    
    if ($negocio_id === false || $negocio_id < 1) {
        throw new Exception('ID inválido');
    }

    // Consulta preparada
    $stmt = $pdo->prepare("SELECT * FROM empleados WHERE negocio_id = ?");
    $stmt->execute([$negocio_id]);
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($empleados)) {
        echo '<div class="text-center text-gray-500 py-8 col-span-full">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="mt-4 text-gray-600 font-medium">No hay especialistas registrados</p>
              </div>';
        exit;
    }

    // Generar tarjetas
    foreach ($empleados as $emp) {
        $foto = !empty($emp['foto']) ? htmlspecialchars($emp['foto']) : '/assets/images/default-profile.jpg';
        $nombre = htmlspecialchars($emp['nombreempleado']);
        $cargo = isset($emp['cargo']) ? htmlspecialchars($emp['cargo']) : 'Especialista';
        
        echo '<div class="employee-card" data-employee-id="' . htmlspecialchars($emp['id']) . '">
                <div class="flex flex-col items-center p-4">
                    <img src="' . $foto . '" alt="' . $nombre . '" class="employee-photo w-20 h-20 rounded-full mb-4 object-cover">
                    <h4 class="employee-name text-lg font-semibold text-gray-800 mb-1">' . $nombre . '</h4>
                    <p class="employee-role text-sm text-gray-600 mb-3">' . $cargo . '</p>';
        
        if (!empty($emp['especialidades'])) {
            echo '<div class="flex flex-wrap gap-2 justify-center">';
            $especialidades = explode(',', $emp['especialidades']);
            foreach ($especialidades as $esp) {
                echo '<span class="employee-specialty px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">' 
                     . htmlspecialchars(trim($esp)) . '</span>';
            }
            echo '</div>';
        }
        
        echo '</div></div>';
    }

} catch (Exception $e) {
    echo '<div class="text-center text-red-500 py-8 col-span-full">
            <svg class="w-16 h-16 mx-auto text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <p class="mt-4 text-red-600 font-medium">Error: ' . htmlspecialchars($e->getMessage()) . '</p>
          </div>';
    error_log('Error Empleados: ' . $e->getMessage());
}
?>