<?php
$host = "localhost";
$dbname = "estetica_roja";
$user = "postgres";
$password = "daniel2002";

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Error en la conexión: " . $e->getMessage());
}
?>
