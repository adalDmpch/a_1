<?php
require '../config/confg.php';
session_start();

// Verificar autenticación del administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Verificar que se recibieron los datos necesarios
if (!isset($_POST['id']) || !isset($_POST['tipo']) || empty($_POST['tipo'])) {
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=Faltan datos requeridos&tipo=error");
    exit();
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$tipo = trim(filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_STRING));

// Validación básica
if (!$id) {
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=ID inválido&tipo=error");
    exit();
}

if (strlen($tipo) < 2 || strlen($tipo) > 50) {
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=El nombre del método de pago debe tener entre 2 y 50 caracteres&tipo=error");
    exit();
}

try {
    // Verificar si ya existe otro método de pago con el mismo nombre (excepto el que estamos editando)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM metodo_de_pago WHERE tipo = ? AND id != ?");
    $stmt->execute([$tipo, $id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=Ya existe un método de pago con ese nombre&tipo=error");
        exit();
    }
    
    // Actualizar el método de pago
    $stmt = $pdo->prepare("UPDATE metodo_de_pago SET tipo = ? WHERE id = ?");
    $result = $stmt->execute([$tipo, $id]);
    
    if ($result) {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=Método de pago actualizado correctamente&tipo=success");
    } else {
        header("Location: ../public/admin/create_metodo_pago.php?mensaje=Error al actualizar el método de pago&tipo=error");
    }
} catch (PDOException $e) {
    // Registrar error para depuración (en un entorno de producción, usar un sistema de logs adecuado)
    error_log("Error en update_pay.php: " . $e->getMessage());
    header("Location: ../public/admin/create_metodo_pago.php?mensaje=Error en la base de datos&tipo=error");
}
exit();
?>