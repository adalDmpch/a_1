<?php
require '../config/confg.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : null;

    switch ($tipo) {
        case 'negocio':
            $nombrenegocio = isset($_POST['nombrenegocio']) ? trim($_POST['nombrenegocio']) : null;
            $tipodenegocio = isset($_POST['tipodenegocio']) ? trim($_POST['tipodenegocio']) : null;
            $ubicaciondelnegocio = isset($_POST['ubicaciondelnegocio']) ? trim($_POST['ubicaciondelnegocio']) : null;
            $phonenegocio = isset($_POST['phonenegocio']) ? trim($_POST['phonenegocio']) : null;
            $emailnegocio = isset($_POST['emailnegocio']) ? trim($_POST['emailnegocio']) : null;
            $servicios = isset($_POST['servicios']) ? implode(', ', $_POST['servicios']) : null;
            
            $dias_operacion = isset($_POST['dias_operacion']) ? implode(', ', $_POST['dias_operacion']) : null;
            $horas_operacion = isset($_POST['horas_operacion']) ? trim($_POST['horas_operacion']) : null;
            $horas_fin = isset($_POST['horas_fin']) ? trim($_POST['horas_fin']) : null;
            $metodo_de_pago_id = isset($_POST['metodo_de_pago_id']) ? intval($_POST['metodo_de_pago_id']) : null;

            if (!$nombrenegocio || !$tipodenegocio || !$ubicaciondelnegocio || !$phonenegocio || !$emailnegocio || !$servicios || !$dias_operacion || !$horas_operacion || !$horas_fin || !$metodo_de_pago_id ) {
                die("Error: Todos los campos son obligatorios.");
            }

            if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                die("Error: La imagen de perfil es obligatoria.");
            }

            // Guardar la imagen en la carpeta uploads/
            $uploads_dir = '../public/uploads/';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true); // Crea la carpeta si no existe
            }
            $foto_nombree = basename($_FILES['logo']['name']);
            $foto_destinoo = $uploads_dir . $foto_nombree;

            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $foto_destinoo)) {
                die("Error: No se pudo guardar la imagen.");
            }

            try {
                $stmt = $pdo->prepare("INSERT INTO negocio (nombrenegocio, tipodenegocio, ubicaciondelnegocio, phonenegocio, emailnegocio, servicios, logo, dias_operacion, horas_operacion, horas_fin, metodo_de_pago_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombrenegocio, $tipodenegocio, $ubicaciondelnegocio, $phonenegocio, $emailnegocio, $servicios, $foto_destinoo, $dias_operacion, $horas_operacion, $horas_fin, $metodo_de_pago_id]);
            } catch (PDOException $e) {
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;

        case 'empleado':
            $nombreempleado = isset($_POST['nombreempleado']) ? trim($_POST['nombreempleado']) : null;
            $phoneempleado = isset($_POST['phoneempleado']) ? trim($_POST['phoneempleado']) : null;
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
            $edad = isset($_POST['edad']) ? intval($_POST['edad']) : null;
            $negocio_id = isset($_POST['negocio_id']) ? intval($_POST['negocio_id']) : null;
            $disponibilidad = isset($_POST['disponibilidad']) ? trim($_POST['disponibilidad']) : null;
            $metodo_de_cobro = isset($_POST['metodo_de_cobro']) ? trim($_POST['metodo_de_cobro']) : null;
            $email_empleado = isset($_POST['email_empleado']) ? trim($_POST['email_empleado']) : null;
            $contra_empleados = isset($_POST['contra_empleados']) ? trim($_POST['contra_empleados']) : null;

            if (!$nombreempleado || !$phoneempleado || !$descripcion || !$edad || !$disponibilidad || !$metodo_de_cobro || !$negocio_id || !$email_empleado || !$contra_empleados) {
                die("Error: Todos los campos son obligatorios.");
            }

            if (!isset($_FILES['foto_de_perfil']) || $_FILES['foto_de_perfil']['error'] !== UPLOAD_ERR_OK) {
                die("Error: La imagen de perfil es obligatoria.");
            }

            // Guardar la imagen en la carpeta uploads/
            $uploads_dir = '../public/uploads/';
            if (!is_dir($uploads_dir)) {
                mkdir($uploads_dir, 0777, true); // Crea la carpeta si no existe
            }
            $foto_nombre = basename($_FILES['foto_de_perfil']['name']);
            $foto_destino = $uploads_dir . $foto_nombre;

            if (!move_uploaded_file($_FILES['foto_de_perfil']['tmp_name'], $foto_destino)) {
                die("Error: No se pudo guardar la imagen.");
            }
            try {
                $pdo->beginTransaction(); // Iniciar transacción
            
                // 🔹 Insertar el usuario con contraseña encriptada en `usuarios`
                $hash_contra = password_hash($contra_empleados, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (email_usuario, password, rol, fecha_creacion, activo) VALUES (?, ?, 'empleado', NOW(), TRUE)");
                $stmt->execute([$email_empleado, $hash_contra]);
            
                // Obtener el ID del usuario insertado
                $usuario_id = $pdo->lastInsertId();
            
                // 🔹 Insertar los datos del empleado en `empleados`
                $stmt = $pdo->prepare("INSERT INTO empleados (nombreempleado, phoneempleado, email_empleado, edad, negocio_id, disponibilidad, metodo_de_cobro, descripcion, foto_de_perfil)
                                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombreempleado, $phoneempleado, $email_empleado, $edad, $negocio_id, $disponibilidad, $metodo_de_cobro, $descripcion, $foto_de_perfil]);
            
                // Obtener el ID del empleado insertado
                $empleado_id = $pdo->lastInsertId();
            
                // 🔹 Asociar el usuario con el empleado en la tabla `usuarios`
                $stmt = $pdo->prepare("UPDATE usuarios SET empleado_id = ? WHERE id = ?");
                $stmt->execute([$empleado_id, $usuario_id]);
            
                $pdo->commit(); // Confirmar la transacción
            
                header("Location: ../public/admin/index.php");
                exit;
            } catch (PDOException $e) {
                $pdo->rollBack(); // Revertir cambios si hay error
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;
            
            
            

        default:
            die("Error: Tipo de entidad no válido.");
    }

    header("Location: ../public/admin/index.php");
    exit;
}
?>
