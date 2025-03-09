<?php
 require '../config/confg.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tipo = isset($_POST['tipo']) ? trim($_POST['tipo']) : null;

    switch ($tipo) {
        case 'negocio':
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            $nombrenegocio = isset($_POST['nombrenegocio']) ? trim($_POST['nombrenegocio']) : null;
            $tipodenegocio = isset($_POST['tipodenegocio']) ? trim($_POST['tipodenegocio']) : null;
            $ubicaciondelnegocio = isset($_POST['ubicaciondelnegocio']) ? trim($_POST['ubicaciondelnegocio']) : null;
            $phonenegocio = isset($_POST['phonenegocio']) ? trim($_POST['phonenegocio']) : null;
            $emailnegocio = isset($_POST['emailnegocio']) ? trim($_POST['emailnegocio']) : null;
            
            $logo = isset($_POST['logo']) ? trim($_POST['logo']) : null;
            $dias_operacion = isset($_POST['dias_operacion']) ? implode(', ', $_POST['dias_operacion']) : null;
            $horas_operacion = isset($_POST['horas_operacion']) ? trim($_POST['horas_operacion']) : null;
            $horas_fin = isset($_POST['horas_fin']) ? trim($_POST['horas_fin']) : null;
            $metodo_de_pago_id = isset($_POST['metodo_de_pago_id']) ? intval($_POST['metodo_de_pago_id']) : null;

            // Obtención de los servicios seleccionados
            $servicios = isset($_POST['servicios']) ? $_POST['servicios'] : [];

            if (!$id || !$nombrenegocio || !$tipodenegocio || !$ubicaciondelnegocio || !$phonenegocio || !$emailnegocio || !$dias_operacion || !$horas_operacion || !$horas_fin || !$metodo_de_pago_id || empty($servicios)) {
                die("Error: Todos los campos son obligatorios, incluidos los servicios.");
            }

            try {
                $pdo->beginTransaction();
        
                // Actualizar los datos del negocio
                $stmt = $pdo->prepare("UPDATE negocio SET nombrenegocio = ?, tipodenegocio = ?, ubicaciondelnegocio = ?, phonenegocio = ?, emailnegocio = ?, dias_operacion = ?, horas_operacion = ?, horas_fin = ?, metodo_de_pago_id = ? WHERE id = ?");
                $stmt->execute([$nombrenegocio, $tipodenegocio, $ubicaciondelnegocio, $phonenegocio, $emailnegocio, $dias_operacion, $horas_operacion, $horas_fin, $metodo_de_pago_id, $id]);
        
                // Eliminar los servicios actuales del negocio
                $stmt = $pdo->prepare("DELETE FROM negocio_servicios WHERE negocio_id = ?");
                $stmt->execute([$id]);
        
                // Insertar los nuevos servicios seleccionados
                $stmt = $pdo->prepare("INSERT INTO negocio_servicios (negocio_id, servicio_id) VALUES (?, ?)");
                foreach ($servicios as $servicio_id) {
                    $stmt->execute([$id, $servicio_id]);
                }
        
                $pdo->commit();
                header("Location: ../public/admin/index.php");
                exit;
        
            } catch (PDOException $e) {
                $pdo->rollBack(); // Revertir en caso de error
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;

            
        case 'empleado':
            $id = isset($_POST['id']) ? intval($_POST['id']) : null;
            $nombreempleado = isset($_POST['nombreempleado']) ? trim($_POST['nombreempleado']) : null;
            $phoneempleado = isset($_POST['phoneempleado']) ? trim($_POST['phoneempleado']) : null;
            $email_empleado_nuevo = isset($_POST['email_empleado']) ? trim($_POST['email_empleado']) : null;
            $contra_empleados = isset($_POST['contra_empleados']) && !empty($_POST['contra_empleados']) ? password_hash($_POST['contra_empleados'], PASSWORD_BCRYPT) : null;
            $edad = isset($_POST['edad']) ? intval($_POST['edad']) : null;
            $negocio_id = isset($_POST['negocio_id']) ? intval($_POST['negocio_id']) : null;
            $disponibilidad = isset($_POST['disponibilidad']) ? trim($_POST['disponibilidad']) : null;
            $metodo_de_cobro = isset($_POST['metodo_de_cobro']) ? trim($_POST['metodo_de_cobro']) : null;
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;

            if (!$id || !$nombreempleado || !$phoneempleado || !$email_empleado_nuevo || !$edad || !$negocio_id || !$disponibilidad || !$metodo_de_cobro || !$descripcion) {
                die("Error: Todos los campos son obligatorios.");
            }

            try {
                $pdo->beginTransaction(); // Iniciar transacción
            
                // Obtener el email actual del empleado
                $stmt = $pdo->prepare("SELECT email_empleado FROM empleados WHERE id = ?");
                $stmt->execute([$id]);
                $empleado_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if (!$empleado_actual) {
                    throw new Exception("Error: No se encontró el empleado.");
                }
            
                $email_empleado_actual = $empleado_actual['email_empleado'];
            
                // Si el email cambió, actualizarlo en la tabla usuarios y empleados
                if (!empty($email_empleado_nuevo) && $email_empleado_nuevo !== $email_empleado_actual) {
                    // 1️⃣ Primero, eliminar temporalmente la relación en `empleados`
                    $stmt = $pdo->prepare("UPDATE empleados SET email_empleado = NULL WHERE id = ?");
                    $stmt->execute([$id]);

                    // 2️⃣ Verificar si el email actual tiene un usuario vinculado
                    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email_usuario = ?");
                    $stmt->execute([$email_empleado_actual]);
                    $usuario_actual = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($usuario_actual) {
                        // 3️⃣ Actualizar el email en la tabla usuarios
                        $stmt = $pdo->prepare("UPDATE usuarios SET email_usuario = ? WHERE id = ?");
                        $stmt->execute([$email_empleado_nuevo, $usuario_actual['id']]);
                    } else {
                        throw new Exception("Error: No se encontró un usuario vinculado al empleado.");
                    }

                    // 4️⃣ Volver a asignar el email en la tabla empleados
                    $stmt = $pdo->prepare("UPDATE empleados SET email_empleado = ? WHERE id = ?");
                    $stmt->execute([$email_empleado_nuevo, $id]);
                }


            
                // Si se proporciona una nueva contraseña, actualizarla en `usuarios`
                if (!empty($contra_empleados)) {
                    $email_target = !empty($email_empleado_nuevo) ? $email_empleado_nuevo : $email_empleado_actual;
                    
                    $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE email_usuario = ?");
                    $stmt->execute([$contra_empleados, $email_target]); // Usa el hash directamente
                }
                
            
                // Actualizar datos del empleado
                $stmt = $pdo->prepare("UPDATE empleados SET nombreempleado=?, phoneempleado=?, edad=?, negocio_id=?, disponibilidad=?, metodo_de_cobro=?, descripcion=? WHERE id=?");
                $stmt->execute([$nombreempleado, $phoneempleado, $edad, $negocio_id, $disponibilidad, $metodo_de_cobro, $descripcion, $id]);
            
                $pdo->commit(); // Confirmar la transacción
            
                header("Location: ../public/admin/index.php");
                exit;
            } catch (Exception $e) {
                $pdo->rollBack(); // Revertir cambios si hay error
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;
            

        default:
            die("Error: Tipo de entidad no válido.");
    }
}
?>