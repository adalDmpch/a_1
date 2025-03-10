<?php
require '../../config/confg.php';

if (isset($_GET['negocio_id'])) {
    $negocio_id = $_GET['negocio_id'];
    $empleado_id = $_GET['empleado_id'] ?? null;
    
    $sql = "SELECT * FROM empleados WHERE negocio_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$negocio_id]);
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $html = '<select id="empleado_id" name="empleado_id" class="w-full p-3 border rounded-lg" required>';
    foreach ($empleados as $empleado) {
        $selected = ($empleado['id'] == $empleado_id) ? 'selected' : '';
        $html .= "<option value='{$empleado['id']}' $selected>{$empleado['nombreempleado']}</option>";
    }
    $html .= '</select>';
    
    echo $html;
}
?>