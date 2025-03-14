<?php
require_once '../config/confg.php';

// Verificar conexión a base de datos
if (!isset($pdo) || !($pdo instanceof PDO)) {
    error_log("Error: No se ha establecido una conexión válida a la base de datos");
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        // Especificar explícitamente que queremos los datos como binarios
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        
        // Recuperar la imagen del empleado según el ID
        $stmt = $pdo->prepare("SELECT foto_de_perfil FROM empleados WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && isset($row['foto_de_perfil']) && $row['foto_de_perfil'] !== null) {
            $imagen_binaria = $row['foto_de_perfil'];
            
            // Si es un recurso, convertirlo a cadena
            if (is_resource($imagen_binaria)) {
                $imagen_binaria = stream_get_contents($imagen_binaria);
            }
            
            // Verificar que la imagen binaria no esté vacía
            if ($imagen_binaria && is_string($imagen_binaria)) {
                // Detectar el tipo MIME
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($imagen_binaria);
                
                // Si el tipo MIME no se detecta, usar un valor predeterminado
                if (!$mime_type || $mime_type == 'application/octet-stream') {
                    $mime_type = "image/jpeg";
                }
                
                // Enviar los encabezados para la imagen
                header("Content-Type: $mime_type");
                echo $imagen_binaria;
                exit;
            }
        }
        
        // Si llegamos aquí, no se encontró la imagen o estaba vacía
        error_log("No se encontró la imagen para el ID: $id o está vacía");
        // Continuar al código de la imagen por defecto
        
    } catch (Exception $e) {
        error_log("Error al recuperar imagen: " . $e->getMessage());
        // Continuar al código de la imagen por defecto
    }
}

// Imagen por defecto con ruta absoluta
header("Content-Type: image/jpeg");
readfile("assets/images/mapache.png");
if (file_exists($rutaImagen)) {
    readfile($rutaImagen);
} else {
    error_log("Imagen por defecto no encontrada en: " . $rutaImagen);
    echo "Imagen no disponible";
}
exit;
?>