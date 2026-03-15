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

// aqui creo el objeto PDF
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// aqui configuro la informacion del documento
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Dr Simi - Sistema Clínico');
$pdf->SetTitle('Reporte de Pacientes');
$pdf->SetSubject('Reporte de Pacientes');

// aqui quito el header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// aqui agrego una pagina
$pdf->AddPage();

// aqui configuro la fuente
$pdf->SetFont('helvetica', 'B', 16);

// aqui pongo el titulo
$pdf->Cell(0, 10, 'Reporte de Pacientes', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
$pdf->Ln(5);

// aqui obtengo los datos de pacientes
$sql = "SELECT 
            IdPaciente,
            NombreCompleto,
            CURP,
            FechaNacimiento,
            Sexo,
            Telefono,
            CorreoElectronico,
            FechaRegistro,
            Estatus
        FROM Control_Pacientes
        ORDER BY FechaRegistro DESC";

$resultado = $conexion->query($sql);

// aqui creo la tabla
$html = '<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#e0e0e0;">
            <th width="5%"><b>ID</b></th>
            <th width="25%"><b>Nombre Completo</b></th>
            <th width="18%"><b>CURP</b></th>
            <th width="12%"><b>Sexo</b></th>
            <th width="15%"><b>Teléfono</b></th>
            <th width="15%"><b>Fecha Registro</b></th>
            <th width="10%"><b>Estado</b></th>
        </tr>
    </thead>
    <tbody>';

// aqui agrego cada fila
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // aqui formateo la fecha
        $fecha = date('d/m/Y', strtotime($row['FechaRegistro']));
        
        // aqui pongo el genero completo
        $sexo = ($row['Sexo'] == 'M') ? 'Masculino' : 'Femenino';
        
        // aqui pongo el color segun el estatus
        $color = $row['Estatus'] == 1 ? 'background-color:#d4edda;' : 'background-color:#f8d7da;';
        $estatusTexto = $row['Estatus'] == 1 ? 'Activo' : 'Inactivo';
        
        $html .= '<tr style="' . $color . '">
            <td>' . $row['IdPaciente'] . '</td>
            <td>' . htmlspecialchars($row['NombreCompleto']) . '</td>
            <td>' . htmlspecialchars($row['CURP']) . '</td>
            <td>' . $sexo . '</td>
            <td>' . htmlspecialchars($row['Telefono']) . '</td>
            <td>' . $fecha . '</td>
            <td>' . $estatusTexto . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" align="center">No hay pacientes registrados</td></tr>';
}

$html .= '</tbody></table>';

// aqui escribo el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// aqui calculo estadisticas
$sqlStats = "SELECT 
                COUNT(*) as Total,
                SUM(CASE WHEN Estatus = 1 THEN 1 ELSE 0 END) as Activos,
                SUM(CASE WHEN Sexo = 'M' THEN 1 ELSE 0 END) as Hombres,
                SUM(CASE WHEN Sexo = 'F' THEN 1 ELSE 0 END) as Mujeres
             FROM Control_Pacientes";
$resultStats = $conexion->query($sqlStats);
$stats = $resultStats->fetch_assoc();

$pdf->Ln(5);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Estadísticas Generales:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Total de pacientes: ' . $stats['Total'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Pacientes activos: ' . $stats['Activos'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Hombres: ' . $stats['Hombres'] . ' | Mujeres: ' . $stats['Mujeres'], 0, 1, 'L');

// aqui cierro la conexion
$conexion->close();

// aqui genero el PDF y lo mando al navegador
$pdf->Output('reporte_pacientes_' . date('Ymd_His') . '.pdf', 'I');
?>
