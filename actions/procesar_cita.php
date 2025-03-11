<?php
require '../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "empleado") {
    header("Location: ../login.php");
    exit();
}

// Obtener el correo del usuario autenticado
$sqlCorreo = "SELECT email_usuario FROM usuarios WHERE id = ?";
$stmtCorreo = $pdo->prepare($sqlCorreo);
$stmtCorreo->execute([$_SESSION["user_id"]]);
$usuario = $stmtCorreo->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    $_SESSION['error'] = "No se encontró el usuario.";
    header("Location: ../login.php");
    exit();
}

$correo = $usuario['email_usuario']; // Correo del usuario autenticado

// Obtener el ID del empleado usando su correo
$sqlEmpleado = "SELECT id FROM empleados WHERE email_empleado = ?";
$stmtEmpleado = $pdo->prepare($sqlEmpleado);
$stmtEmpleado->execute([$correo]);
$empleado = $stmtEmpleado->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    $_SESSION['error'] = "No se encontró el empleado asociado a este usuario.";
    header("Location: ../login.php");
    exit();
}

$id_empleado = $empleado['id']; // ID real del empleado

// Verificar que se recibió un formulario por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener datos del formulario
    $id_cita = isset($_POST['id_cita']) ? intval($_POST['id_cita']) : 0;
    $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
    
    // Validar que tenemos un ID de cita válido
    if ($id_cita <= 0) {
        $_SESSION['error'] = "ID de cita no válido";
        header('Location: ../public/empleado/inicio.php');
        exit;
    }
    
    try {
        // Primero verificar que la cita pertenece al empleado y está pendiente
        $sql_verificar = "SELECT id, estado FROM citas 
                          WHERE id = ? AND empleado_id = ? AND estado = 'pendiente'";
        
        $stmt_verificar = $pdo->prepare($sql_verificar);
        $stmt_verificar->execute([$id_cita, $id_empleado]);
        
        if ($stmt_verificar->rowCount() === 0) {
            // La cita no existe, no pertenece al empleado o no está pendiente
            $_SESSION['error'] = "No se puede procesar esta cita. Es posible que no te pertenezca o ya haya sido procesada.";
            header('Location: ../public/empleado/inicio.php');
            exit;
        }
        
        // Definir el nuevo estado según la acción
        $nuevo_estado = '';
        $mensaje_exito = '';
        
        if ($accion === 'aceptar') {
            $nuevo_estado = 'confirmada';
            $mensaje_exito = 'La cita ha sido confirmada exitosamente.';
        } else if ($accion === 'rechazar') {
            $nuevo_estado = 'rechazada';
            $mensaje_exito = 'La cita ha sido rechazada.';
        } else {
            $_SESSION['error'] = "Acción no válida.";
            header('Location: ../public/empleado/inicio.php');
            exit;
        }
        
        // Actualizar el estado de la cita
        $sql_actualizar = "UPDATE citas SET estado = ? WHERE id = ?";
        $stmt_actualizar = $pdo->prepare($sql_actualizar);
        $stmt_actualizar->execute([$nuevo_estado, $id_cita]);
        
        $_SESSION['exito'] = $mensaje_exito;
        
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error al procesar la cita: " . $e->getMessage();
    }
    
} else {
    // Si no es una solicitud POST, redirigir
    $_SESSION['error'] = "Método de solicitud no válido.";
}

// Redirigir de vuelta a la página de inicio
header('Location: ../public/empleado/inicio.php');
exit;

?>