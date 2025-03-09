<?php
require '../config/confg.php';

// Iniciar sesión para verificar permisos
session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../public/login.php");
    exit();
}

// Verificar si el formulario se ha enviado mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y validar los datos del formulario
    $id = intval($_POST["id"]);
    $tipo = trim($_POST["tipo"]);
    $duracion = intval($_POST["duracion"]);
    $precio = floatval($_POST["precio"]);
    
    // Validaciones básicas
    if (empty($tipo)) {
        header("Location: ../public/admin/servicio.php?mensaje=El nombre del servicio no puede estar vacío&tipo=error");
        exit();
    }
    
    if ($duracion <= 0) {
        header("Location: ../public/admin/servicio.php?mensaje=La duración debe ser un valor positivo&tipo=error");
        exit();
    }
    
    if ($precio < 0) {
        header("Location: ../public/admin/servicio.php?mensaje=El precio no puede ser negativo&tipo=error");
        exit();
    }
    
    try {
        // Verificar si existe el ID
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE id = ?");
        $stmt->execute([$id]);
        $existe = $stmt->fetchColumn();
        
        if ($existe == 0) {
            header("Location: ../public/admin/servicio.php?mensaje=El servicio que intentas editar no existe&tipo=error");
            exit();
        }
        
        // Verificar si ya existe otro servicio con el mismo nombre (excluyendo el actual)
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE tipo = ? AND id != ?");
        $stmt->execute([$tipo, $id]);
        $existe_otro = $stmt->fetchColumn();
        
        if ($existe_otro > 0) {
            header("Location: ../public/admin/servicio.php?mensaje=Ya existe otro servicio con ese nombre&tipo=error");
            exit();
        }
        
        // Actualizar el servicio en la base de datos
        $stmt = $pdo->prepare("UPDATE servicios SET tipo = ?, duracion = ?, precio = ? WHERE id = ?");
        $resultado = $stmt->execute([$tipo, $duracion, $precio, $id]);
        
        if ($resultado) {
            header("Location: ../public/admin/servicio.php?mensaje=Servicio actualizado exitosamente&tipo=success");
        } else {
            header("Location: ../public/admin/servicio.php?mensaje=Error al actualizar el servicio&tipo=error");
        }
    } catch (PDOException $e) {
        // Capturar cualquier error de la base de datos
        header("Location: ../public/admin/servicio.php?mensaje=Error en la base de datos: " . urlencode($e->getMessage()) . "&tipo=error");
    }
} else {
    // Si se accede directamente al script sin usar POST
    header("Location: ../public/admin/servicio.php");
}
exit();
?>