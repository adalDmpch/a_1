<?php
require '../../config/confg.php';

header('Content-Type: text/html; charset=utf-8');

try {
    // Validación estricta del input
    if (!isset($_GET['negocio_id'])) {
        throw new Exception('Parámetro requerido');
    }
    
    $negocio_id = filter_var($_GET['negocio_id'], FILTER_VALIDATE_INT);
    
    if ($negocio_id === false || $negocio_id < 1) {
        throw new Exception('ID inválido');
    }

    // Consulta segura con JOIN
    $sql = "SELECT s.id, s.tipo, s.precio 
            FROM servicios s
            INNER JOIN negocio_servicios ns ON s.id = ns.servicio_id
            WHERE ns.negocio_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$negocio_id]);
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Construir respuesta
    $html = '<select id="servicio_id" name="servicio_id" 
            class="w-full p-3 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" 
            required>';
    
    if (empty($servicios)) {
        $html .= '<option value="">No hay servicios disponibles</option>';
    } else {
        $html .= '<option value="">Selecciona un servicio</option>';
        foreach ($servicios as $serv) {
            $precio = number_format($serv['precio'], 2);
            $html .= sprintf(
                '<option value="%d">%s - $%s</option>',
                htmlspecialchars($serv['id'], ENT_QUOTES),
                htmlspecialchars($serv['tipo']),
                $precio
            );
        }
    }
    $html .= '</select>';
    
    echo $html;

} catch (Exception $e) {
    $error = '<select class="w-full p-3 border border-red-300 bg-red-50 rounded-lg" disabled>';
    $error .= '<option>Selección inválida - ' . htmlspecialchars($e->getMessage()) . '</option>';
    $error .= '</select>';
    echo $error;
    error_log('Error Servicios: ' . $e->getMessage());
}
?>