<?php
// templates/headeradmin.php
// Recibir título de página como parámetro
$pageTitle = $pageTitle ?? 'Bella Hair';
include_once '../templates/mode.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Agregar SweetAlert2 para mejores confirmaciones -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Agregar Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Animaciones personalizadas para los iconos */
        .icon-container {
            transition: all 0.3s ease;
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        
        .icon-container:hover {
            transform: translateY(-3px);
        }
        
        .icon-container:hover i {
            color: #ff6b9d; /* Color rosado al hacer hover */
        }
        
        .pulse-effect {
            animation: pulse 1.5s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .rotate-effect:hover i {
            animation: rotate 0.5s ease-in-out;
        }
        
        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }
            25% {
                transform: rotate(20deg);
            }
            75% {
                transform: rotate(-20deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">