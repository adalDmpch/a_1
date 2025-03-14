<?php
require_once '../config/confg.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("SELECT logo FROM negocio WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row && !empty($row['logo'])) {
            // Para PostgreSQL con bytea, necesitamos usar pg_unescape_bytea si se usa pg_escape_bytea al guardar
            if (function_exists('pg_unescape_bytea')) {
                $imagen_binaria = pg_unescape_bytea($row['logo']);
            } else {
                // Si la extensión pg no está disponible, asumimos que PDO ya lo maneja
                $imagen_binaria = $row['logo'];
            }
            
            // Detectar el tipo de imagen o usar un tipo fijo
            if (function_exists('finfo_buffer')) {
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($imagen_binaria);
                
                if (!$mime_type) {
                    $mime_type = "image/jpeg";
                }
            } else {
                // Si no se puede detectar, asumir jpeg
                $mime_type = "image/jpeg";
            }
            
            header("Content-Type: $mime_type");
            echo $imagen_binaria;
            exit;
        }
    } catch (Exception $e) {
        // Log el error pero no mostrar al usuario
        error_log("Error al recuperar imagen: " . $e->getMessage());
    }
}

// Imagen por defecto si no se encontró la solicitada
header("Content-Type: image/jpeg"); 
readfile("rigby.jpg");
exit;
?>