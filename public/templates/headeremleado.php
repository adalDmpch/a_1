<?php
// templates/headeradmin.php
// Recibir título de página como parámetro

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title><?= $pageTitle ?></title>
    <style>



        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Inter:wght@300;400;600&display=swap');

        .font-heading {
            font-family: 'Poppins', sans-serif;
        }

        .font-body {
            font-family: 'Inter', sans-serif;
        }
        .transition-custom {
            transition: all 0.3s ease;
        }

        .hover-zoom {
            transition: transform 0.3s ease;
        }

        .hover-zoom:hover {
            transform: scale(1.03);
        }

        .calendar-day {
            min-height: 100px;
            transition: all 0.2s;
        }

        .calendar-day:hover:not(.inactive) {
            background-color: #f0fdf4;
            border-color: #10b981;
        }

        .calendar-day.inactive {
            background-color: #f9fafb;
            color: #9ca3af;
        }

        .appointment {
            border-left: 3px solid #10b981;
        }
    </style>
</head>
<body class="font-body bg-gray-50">
