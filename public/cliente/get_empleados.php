<?php
require '../../config/confg.php';

if (isset($_GET['negocio_id'])) {
    $negocio_id = $_GET['negocio_id'];
    
    $sql = "SELECT * FROM empleados WHERE negocio_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$negocio_id]);
    $empleados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<select id="empleado_id" name="empleado_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required>';
    foreach ($empleados as $empleado) {
        echo "<option value='{$empleado['id']}'>{$empleado['nombreempleado']}</option>";
    }
    echo '</select>';
}
?>