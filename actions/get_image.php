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
        // Desactivar la emulación de prepares para manejar binarios correctamente
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

        // Obtener el logo del negocio
        $stmt = $pdo->prepare("SELECT logo FROM negocio WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && isset($row['logo']) && $row['logo'] !== null) {
            $imagen_binaria = $row['logo'];

            // Para PostgreSQL, debemos usar pg_unescape_bytea si es necesario
            if (is_resource($imagen_binaria)) {
                $imagen_binaria = stream_get_contents($imagen_binaria);
            }

            // Asegurar que la imagen no está vacía
            if ($imagen_binaria && is_string($imagen_binaria)) {
                // Detectar el tipo MIME
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mime_type = $finfo->buffer($imagen_binaria) ?: "image/jpeg";

                // Enviar la imagen con el tipo MIME correcto
                header("Content-Type: $mime_type");
                echo $imagen_binaria;
                exit;
            }
        }
        
        error_log("No se encontró la imagen para el ID: $id o está vacía");

    } catch (Exception $e) {
        error_log("Error al recuperar imagen: " . $e->getMessage());
    }
}

// Imagen por defecto
$rutaImagen = "assets/images/mapache.png";

if (file_exists($rutaImagen)) {
    header("Content-Type: image/jpeg");
    readfile($rutaImagen);
} else {
    error_log("Imagen por defecto no encontrada en: " . $rutaImagen);
    echo "Imagen no disponible";
}
exit;
