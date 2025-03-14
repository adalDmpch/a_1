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
            $dias_operacion = isset($_POST['dias_operacion']) ? implode(', ', $_POST['dias_operacion']) : null;
            $horas_operacion = isset($_POST['horas_operacion']) ? trim($_POST['horas_operacion']) : null;
            $horas_fin = isset($_POST['horas_fin']) ? trim($_POST['horas_fin']) : null;
            $metodo_de_pago_id = isset($_POST['metodo_de_pago_id']) ? intval($_POST['metodo_de_pago_id']) : null;
            $servicios = isset($_POST['servicios']) ? $_POST['servicios'] : [];

            if (!$id || !$nombrenegocio || !$tipodenegocio || !$ubicaciondelnegocio || !$phonenegocio || !$emailnegocio || !$dias_operacion || !$horas_operacion || !$horas_fin || !$metodo_de_pago_id || empty($servicios)) {
                die("Error: Todos los campos son obligatorios, incluidos los servicios.");
            }

            try {
                $pdo->beginTransaction();
                
                // Verificar si se ha subido un nuevo logo
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    // Leer el nuevo logo en binario
                    $logo_binario = file_get_contents($_FILES['logo']['tmp_name']);
                    
                    // Preparar para PostgreSQL si es necesario
                    if (function_exists('pg_escape_bytea')) {
                        $logo_escapado = pg_escape_bytea($logo_binario);
                        $logo = $logo_escapado; // Asignar a la variable que usarás en bindParam
                    } else {
                        $logo = $logo_binario; // Para PDO
                    }
                    
                    // Actualizar los datos del negocio incluyendo el logo
                    $stmt = $pdo->prepare("UPDATE negocio SET nombrenegocio = ?, tipodenegocio = ?, ubicaciondelnegocio = ?, 
                                          phonenegocio = ?, emailnegocio = ?, dias_operacion = ?, horas_operacion = ?, 
                                          horas_fin = ?, metodo_de_pago_id = ?, logo = ? WHERE id = ?");
                    $stmt->bindParam(1, $nombrenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(2, $tipodenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(3, $ubicaciondelnegocio, PDO::PARAM_STR);
                    $stmt->bindParam(4, $phonenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(5, $emailnegocio, PDO::PARAM_STR);
                    $stmt->bindParam(6, $dias_operacion, PDO::PARAM_STR);
                    $stmt->bindParam(7, $horas_operacion, PDO::PARAM_STR);
                    $stmt->bindParam(8, $horas_fin, PDO::PARAM_STR);
                    $stmt->bindParam(9, $metodo_de_pago_id, PDO::PARAM_INT);
                    $stmt->bindParam(10, $logo, PDO::PARAM_LOB);
                    $stmt->bindParam(11, $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // Actualizar los datos del negocio sin cambiar el logo
                    $stmt = $pdo->prepare("UPDATE negocio SET nombrenegocio = ?, tipodenegocio = ?, ubicaciondelnegocio = ?, 
                                          phonenegocio = ?, emailnegocio = ?, dias_operacion = ?, horas_operacion = ?, 
                                          horas_fin = ?, metodo_de_pago_id = ? WHERE id = ?");
                    $stmt->execute([$nombrenegocio, $tipodenegocio, $ubicaciondelnegocio, $phonenegocio, $emailnegocio, 
                                  $dias_operacion, $horas_operacion, $horas_fin, $metodo_de_pago_id, $id]);
                }
        
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
            $email_empleado = isset($_POST['email_empleado']) ? trim($_POST['email_empleado']) : null;
            $edad = isset($_POST['edad']) ? intval($_POST['edad']) : null;
            $negocio_id = isset($_POST['negocio_id']) ? intval($_POST['negocio_id']) : null;
            $disponibilidad = isset($_POST['disponibilidad']) ? trim($_POST['disponibilidad']) : null;
            $metodo_de_cobro = isset($_POST['metodo_de_cobro']) ? trim($_POST['metodo_de_cobro']) : null;
            $descripcion = isset($_POST['descripcion']) ? trim($_POST['descripcion']) : null;
            // La contraseña solo se actualiza si se proporciona
            $contra_empleados = isset($_POST['contra_empleados']) && !empty($_POST['contra_empleados']) ? 
                               password_hash($_POST['contra_empleados'], PASSWORD_DEFAULT) : null;

            if (!$id || !$nombreempleado || !$phoneempleado || !$email_empleado || !$edad || !$negocio_id || 
                !$disponibilidad || !$metodo_de_cobro || !$descripcion) {
                die("Error: Todos los campos son obligatorios.");
            }

            try {
                $pdo->beginTransaction();
            
                // Obtener el email actual del empleado
                $stmt = $pdo->prepare("SELECT email_empleado FROM empleados WHERE id = ?");
                $stmt->execute([$id]);
                $empleado_actual = $stmt->fetch(PDO::FETCH_ASSOC);
            
                if (!$empleado_actual) {
                    throw new Exception("Error: No se encontró el empleado.");
                }
            
                $email_empleado_actual = $empleado_actual['email_empleado'];
            
                // Si el email cambió, actualizarlo en la tabla usuarios
                if ($email_empleado !== $email_empleado_actual) {
                    // Buscar el usuario asociado al empleado
                    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email_usuarios = ? AND empleado_id = ?");
                    $stmt->execute([$email_empleado_actual, $id]);
                    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($usuario) {
                        // Actualizar el email en la tabla usuarios
                        $stmt = $pdo->prepare("UPDATE usuarios SET email_usuarios = ? WHERE id = ?");
                        $stmt->execute([$email_empleado, $usuario['id']]);
                    }
                }
            
                // Si se proporcionó una nueva contraseña, actualizarla
                if ($contra_empleados) {
                    $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE empleado_id = ?");
                    $stmt->execute([$contra_empleados, $id]);
                }
                
                // Verificar si se ha subido una nueva imagen de perfil
                if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
                    // Leer la nueva imagen en binario
                    $foto_binaria = file_get_contents($_FILES['foto_de_perfil']['tmp_name']);
                    
                    // Preparar para PostgreSQL si es necesario
                    if (function_exists('pg_escape_bytea')) {
                        $foto_escapada = pg_escape_bytea($foto_binaria);
                        $foto_de_perfil = $foto_escapada; // Asignar a la variable que usarás en bindParam
                    } else {
                        $foto_de_perfil = $foto_binaria; // Para PDO
                    }
                    
                    // Actualizar los datos del empleado incluyendo la foto
                    $stmt = $pdo->prepare("UPDATE empleados SET nombreempleado = ?, phoneempleado = ?, email_empleado = ?, 
                                          edad = ?, negocio_id = ?, disponibilidad = ?, metodo_de_cobro = ?, 
                                          descripcion = ?, foto_de_perfil = ? WHERE id = ?");
                    $stmt->bindParam(1, $nombreempleado, PDO::PARAM_STR);
                    $stmt->bindParam(2, $phoneempleado, PDO::PARAM_STR);
                    $stmt->bindParam(3, $email_empleado, PDO::PARAM_STR);
                    $stmt->bindParam(4, $edad, PDO::PARAM_INT);
                    $stmt->bindParam(5, $negocio_id, PDO::PARAM_INT);
                    $stmt->bindParam(6, $disponibilidad, PDO::PARAM_STR);
                    $stmt->bindParam(7, $metodo_de_cobro, PDO::PARAM_STR);
                    $stmt->bindParam(8, $descripcion, PDO::PARAM_STR);
                    $stmt->bindParam(9, $foto_de_perfil, PDO::PARAM_LOB);
                    $stmt->bindParam(10, $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // Actualizar los datos del empleado sin cambiar la foto
                    $stmt = $pdo->prepare("UPDATE empleados SET nombreempleado = ?, phoneempleado = ?, email_empleado = ?, 
                                          edad = ?, negocio_id = ?, disponibilidad = ?, metodo_de_cobro = ?, 
                                          descripcion = ? WHERE id = ?");
                    $stmt->execute([$nombreempleado, $phoneempleado, $email_empleado, $edad, $negocio_id, 
                                  $disponibilidad, $metodo_de_cobro, $descripcion, $id]);
                }
            
                $pdo->commit();
                header("Location: ../public/admin/index.php");
                exit;
            } catch (Exception $e) {
                $pdo->rollBack();
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;

        default:
            die("Error: Tipo de entidad no válido.");
    }
}
?>