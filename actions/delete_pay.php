<?php
require '../config/confg.php';
session_start();

// Verificar autenticación del administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../.php");
    exit();
}

// Verificar que se recibió el ID
if (!isset($_GET['id'])) {
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=No se especificó el ID del método de pago&tipo=error");
    exit();
}

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

// Validación básica
if (!$id) {
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=ID inválido&tipo=error");
    exit();
}

try {
    // Primero verificar si el método de pago está siendo utilizado por algún negocio
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM negocio WHERE metodo_de_pago_id = ?");
    $stmt->execute([$id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=No se puede eliminar este método de pago porque está siendo utilizado por " . $count . " negocio(s)&tipo=error");
        exit();
    }
    
    // Si no está siendo utilizado, proceder con la eliminación
    $stmt = $pdo->prepare("DELETE FROM metodo_de_pago WHERE id = ?");
    $result = $stmt->execute([$id]);
    
    if ($result) {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=Método de pago eliminado correctamente&tipo=success");
    } else {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=Error al eliminar el método de pago&tipo=error");
    }
} catch (PDOException $e) {
    // Registrar error para depuración
    error_log("Error en delete_pay.php: " . $e->getMessage());
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=Error en la base de datos&tipo=error");
}
exit();
?>