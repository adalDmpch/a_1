<?php
require '../config/confg.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado = isset($_POST['estado']) ? $_POST['estado'] : '';
    $cita_id = isset($_POST['cita_id']) ? intval($_POST['cita_id']) : 0;

    // Verificar si ambos datos están presentes
    if ($estado && $cita_id) {
        $sql = "UPDATE citas SET estado = :estado WHERE id = :cita_id";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([ ':estado' => $estado, ':cita_id' => $cita_id ])) {
            $mensaje = 'Estado actualizado correctamente';
            // Redirigir a agenda.php
            header("Location: ../public/empleado/agenda.php");
            exit(); // Asegúrate de llamar a exit después de la redirección
        } else {
            $mensaje = 'Hubo un error al actualizar el estado.';
        }
    } else {
        $mensaje = 'Faltan datos en el formulario.';
    }
}

// Mostrar mensajes de error o éxito
if (isset($mensaje['error'])) {
    echo '<div class="max-w-7xl mx-auto mt-24 mb-4">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                <p>' . htmlspecialchars($mensaje['error']) . '</p>
            </div>
          </div>';
    unset($mensaje['error']);
}
?>
