<?php
require '../../config/confg.php';

if (isset($_GET['negocio_id'])) {
    $negocio_id = $_GET['negocio_id'];
    
    $sql = "SELECT s.* 
            FROM servicios s
            JOIN negocio_servicios ns ON s.id = ns.servicio_id
            WHERE ns.negocio_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$negocio_id]);
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo '<select id="servicio_id" name="servicio_id" class="w-full p-3 bg-gray-50 border border-gray-200 rounded-lg" required>';
    foreach ($servicios as $servicio) {
        echo "<option value='{$servicio['id']}'>{$servicio['tipo']} - $ {$servicio['precio']}</option>";
    }
    echo '</select>';
}
?>