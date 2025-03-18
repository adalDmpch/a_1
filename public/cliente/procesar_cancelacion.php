<?php
require '../../config/confg.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $cita_id = $_POST['cita_id'];
    
    // Actualizar solo el estado a 'cancelada'
    $sql = "UPDATE citas c
            SET estado = 'cancelada'
            FROM usuarios u
            WHERE c.id = :cita_id 
            AND u.id = :user_id 
            AND c.cliente_id = u.cliente_id";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':cita_id' => $cita_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    
    header("Location: perfil.php?success=cancelado");
    exit();
}