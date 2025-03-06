<?php
require '../config/confg.php'; // Asegúrate de incluir tu conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevo_metodo_pago = trim($_POST['nuevo_metodo_pago']);

    if (!empty($nuevo_metodo_pago)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO metodo_de_pago (tipo) VALUES (?)");
            $stmt->execute([$nuevo_metodo_pago]);
            header("Location: ../public/admin/create_metodo_pago.php"); // Redirige de nuevo al formulario
            exit();
        } catch (PDOException $e) {
            die("Error en la base de datos: " . $e->getMessage());
        }
    } else {
        die("Error: El nombre del método de pago es obligatorio.");
    }
}
?>
