<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';

// aqui incluyo la libreria TCPDF
require_once '../../vendor/tcpdf/tcpdf.php';

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo las fechas del filtro si vienen
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// aqui creo el objeto PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// aqui configuro la informacion del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dr Simi - Sistema Clínico');
$pdf->SetTitle('Reporte de Pagos');
$pdf->SetSubject('Reporte de Pagos');

// aqui quito el header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// aqui agrego una pagina
$pdf->AddPage();

// aqui configuro la fuente
$pdf->SetFont('helvetica', 'B', 16);

// aqui pongo el titulo
$pdf->Cell(0, 10, 'Reporte de Pagos', 0, 1, 'C');
$pdf->Ln(3);

// aqui muestro el rango de fechas si aplica
if ($fecha_inicio && $fecha_fin) {
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(0, 5, 'Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)), 0, 1, 'C');
}

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
$pdf->Ln(5);

// aqui construyo el query base
$sql = "SELECT 
            p.IdPago,
            p.Monto,
            p.MetodoPago,
            p.FechaPago,
            p.Referencia,
            p.EstatusPago,
            pac.NombreCompleto as NombrePaciente,
            pac.CURP,
            a.FechaCita,
            m.NombreCompleto as NombreMedico
        FROM Gestor_Pagos p
        LEFT JOIN Control_Pacientes pac ON p.IdPaciente = pac.IdPaciente
        LEFT JOIN Control_Agenda a ON p.IdCita = a.IdCita
        LEFT JOIN Control_Medicos m ON a.IdMedico = m.IdMedico";

// aqui agrego el filtro de fechas si viene
if ($fecha_inicio && $fecha_fin) {
    $sql .= " WHERE DATE(p.FechaPago) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY p.FechaPago DESC";

$resultado = $conexion->query($sql);

// aqui creo la tabla
$html = '<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#e0e0e0;">
            <th width="8%"><b>ID</b></th>
            <th width="22%"><b>Paciente</b></th>
            <th width="15%"><b>Médico</b></th>
            <th width="12%"><b>Monto</b></th>
            <th width="13%"><b>Método</b></th>
            <th width="15%"><b>Fecha Pago</b></th>
            <th width="15%"><b>Estado</b></th>
        </tr>
    </thead>
    <tbody>';

// aqui agrego cada fila
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // aqui formateo la fecha
        $fecha = date('d/m/Y', strtotime($row['FechaPago']));
        
        // aqui pongo el color segun el estado
        $color = '';
        if ($row['EstatusPago'] == 'Pagado') {
            $color = 'background-color:#d4edda;';
        } elseif ($row['EstatusPago'] == 'Pendiente') {
            $color = 'background-color:#fff3cd;';
        } else {
            $color = 'background-color:#f8d7da;';
        }
        
        $html .= '<tr style="' . $color . '">
            <td>' . $row['IdPago'] . '</td>
            <td>' . htmlspecialchars($row['NombrePaciente']) . '</td>
            <td>' . htmlspecialchars($row['NombreMedico']) . '</td>
            <td>$' . number_format($row['Monto'], 2) . '</td>
            <td>' . htmlspecialchars($row['MetodoPago']) . '</td>
            <td>' . $fecha . '</td>
            <td>' . htmlspecialchars($row['EstatusPago']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" align="center">No hay pagos registrados</td></tr>';
}

$html .= '</tbody></table>';

// aqui escribo el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// aqui calculo el total de pagos respetando el filtro de fechas
$sqlTotal = "SELECT SUM(Monto) as Total FROM Gestor_Pagos WHERE EstatusPago = 'Pagado'";
if ($fecha_inicio && $fecha_fin) {
    $sqlTotal .= " AND DATE(FechaPago) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
$resultTotal = $conexion->query($sqlTotal);
$total = $resultTotal->fetch_assoc()['Total'] ?? 0;

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Total Pagado: $' . number_format($total, 2), 0, 1, 'R');

// aqui cierro la conexion
$conexion->close();

// aqui genero el PDF y lo mando al navegador
$pdf->Output('reporte_pagos_' . date('Ymd_His') . '.pdf', 'I');
?>
