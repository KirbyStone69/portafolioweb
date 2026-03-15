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

// aqui creo el objeto PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// aqui configuro la informacion del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dr Simi - Sistema Clínico');
$pdf->SetTitle('Reporte de Médicos');
$pdf->SetSubject('Reporte de Médicos');

// aqui quito el header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// aqui agrego una pagina
$pdf->AddPage();

// aqui configuro la fuente
$pdf->SetFont('helvetica', 'B', 16);

// aqui pongo el titulo
$pdf->Cell(0, 10, 'Reporte de Médicos', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
$pdf->Ln(5);

// aqui obtengo los datos de medicos con su especialidad
$sql = "SELECT 
            m.IdMedico,
            m.NombreCompleto,
            m.CedulaProfesional,
            e.NombreEspecialidad,
            m.Telefono,
            m.CorreoElectronico,
            m.FechaIngreso,
            m.Estatus
        FROM Control_Medicos m
        LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
        ORDER BY m.FechaIngreso DESC";

$resultado = $conexion->query($sql);

// aqui creo la tabla
$html = '<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#e0e0e0;">
            <th width="5%"><b>ID</b></th>
            <th width="25%"><b>Nombre Completo</b></th>
            <th width="15%"><b>Cédula</b></th>
            <th width="20%"><b>Especialidad</b></th>
            <th width="15%"><b>Teléfono</b></th>
            <th width="10%"><b>Estado</b></th>
        </tr>
    </thead>
    <tbody>';

// aqui agrego cada fila
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // aqui pongo el color segun el estatus
        $color = $row['Estatus'] == 1 ? 'background-color:#d4edda;' : 'background-color:#f8d7da;';
        $estatusTexto = $row['Estatus'] == 1 ? 'Activo' : 'Inactivo';
        
        $html .= '<tr style="' . $color . '">
            <td>' . $row['IdMedico'] . '</td>
            <td>' . htmlspecialchars($row['NombreCompleto']) . '</td>
            <td>' . htmlspecialchars($row['CedulaProfesional']) . '</td>
            <td>' . htmlspecialchars($row['NombreEspecialidad']) . '</td>
            <td>' . htmlspecialchars($row['Telefono']) . '</td>
            <td>' . $estatusTexto . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="6" align="center">No hay médicos registrados</td></tr>';
}

$html .= '</tbody></table>';

// aqui escribo el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// aqui calculo estadisticas por especialidad
$sqlStats = "SELECT 
                e.NombreEspecialidad,
                COUNT(m.IdMedico) as Total
             FROM Especialidades e
             LEFT JOIN Control_Medicos m ON e.IdEspecialidad = m.EspecialidadId
             WHERE m.Estatus = 1
             GROUP BY e.IdEspecialidad, e.NombreEspecialidad
             ORDER BY Total DESC";
$resultStats = $conexion->query($sqlStats);

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Médicos por Especialidad:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

// aqui listo cada especialidad
while ($stat = $resultStats->fetch_assoc()) {
    $pdf->Cell(0, 5, '• ' . $stat['NombreEspecialidad'] . ': ' . $stat['Total'] . ' médico(s)', 0, 1, 'L');
}

// aqui cierro la conexion
$conexion->close();

// aqui genero el PDF y lo mando al navegador
$pdf->Output('reporte_medicos_' . date('Ymd_His') . '.pdf', 'I');
?>
