<?php
require '../config/confg.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = isset($_POST['estado']) && !empty($_POST['estado']) ? $_POST['estado'] : null;
    $cita_id = isset($_POST['cita_id']) ? intval($_POST['cita_id']) : 0;

    // Verificar si los datos son válidos
    if ($estado && $cita_id > 0) {
        try {
            $sql = "UPDATE citas SET estado = :estado WHERE id = :cita_id";
            $stmt = $pdo->prepare($sql);

            if ($stmt && $stmt->execute([ ':estado' => $estado, ':cita_id' => $cita_id ])) {
                $_SESSION['mensaje'] = 'Estado actualizado correctamente';
                header("Location: ../public/empleado/agenda.php");
                exit();
            } else {
                $_SESSION['error'] = 'Hubo un error al actualizar el estado.';
            }
        } catch (PDOException $e) {
            $_SESSION['error'] = 'Error en la base de datos: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error'] = 'Faltan datos en el formulario.';
    }
    header("Location: ../public/empleado/agenda.php");
    exit();
}

// Mostrar mensajes de error o éxito si existen en la sesión
if (isset($_SESSION['error'])) {
    echo '<div class="max-w-7xl mx-auto mt-24 mb-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                <p>' . htmlspecialchars($_SESSION['error']) . '</p>
            </div>
          </div>';
    unset($_SESSION['error']);
}

if (isset($_SESSION['mensaje'])) {
    echo '<div class="max-w-7xl mx-auto mt-24 mb-4">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                <p>' . htmlspecialchars($_SESSION['mensaje']) . '</p>
            </div>
          </div>';
    unset($_SESSION['mensaje']);
}
?>
