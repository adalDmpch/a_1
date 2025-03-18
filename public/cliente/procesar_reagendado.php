<?php
require '../../config/confg.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $cita_id = $_POST['cita_id'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    
    $sql = "UPDATE citas c
            SET fecha = :fecha, hora = :hora
            FROM usuarios u
            WHERE c.id = :cita_id 
            AND u.id = :user_id 
            AND c.cliente_id = u.cliente_id";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':fecha' => $fecha,
        ':hora' => $hora,
        ':cita_id' => $cita_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    
    header("Location: perfil.php?success=reagendado");
    exit();
}