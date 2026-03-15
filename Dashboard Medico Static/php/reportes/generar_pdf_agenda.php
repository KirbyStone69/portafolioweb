<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';

// aqui incluyo la libreria TCPDF
require_once '../../vendor/tcpdf/tcpdf.php';

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
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
$pdf->SetTitle('Reporte de Agenda');
$pdf->SetSubject('Reporte de Agenda');

// aqui quito el header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// aqui agrego una pagina
$pdf->AddPage();

// aqui configuro la fuente
$pdf->SetFont('helvetica', 'B', 16);

// aqui pongo el titulo
$pdf->Cell(0, 10, 'Reporte de Agenda de Citas', 0, 1, 'C');
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
            a.IdCita,
            a.FechaCita,
            a.MotivoConsulta,
            a.EstadoCita,
            p.NombreCompleto as NombrePaciente,
            m.NombreCompleto as NombreMedico,
            e.NombreEspecialidad
        FROM Control_Agenda a
        LEFT JOIN Control_Pacientes p ON a.IdPaciente = p.IdPaciente
        LEFT JOIN Control_Medicos m ON a.IdMedico = m.IdMedico
        LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad";

// aqui agrego el filtro de fechas si viene
if ($fecha_inicio && $fecha_fin) {
    $sql .= " WHERE DATE(a.FechaCita) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY a.FechaCita DESC";

$resultado = $conexion->query($sql);

// aqui creo la tabla
$html = '<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#e0e0e0;">
            <th width="6%"><b>ID</b></th>
            <th width="15%"><b>Fecha/Hora</b></th>
            <th width="22%"><b>Paciente</b></th>
            <th width="22%"><b>Médico</b></th>
            <th width="20%"><b>Motivo</b></th>
            <th width="15%"><b>Estado</b></th>
        </tr>
    </thead>
    <tbody>';

// aqui agrego cada fila
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // aqui formateo la fecha y hora
        $fecha = date('d/m/Y H:i', strtotime($row['FechaCita']));
        
        // aqui pongo el color segun el estado
        $color = '';
        if ($row['EstadoCita'] == 'Completada') {
            $color = 'background-color:#d4edda;';
        } elseif ($row['EstadoCita'] == 'Programada') {
            $color = 'background-color:#cfe2ff;';
        } else {
            $color = 'background-color:#f8d7da;';
        }
        
        // aqui recorto el motivo si es muy largo
        $motivo = strlen($row['MotivoConsulta']) > 30 ? substr($row['MotivoConsulta'], 0, 30) . '...' : $row['MotivoConsulta'];
        
        $html .= '<tr style="' . $color . '">
            <td>' . $row['IdCita'] . '</td>
            <td>' . $fecha . '</td>
            <td>' . htmlspecialchars($row['NombrePaciente']) . '</td>
            <td>' . htmlspecialchars($row['NombreMedico']) . '</td>
            <td>' . htmlspecialchars($motivo) . '</td>
            <td>' . htmlspecialchars($row['EstadoCita']) . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="6" align="center">No hay citas registradas</td></tr>';
}

$html .= '</tbody></table>';

// aqui escribo el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// aqui calculo estadisticas respetando el filtro de fechas
$sqlStats = "SELECT 
                COUNT(*) as Total,
                SUM(CASE WHEN EstadoCita = 'Programada' THEN 1 ELSE 0 END) as Programadas,
                SUM(CASE WHEN EstadoCita = 'Completada' THEN 1 ELSE 0 END) as Completadas,
                SUM(CASE WHEN EstadoCita = 'Cancelada' THEN 1 ELSE 0 END) as Canceladas
             FROM Control_Agenda";
if ($fecha_inicio && $fecha_fin) {
    $sqlStats .= " WHERE DATE(FechaCita) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
$resultStats = $conexion->query($sqlStats);
$stats = $resultStats->fetch_assoc();

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Estadísticas de Citas:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Total de citas: ' . $stats['Total'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Programadas: ' . $stats['Programadas'] . ' | Completadas: ' . $stats['Completadas'] . ' | Canceladas: ' . $stats['Canceladas'], 0, 1, 'L');

// aqui cierro la conexion
$conexion->close();

// aqui genero el PDF y lo mando al navegador
$pdf->Output('reporte_agenda_' . date('Ymd_His') . '.pdf', 'I');
?>
