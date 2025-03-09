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
    $nuevo_servicio = trim($_POST["nuevo_servicio"]);
    $duracion = intval($_POST["duracion"]);
    $precio = floatval($_POST["precio"]);
    
    // Validaciones básicas
    if (empty($nuevo_servicio)) {
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
        // Verificar si ya existe un servicio con el mismo nombre
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM servicios WHERE tipo = ?");
        $stmt->execute([$nuevo_servicio]);
        $existe = $stmt->fetchColumn();
        
        if ($existe > 0) {
            header("Location: ../public/admin/servicio.php?mensaje=Ya existe un servicio con ese nombre&tipo=error");
            exit();
        }
        
        // Insertar el nuevo servicio en la base de datos
        $stmt = $pdo->prepare("INSERT INTO servicios (tipo, duracion, precio) VALUES (?, ?, ?)");
        $resultado = $stmt->execute([$nuevo_servicio, $duracion, $precio]);
        
        if ($resultado) {
            header("Location: ../public/admin/servicio.php?mensaje=Servicio guardado exitosamente&tipo=success");
        } else {
            header("Location: ../public/admin/servicio.php?mensaje=Error al guardar el servicio&tipo=error");
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