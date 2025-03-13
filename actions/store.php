<?php
require '../config/confg.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : null;

    switch ($tipo) {
        case 'negocio':
            $nombrenegocio = trim($_POST['nombrenegocio'] ?? '');
            $tipodenegocio = trim($_POST['tipodenegocio'] ?? '');
            $ubicaciondelnegocio = trim($_POST['ubicaciondelnegocio'] ?? '');
            $phonenegocio = trim($_POST['phonenegocio'] ?? '');
            $emailnegocio = trim($_POST['emailnegocio'] ?? '');
            $dias_operacion = isset($_POST['dias_operacion']) ? implode(', ', $_POST['dias_operacion']) : null;
            $horas_operacion = trim($_POST['horas_operacion'] ?? '');
            $horas_fin = trim($_POST['horas_fin'] ?? '');
            $metodo_de_pago_id = intval($_POST['metodo_de_pago_id'] ?? 0);
            $servicios = $_POST['servicios'] ?? [];

            if (!$nombrenegocio || !$tipodenegocio || !$ubicaciondelnegocio || !$phonenegocio || !$emailnegocio || !$dias_operacion || !$horas_operacion || !$horas_fin || !$metodo_de_pago_id) {
                die("Error: Todos los campos son obligatorios.");
            }

            if (!isset($_FILES['logo']) || $_FILES['logo']['error'] !== UPLOAD_ERR_OK) {
                die("Error: El logo del negocio es obligatorio.");
            }

            // Leer el archivo en binario
            $logo_binario = file_get_contents($_FILES['logo']['tmp_name']);
            
            // Preparar para PostgreSQL si es necesario
            if (function_exists('pg_escape_bytea')) {
                $logo_escapado = pg_escape_bytea($logo_binario);
            } else {
                $logo_escapado = $logo_binario; // PDO se encargará
            }
            try {
                $stmt = $pdo->prepare("INSERT INTO negocio (nombrenegocio, tipodenegocio, ubicaciondelnegocio, phonenegocio, emailnegocio, dias_operacion, horas_operacion, horas_fin, metodo_de_pago_id, logo) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bindParam(1, $nombrenegocio, PDO::PARAM_STR);
                $stmt->bindParam(2, $tipodenegocio, PDO::PARAM_STR);
                $stmt->bindParam(3, $ubicaciondelnegocio, PDO::PARAM_STR);
                $stmt->bindParam(4, $phonenegocio, PDO::PARAM_STR);
                $stmt->bindParam(5, $emailnegocio, PDO::PARAM_STR);
                $stmt->bindParam(6, $dias_operacion, PDO::PARAM_STR);
                $stmt->bindParam(7, $horas_operacion, PDO::PARAM_STR);
                $stmt->bindParam(8, $horas_fin, PDO::PARAM_STR);
                $stmt->bindParam(9, $metodo_de_pago_id, PDO::PARAM_INT);
                $stmt->bindParam(10, $logo, PDO::PARAM_LOB); // 📌 Asegurar correcta inserción de imagen

                $stmt->execute();
                $negocio_id = $pdo->lastInsertId();

                if (!empty($servicios)) {
                    $stmtServicio = $pdo->prepare("INSERT INTO negocio_servicios (negocio_id, servicio_id) VALUES (?, ?)");
                    foreach ($servicios as $servicio_id) {
                        $stmtServicio->execute([$negocio_id, $servicio_id]);
                    }
                }

                echo "Negocio registrado con éxito.";
            } catch (PDOException $e) {
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;

        case 'empleado':
            $nombreempleado = trim($_POST['nombreempleado'] ?? '');
            $phoneempleado = trim($_POST['phoneempleado'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $edad = intval($_POST['edad'] ?? 0);
            $negocio_id = intval($_POST['negocio_id'] ?? 0);
            $disponibilidad = trim($_POST['disponibilidad'] ?? '');
            $metodo_de_cobro = trim($_POST['metodo_de_cobro'] ?? '');
            $email_empleado = trim($_POST['email_empleado'] ?? '');
            $contra_empleados = trim($_POST['contra_empleados'] ?? '');

            if (!$nombreempleado || !$phoneempleado || !$descripcion || !$edad || !$disponibilidad || !$metodo_de_cobro || !$negocio_id || !$email_empleado || !$contra_empleados) {
                die("Error: Todos los campos son obligatorios.");
            }

// Código similar para empleados...
            if (!isset($_FILES['foto_de_perfil']) || $_FILES['foto_de_perfil']['error'] !== UPLOAD_ERR_OK) {
                die("Error: La foto de perfil es obligatoria.");
            }

            // Leer el archivo en binario
            $foto_binaria = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
            
            // Preparar para PostgreSQL si es necesario
            if (function_exists('pg_escape_bytea')) {
                $foto_escapada = pg_escape_bytea($foto_binaria);
            } else {
                $foto_escapada = $foto_binaria; // PDO se encargará
            }
            try {
                $pdo->beginTransaction();

                // Insertar usuario
                $hash_contra = password_hash($contra_empleados, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO usuarios (email_usuarios, password, rol, fecha_creacion, activo) VALUES (?, ?, 'empleado', NOW(), TRUE)");
                $stmt->execute([$email_empleado, $hash_contra]);

                $usuario_id = $pdo->lastInsertId();

                // Insertar empleado con imagen
                $stmt = $pdo->prepare("INSERT INTO empleados (nombreempleado, phoneempleado, email_empleado, edad, negocio_id, disponibilidad, metodo_de_cobro, descripcion, foto_de_perfil) 
                                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

                $stmt->bindParam(1, $nombreempleado, PDO::PARAM_STR);
                $stmt->bindParam(2, $phoneempleado, PDO::PARAM_STR);
                $stmt->bindParam(3, $email_empleado, PDO::PARAM_STR);
                $stmt->bindParam(4, $edad, PDO::PARAM_INT);
                $stmt->bindParam(5, $negocio_id, PDO::PARAM_INT);
                $stmt->bindParam(6, $disponibilidad, PDO::PARAM_STR);
                $stmt->bindParam(7, $metodo_de_cobro, PDO::PARAM_STR);
                $stmt->bindParam(8, $descripcion, PDO::PARAM_STR);
                $stmt->bindParam(9, $foto_de_perfil, PDO::PARAM_LOB); // 📌 Asegurar correcta inserción de imagen

                $stmt->execute();
                $empleado_id = $pdo->lastInsertId();

                // Asociar usuario con empleado
                $stmt = $pdo->prepare("UPDATE usuarios SET empleado_id = ? WHERE id = ?");
                $stmt->execute([$empleado_id, $usuario_id]);

                $pdo->commit();

                echo "Empleado registrado con éxito.";
            } catch (PDOException $e) {
                $pdo->rollBack();
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
