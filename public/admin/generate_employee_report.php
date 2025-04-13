<?php
require '../../config/confg.php';

session_start();
if (!isset($_SESSION["user_id"]) || $_SESSION["rol"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

// Verificar que se recibió un ID de empleado
if (!isset($_POST['employee_id']) || empty($_POST['employee_id'])) {
    header("Location: index.php?mensaje=ID de empleado no válido&tipo=error");
    exit();
}

$empleadoId = $_POST['employee_id'];

// Obtener información del empleado
$stmt = $pdo->prepare("
    SELECT e.*, n.nombrenegocio 
    FROM empleados e
    LEFT JOIN negocio n ON e.negocio_id = n.id
    WHERE e.id = ?
");
$stmt->execute([$empleadoId]);
$empleado = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$empleado) {
    header("Location: index.php?mensaje=Empleado no encontrado&tipo=error");
    exit();
}

// Obtener historial de citas del empleado
$stmtCitas = $pdo->prepare("
    SELECT c.*, cl.nombre as cliente_nombre, s.tipo as servicio
    FROM citas c
    LEFT JOIN cliente cl ON c.cliente_id = cl.id
    LEFT JOIN servicios s ON c.servicio_id = s.id
    WHERE c.empleado_id = ?
    ORDER BY c.fecha DESC, c.hora DESC
");
$stmtCitas->execute([$empleadoId]);
$citas = $stmtCitas->fetchAll(PDO::FETCH_ASSOC);

// Calcular estadísticas
$totalCitas = count($citas);
$citasCompletadas = 0;
$citasPendientes = 0;
$citasCanceladas = 0;

foreach ($citas as $cita) {
    switch ($cita['estado']) {
        case 'completada':
            $citasCompletadas++;
            break;
        case 'pendiente':
            $citasPendientes++;
            break;
        case 'cancelada':
            $citasCanceladas++;
            break;
    }
}

// Calcular porcentaje de citas completadas
$porcentajeCompletadas = $totalCitas > 0 ? round(($citasCompletadas / $totalCitas) * 100) : 0;

// Agrupar citas por mes (para estadísticas)
$citasPorMes = [];
foreach ($citas as $cita) {
    $mes = date('m/Y', strtotime($cita['fecha']));
    if (!isset($citasPorMes[$mes])) {
        $citasPorMes[$mes] = 0;
    }
    $citasPorMes[$mes]++;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Empleado - <?= htmlspecialchars($empleado['nombreempleado']) ?></title>
    <style>
        /* Estilos generales y para impresión */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f9f9f9;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .header {
            text-align: center;
            background-color: #047857;
            color: white;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .section-title {
            color: #047857;
            border-bottom: 2px solid #047857;
            padding-bottom: 5px;
            margin-top: 30px;
            margin-bottom: 15px;
        }
        
        .info-block {
            margin-bottom: 10px;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 150px;
            display: inline-block;
        }
        
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        
        .stat-box {
            background-color: #f2f2f2;
            border-left: 4px solid #047857;
            padding: 10px 15px;
            margin-bottom: 10px;
            width: calc(33% - 10px);
        }
        
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #047857;
        }
        
        .stat-label {
            font-size: 14px;
            color: #555;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th {
            background-color: #047857;
            color: white;
            text-align: left;
            padding: 10px;
        }
        
        td {
            padding: 8px 10px;
            border-bottom: 1px solid #ddd;
        }
        
        tr:nth-child(even) {
            background-color: #f5f5f5;
        }
        
        .print-only {
            display: none;
        }
        
        .no-print {
            display: block;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
            }
            
            .container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            
            .print-only {
                display: block;
            }
            
            .no-print {
                display: none;
            }
            
            .page-break {
                page-break-before: always;
            }
            
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Botones para imprimir (solo visibles en pantalla) -->
        <div class="no-print" style="text-align: right; margin-bottom: 20px;">
            <button onclick="window.print();" style="padding: 8px 16px; background: #047857; color: white; border: none; border-radius: 4px; cursor: pointer;">
                <i class="fas fa-print"></i> Imprimir Reporte
            </button>
            <button onclick="window.location.href='employee_details.php?id=<?= $empleadoId ?>';" style="padding: 8px 16px; background: #6B7280; color: white; border: none; border-radius: 4px; margin-left: 10px; cursor: pointer;">
                Volver
            </button>
        </div>
        
        <!-- Cabecera del reporte -->
        <div class="header">
            <h1>REPORTE DE EMPLEADO
                DE BELLA HAIR
            </h1>
            <p class="print-only">Fecha de generación: <?= date('d/m/Y H:i') ?></p>
        </div>
        
        <!-- Información personal -->
        <h2 class="section-title">INFORMACIÓN PERSONAL</h2>
        
        <div class="info-block">
            <span class="info-label">Nombre:</span>
            <span><?= htmlspecialchars($empleado['nombreempleado']) ?></span>
        </div>
        
        <div class="info-block">
            <span class="info-label">Sucursal:</span>
            <span><?= htmlspecialchars($empleado['nombrenegocio'] ?: 'No asignado') ?></span>
        </div>
        
        <div class="info-block">
            <span class="info-label">Teléfono:</span>
            <span><?= htmlspecialchars($empleado['phoneempleado']) ?></span>
        </div>
        
        <div class="info-block">
            <span class="info-label">Email:</span>
            <span><?= htmlspecialchars($empleado['email_empleado']) ?></span>
        </div>
        
        <div class="info-block">
            <span class="info-label">Edad:</span>
            <span><?= htmlspecialchars($empleado['edad']) ?> años</span>
        </div>
        
        
        
        <!-- Estadísticas -->
        <h2 class="section-title">ESTADÍSTICAS</h2>
        
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-value"><?= $totalCitas ?></div>
                <div class="stat-label">Total de citas</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-value"><?= $citasCompletadas ?></div>
                <div class="stat-label">Citas completadas</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-value"><?= $porcentajeCompletadas ?>%</div>
                <div class="stat-label">Tasa de finalización</div>
            </div>
        </div>
        
        <div class="stats-container">
            <div class="stat-box">
                <div class="stat-value"><?= $citasPendientes ?></div>
                <div class="stat-label">Citas pendientes</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-value"><?= $citasCanceladas ?></div>
                <div class="stat-label">Citas canceladas</div>
            </div>
            
            <div class="stat-box">
                <div class="stat-value"><?= count($citasPorMes) > 0 ? round($totalCitas / count($citasPorMes), 1) : 0 ?></div>
                <div class="stat-label">Promedio mensual</div>
            </div>
        </div>
        
        <!-- Historial de citas -->
        <h2 class="section-title">HISTORIAL DE CITAS</h2>
        
        <?php if ($totalCitas > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Cliente</th>
                    <th>Servicio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($citas as $cita): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($cita['fecha'])) ?></td>
                    <td><?= date('H:i', strtotime($cita['hora'])) ?></td>
                    <td><?= htmlspecialchars($cita['cliente_nombre']) ?></td>
                    <td><?= htmlspecialchars($cita['servicio']) ?></td>
                    <td><?= ucfirst($cita['estado']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p style="text-align: center; color: #666; font-style: italic;">Este empleado no tiene historial de citas registradas.</p>
        <?php endif; ?>
        
        <!-- Pie de página -->
        <div class="print-only" style="margin-top: 50px; text-align: center; color: #666; font-size: 12px; border-top: 1px solid #ddd; padding-top: 10px;">
            Reporte generado el <?= date('d/m/Y') ?> a las <?= date('H:i') ?> - Sistema de Gestión
        </div>
    </div>
</body>
</html><?php
