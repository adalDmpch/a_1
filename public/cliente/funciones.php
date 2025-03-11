<?php
function limpiarInput($data) {
    return htmlspecialchars(trim($data));
}

function manejarSubidaArchivo($archivo, $directorio, $extensiones, $max_tamano) {
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Error en la subida del archivo");
    }

    $nombre_original = basename($archivo['name']);
    $tipo = strtolower(pathinfo($nombre_original, PATHINFO_EXTENSION));
    $tamano = $archivo['size'];

    // Validar extensión
    if (!in_array($tipo, $extensiones)) {
        throw new Exception("Tipo de archivo no permitido");
    }

    // Validar tamaño
    if ($tamano > $max_tamano) {
        throw new Exception("El archivo excede el tamaño máximo permitido");
    }

    // Generar nombre único
    $nombre_unico = uniqid() . '.' . $tipo;
    $ruta_completa = $directorio . $nombre_unico;

    if (!move_uploaded_file($archivo['tmp_name'], $ruta_completa)) {
        throw new Exception("Error al guardar el archivo");
    }

    return ['nombre' => $nombre_unico, 'ruta' => $ruta_completa];
}
?>