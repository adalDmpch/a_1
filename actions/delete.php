<?php
require '../config/confg.php';
session_start();

// Verificar autenticación del administrador
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../index.php");
    exit();
}

// Validar parámetros
if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['tipo'])) {
    header("Location: ../public/admin/index.php?mensaje=Parámetros inválidos&tipo=error");
    exit();
}

$id = $_GET['id'];
$tipo = $_GET['tipo'];

try {
    if ($tipo === 'empleado') {
        // Verificar si el empleado existe
        $stmt = $pdo->prepare("SELECT email_empleado FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$empleado) {
            header("Location: ../public/admin/index.php?mensaje=El empleado no existe&tipo=error");
            exit();
        }

        // Verificar si el empleado tiene citas asociadas
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM citas WHERE empleado_id = ?");
        $stmt->execute([$id]);
        $totalCitas = $stmt->fetchColumn();

        if ($totalCitas > 0) {
            header("Location: ../public/admin/index.php?mensaje=No se puede eliminar el empleado porque tiene citas registradas&tipo=error");
            exit();
        }

        $email_empleado = $empleado['email_empleado'];

        // Desvincular usuario
        $stmt = $pdo->prepare("UPDATE usuarios SET empleado_id = NULL WHERE email_usuario = ?");
        $stmt->execute([$email_empleado]);

        // Eliminar empleado
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->execute([$id]);

        // Eliminar usuario
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE email_usuario = ?");
        $stmt->execute([$email_empleado]);

        header("Location: ../public/admin/index.php?mensaje=Empleado eliminado correctamente&tipo=success");
        exit();

    } elseif ($tipo === 'negocio') {
        // Verificar si el negocio tiene empleados
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM empleados WHERE negocio_id = ?");
        $stmt->execute([$id]);
        $totalEmpleados = $stmt->fetchColumn();

        if ($totalEmpleados > 0) {
            header("Location: ../public/admin/index.php?mensaje=No se puede eliminar el negocio porque tiene empleados registrados&tipo=error");
            exit();
        }

        // Eliminar negocio
        $stmt = $pdo->prepare("DELETE FROM negocio WHERE id = ?");
        $stmt->execute([$id]);

        header("Location: ../public/admin/index.php?mensaje=Negocio eliminado correctamente&tipo=success");
        exit();

    } else {
        header("Location: ../public/admin/index.php?mensaje=Tipo de entidad no válido&tipo=error");
        exit();
    }
} catch (PDOException $e) {
    error_log("Error en delete.php: " . $e->getMessage());
    header("Location: ../public/admin/index.php?mensaje=Error en la base de datos&tipo=error");
    exit();
}
?>
