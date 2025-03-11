<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require '../config/confg.php';
require '../public/cliente/funciones.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "cliente") {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $pdo->beginTransaction(); // Iniciar transacción

    // Obtener datos del formulario
    $cliente_id = filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT);
    $nombre = limpiarInput($_POST['nombre']);
    $nuevo_email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telefono = limpiarInput($_POST['telefono']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    
    // Validaciones
    if (!$cliente_id || !$nombre || !$nuevo_email || !$fecha_nacimiento) {
        throw new Exception("Campos requeridos faltantes");
    }
    
    if (!filter_var($nuevo_email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Formato de email inválido");
    }

    // Verificar si el nuevo email ya existe en usuarios
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email_usuario = ? AND id != ?");
    $stmt->execute([$nuevo_email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception("El correo electrónico ya está en uso por otra cuenta");
    }

    // Manejar imagen de perfil
    $nombre_imagen = null;
    if (!empty($_FILES['foto_perfil']['name'])) {
        $archivo = manejarSubidaArchivo(
            $_FILES['foto_perfil'], 
            '../../uploads/', 
            ['jpg', 'jpeg', 'png', 'gif'], 
            2 * 1024 * 1024
        );
        $nombre_imagen = $archivo['nombre'];
    }

    // Actualizar tabla CLIENTE
    $sqlCliente = "UPDATE cliente SET 
                  nombre = ?, 
                  email_cliente = ?, 
                  phone = ?, 
                  fecha = ?" 
                  . ($nombre_imagen ? ", foto_de_perfil = ?" : "") 
                  . " WHERE id = ?";
    
    $paramsCliente = [$nombre, $nuevo_email, $telefono, $fecha_nacimiento];
    if ($nombre_imagen) {
        $paramsCliente[] = $nombre_imagen;
    }
    $paramsCliente[] = $cliente_id;
    
    $stmtCliente = $pdo->prepare($sqlCliente);
    $stmtCliente->execute($paramsCliente);

    // Actualizar tabla USUARIOS
    $sqlUsuario = "UPDATE usuarios SET 
                  email_usuario = ? 
                  WHERE cliente_id = ?";
    
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([$nuevo_email, $cliente_id]);

    $pdo->commit(); // Confirmar cambios

    // Actualizar email en sesión si es necesario
    $_SESSION['email'] = $nuevo_email;
    $_SESSION['success'] = "Perfil actualizado exitosamente!";

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Error: " . $e->getMessage();
}

header("Location: ../public/cliente/editar_perfil.php");
exit();
?>