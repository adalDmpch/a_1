<?php
require '../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../public/login.php");
    exit();
}

if (isset($_POST['cita_id'])) {
    $cita_id = intval($_POST['cita_id']);
    
    // Si se está actualizando el estado
    if (isset($_POST['estado'])) {
        $nuevo_estado = $_POST['estado'];
        
        $estados_permitidos = ['confirmada', 'rechazada', 'completada', 'no_asistio'];
        if (in_array($nuevo_estado, $estados_permitidos)) {
            $sql = "UPDATE citas SET estado = ? WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$nuevo_estado, $cita_id])) {
                $_SESSION['exito'] = "El estado de la cita se ha actualizado correctamente.";
            } else {
                $_SESSION['error'] = "Ocurrió un error al actualizar el estado de la cita.";
            }
        } else {
            $_SESSION['error'] = "El estado solicitado no es válido.";
        }
    }
    
    // Manejar acciones de inicio y fin de servicio
    if (isset($_POST['accion'])) {
        $accion = $_POST['accion'];
        
        if ($accion === 'iniciar_servicio') {
            $sql = "UPDATE citas SET hora_inicio_real = CURRENT_TIME WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$cita_id])) {
                $_SESSION['exito'] = "El servicio se ha iniciado correctamente.";
            } else {
                $_SESSION['error'] = "Ocurrió un error al registrar el inicio del servicio.";
            }
        } 
        elseif ($accion === 'finalizar_servicio') {
            // Actualizar hora de finalización y cambiar estado a 'completada'
            $sql = "UPDATE citas SET hora_fin_real = CURRENT_TIME, estado = 'completada' WHERE id = ?";
            $stmt = $pdo->prepare($sql);
            
            if ($stmt->execute([$cita_id])) {
                $_SESSION['exito'] = "El servicio se ha finalizado correctamente y marcado como completado.";
            } else {
                $_SESSION['error'] = "Ocurrió un error al registrar la finalización del servicio.";
            }
        }
    }
    
    header("Location: ../public/empleado/inicio.php");
    exit();
} else {
    $_SESSION['error'] = "No se especificó ninguna cita.";
    header("Location: ../public/empleado/inicio.php");
    exit();
}