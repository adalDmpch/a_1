<?php
require '../config/confg.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id']) || !isset($_GET['tipo'])) {
    die("Error: Parámetros inválidos.");
}

$id = $_GET['id'];
$tipo = $_GET['tipo'];

try {
    if ($tipo === 'empleado') {
        // Obtener el email del empleado antes de eliminarlo
        $stmt = $pdo->prepare("SELECT email_empleado FROM empleados WHERE id = ?");
        $stmt->execute([$id]);
        $empleado = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$empleado) {
            die("Error: El empleado no existe.");
        }

        $email_empleado = $empleado['email_empleado'];

        // Primero, desvincular el usuario del empleado en la tabla 'usuarios'
        $stmt = $pdo->prepare("UPDATE usuarios SET empleado_id = NULL WHERE email_usuario = ?");
        $stmt->execute([$email_empleado]);

        // Luego, eliminar el empleado de la tabla 'empleados'
        $stmt = $pdo->prepare("DELETE FROM empleados WHERE id = ?");
        $stmt->execute([$id]);

        // Finalmente, eliminar el usuario de la tabla 'usuarios'
        $stmt = $pdo->prepare("DELETE FROM usuarios WHERE email_usuario = ?");
        $stmt->execute([$email_empleado]);

    } elseif ($tipo === 'negocio') {
        // Eliminar el negocio
        $stmt = $pdo->prepare("DELETE FROM negocio WHERE id = ?");
        $stmt->execute([$id]);
    } else {
        die("Error: Tipo de entidad no válido.");
    }

    header("Location: ../public/admin/index.php");
    exit;
} catch (PDOException $e) {
    die("Error en la base de datos: " . $e->getMessage());
}
?>
