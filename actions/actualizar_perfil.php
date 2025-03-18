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

    // Verificar si el nuevo email ya existe en otros usuarios
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email_usuario = ? AND id != ?");
    $stmt->execute([$nuevo_email, $user_id]);
    if ($stmt->fetch()) {
        throw new Exception("El correo electrónico ya está en uso por otra cuenta");
    }

    // Manejar imagen de perfil
    $foto_binaria = null;
    $hay_imagen_nueva = false;
    
    if (!empty($_FILES['foto_perfil']['name']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        // Leer el contenido binario de la imagen
        $foto_binaria = file_get_contents($_FILES['foto_perfil']['tmp_name']);
        
        if ($foto_binaria === false) {
            throw new Exception("Error al leer la imagen.");
        }
        $hay_imagen_nueva = true;
    }

    // Paso 1: Establecer el email_cliente a NULL para evitar el conflicto
    $sqlCliente1 = "UPDATE cliente SET email_cliente = NULL WHERE id = ?";
    $stmtCliente1 = $pdo->prepare($sqlCliente1);
    $stmtCliente1->execute([$cliente_id]);

    // Paso 2: Actualizar el email en usuarios
    $sqlUsuario = "UPDATE usuarios SET email_usuario = ? WHERE id = ?";
    $stmtUsuario = $pdo->prepare($sqlUsuario);
    $stmtUsuario->execute([$nuevo_email, $user_id]);

    // Paso 3: Actualizar el resto de la información del cliente, incluyendo el nuevo email
    if ($hay_imagen_nueva) {
        $sqlCliente2 = "UPDATE cliente SET 
            nombre = ?, 
            email_cliente = ?, 
            phone = ?, 
            fecha = ?,
            foto_de_perfil = ?
            WHERE id = ?";
        
        $stmtCliente2 = $pdo->prepare($sqlCliente2);
        $stmtCliente2->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmtCliente2->bindParam(2, $nuevo_email, PDO::PARAM_STR);
        $stmtCliente2->bindParam(3, $telefono, PDO::PARAM_STR);
        $stmtCliente2->bindParam(4, $fecha_nacimiento, PDO::PARAM_STR);
        $stmtCliente2->bindParam(5, $foto_binaria, PDO::PARAM_LOB);
        $stmtCliente2->bindParam(6, $cliente_id, PDO::PARAM_INT);
    } else {
        $sqlCliente2 = "UPDATE cliente SET 
            nombre = ?, 
            email_cliente = ?, 
            phone = ?, 
            fecha = ?
            WHERE id = ?";
        
        $stmtCliente2 = $pdo->prepare($sqlCliente2);
        $stmtCliente2->bindParam(1, $nombre, PDO::PARAM_STR);
        $stmtCliente2->bindParam(2, $nuevo_email, PDO::PARAM_STR);
        $stmtCliente2->bindParam(3, $telefono, PDO::PARAM_STR);
        $stmtCliente2->bindParam(4, $fecha_nacimiento, PDO::PARAM_STR);
        $stmtCliente2->bindParam(5, $cliente_id, PDO::PARAM_INT);
    }
    
    $stmtCliente2->execute();

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