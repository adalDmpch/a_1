<?php
require '../config/confg.php';

// Iniciar sesión para verificar permisos
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../public/login.php");
    exit();
}

// Verificar si se ha proporcionado un ID válido
if (isset($_GET["id"]) && is_numeric($_GET["id"])) {
    $id = intval($_GET["id"]);
    
    try {
        // Verificar si el servicio existe antes de eliminarlo
        $stmt = $pdo->prepare("SELECT tipo FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$servicio) {
            header("Location: ../public/admin/servicio.php?mensaje=El servicio que intentas eliminar no existe&tipo=error");
            exit();
        }
        
        // Verificar si el servicio está asociado a otras tablas (citas, por ejemplo)
        // Esto es solo un ejemplo, ajusta según tu estructura de base de datos
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE servicio_id = ?");
        $stmt->execute([$id]);
        $tiene_citas = $stmt->fetchColumn();
        
        if ($tiene_citas > 0) {
            header("Location: ../public/admin/servicio.php?mensaje=No se puede eliminar este servicio porque está asociado a citas existentes&tipo=error");
            exit();
        }
        
        // Eliminar el servicio
        $stmt = $pdo->prepare("DELETE FROM servicios WHERE id = ?");
        $resultado = $stmt->execute([$id]);
        
        if ($resultado) {
            header("Location: ../public/admin/servicio.php?mensaje=Servicio eliminado exitosamente&tipo=success");
        } else {
            header("Location: ../public/admin/servicio.php?mensaje=Error al eliminar el servicio&tipo=error");
        }
    } catch (PDOException $e) {
        // Capturar cualquier error de la base de datos
        header("Location: ../public/admin/servicio.php?mensaje=Error en la base de datos: " . urlencode($e->getMessage()) . "&tipo=error");
    }
} else {
    // Si no se proporcionó un ID válido
    header("Location: ../public/admin/servicio.php?mensaje=ID de servicio no válido&tipo=error");
}
exit();
?>