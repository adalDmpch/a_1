<?php
$host = "localhost";
$dbname = "nombre_de_tu_base";
$user = "usuario_bd";
$password = "contraseña_bd";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
