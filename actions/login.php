<?php
session_start();
require '../config/confg.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    $stmt = $pdo->prepare("SELECT id, email_usuario, password, rol FROM usuarios WHERE email_usuario = ? AND activo = TRUE");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $hash_contra = $usuario['password'];

        // Verificar si la contraseña está encriptada usando password_verify()
        if (password_verify($password, $hash_contra) || $password === $hash_contra) {  
            session_regenerate_id(true);
            $_SESSION["user_id"] = $usuario["id"];
            $_SESSION["rol"] = $usuario["rol"];
            $_SESSION["email"] = $usuario["email_usuario"];

            switch ($usuario["rol"]) {
                case "admin":
                    header("Location: ../public/admin/index.php");
                    break;
                case "empleado":
                    header("Location: ../public/empleado/inicio.php");
                    break;
                case "cliente":
                    header("Location: ../public/cliente/perfil.php");
                    break;
                default:
                    header("Location: ../public/login.php");
                    break;
            }
            exit();
        }
    }

    echo "<script>alert('Usuario o contraseña incorrectos'); window.location.href = '../public/login.php';</script>";
}
?>
