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
                
                if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                    // Leer el archivo en binario
                    $logo_binario = file_get_contents($_FILES['logo']['tmp_name']);
                    
                    // ✅ Actualizar los datos del negocio incluyendo el logo
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
                    $stmt->bindParam(10, $logo_binario, PDO::PARAM_LOB); // ✅ Usar la imagen en binario directamente
                    $stmt->bindParam(11, $id, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // ✅ Actualizar sin modificar el logo
                    $stmt = $pdo->prepare("UPDATE negocio SET nombrenegocio = ?, tipodenegocio = ?, ubicaciondelnegocio = ?, 
                                          phonenegocio = ?, emailnegocio = ?, dias_operacion = ?, horas_operacion = ?, 
                                          horas_fin = ?, metodo_de_pago_id = ? WHERE id = ?");
                    $stmt->bindParam(1, $nombrenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(2, $tipodenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(3, $ubicaciondelnegocio, PDO::PARAM_STR);
                    $stmt->bindParam(4, $phonenegocio, PDO::PARAM_STR);
                    $stmt->bindParam(5, $emailnegocio, PDO::PARAM_STR);
                    $stmt->bindParam(6, $dias_operacion, PDO::PARAM_STR);
                    $stmt->bindParam(7, $horas_operacion, PDO::PARAM_STR);
                    $stmt->bindParam(8, $horas_fin, PDO::PARAM_STR);
                    $stmt->bindParam(9, $metodo_de_pago_id, PDO::PARAM_INT);
                    $stmt->bindParam(10, $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
        
                // ✅ Eliminar servicios antiguos y registrar los nuevos
                $stmt = $pdo->prepare("DELETE FROM negocio_servicios WHERE negocio_id = ?");
                $stmt->execute([$id]);
        
                $stmt = $pdo->prepare("INSERT INTO negocio_servicios (negocio_id, servicio_id) VALUES (?, ?)");
                foreach ($servicios as $servicio_id) {
                    $stmt->execute([$id, $servicio_id]);
                }
        
                $pdo->commit();
                header("Location: ../public/admin/index.php");
                exit;
        
            } catch (PDOException $e) {
                $pdo->rollBack();
                die("Error en la base de datos: " . $e->getMessage());
            }
            break;
            
            case 'empleado':
                $id = isset($_POST['id']) ? intval($_POST['id']) : null;
                $nombreempleado = trim($_POST['nombreempleado'] ?? '');
                $phoneempleado = trim($_POST['phoneempleado'] ?? '');
                $email_empleado = trim($_POST['email_empleado'] ?? '');
                $edad = intval($_POST['edad'] ?? 0);
                $negocio_id = intval($_POST['negocio_id'] ?? 0);
                $disponibilidad = trim($_POST['disponibilidad'] ?? '');
                $metodo_de_cobro = trim($_POST['metodo_de_cobro'] ?? '');
                $descripcion = trim($_POST['descripcion'] ?? '');
                $contra_empleados = !empty($_POST['contra_empleados']) ? password_hash($_POST['contra_empleados'], PASSWORD_DEFAULT) : null;
    
                if (!$id || !$nombreempleado || !$phoneempleado || !$email_empleado || !$edad || !$negocio_id || !$disponibilidad || !$metodo_de_cobro || !$descripcion) {
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
                        $stmt = $pdo->prepare("UPDATE usuarios SET email_usuarios = ? WHERE empleado_id = ?");
                        $stmt->execute([$email_empleado, $id]);
                    }
    
                    // Si se proporcionó una nueva contraseña, actualizarla
                    if ($contra_empleados) {
                        $stmt = $pdo->prepare("UPDATE usuarios SET password = ? WHERE empleado_id = ?");
                        $stmt->execute([$contra_empleados, $id]);
                    }
    
                    // Verificar si se ha subido una nueva imagen de perfil
                    if (isset($_FILES['foto_de_perfil']) && $_FILES['foto_de_perfil']['error'] === UPLOAD_ERR_OK) {
                        $foto_binaria = file_get_contents($_FILES['foto_de_perfil']['tmp_name']); // Leer imagen
    
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
                        $stmt->bindParam(9, $foto_binaria, PDO::PARAM_LOB); // ✅ Se guarda correctamente
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