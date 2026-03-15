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
$pdf->SetTitle('Reporte de Bitácora de Acceso');
$pdf->SetSubject('Reporte de Bitácora de Acceso');

// aqui quito el header y footer por defecto
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// aqui agrego una pagina
$pdf->AddPage();

// aqui configuro la fuente
$pdf->SetFont('helvetica', 'B', 16);

// aqui pongo el titulo
$pdf->Cell(0, 10, 'Reporte de Bitácora de Acceso', 0, 1, 'C');
$pdf->Ln(3);

// aqui muestro el rango de fechas si aplica
if ($fecha_inicio && $fecha_fin) {
    $pdf->SetFont('helvetica', '', 11);
    $pdf->Cell(0, 5, 'Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)), 0, 1, 'C');
}

$pdf->SetFont('helvetica', '', 10);
$pdf->Cell(0, 5, 'Fecha de generación: ' . date('d/m/Y H:i:s'), 0, 1, 'R');
$pdf->Ln(5);

// aqui construyo el query base con los nombres correctos de columnas
$sql = "SELECT 
            b.IdBitacora,
            b.FechaHora,
            b.TipoAccion,
            b.Modulo,
            b.DescripcionAccion,
            u.Usuario,
            u.NombreCompleto,
            u.Rol
        FROM Bitacora_Acceso b
        LEFT JOIN Usuarios_Sistema u ON b.IdUsuario = u.IdUsuario";

// aqui agrego el filtro de fechas si viene
if ($fecha_inicio && $fecha_fin) {
    $sql .= " WHERE DATE(b.FechaHora) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY b.FechaHora DESC LIMIT 100";

$resultado = $conexion->query($sql);

// aqui creo la tabla
$html = '<table border="1" cellpadding="4" cellspacing="0">
    <thead>
        <tr style="background-color:#e0e0e0;">
            <th width="5%"><b>ID</b></th>
            <th width="17%"><b>Fecha/Hora</b></th>
            <th width="20%"><b>Usuario</b></th>
            <th width="10%"><b>Rol</b></th>
            <th width="13%"><b>Acción</b></th>
            <th width="15%"><b>Módulo</b></th>
            <th width="20%"><b>Descripción</b></th>
        </tr>
    </thead>
    <tbody>';

// aqui agrego cada fila
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        // aqui formateo la fecha y hora
        $fecha = date('d/m/Y H:i:s', strtotime($row['FechaHora']));
        
        // aqui pongo color segun el tipo de accion
        $color = '';
        if ($row['TipoAccion'] == 'Login') {
            $color = 'background-color:#d4edda;';
        } elseif ($row['TipoAccion'] == 'Logout') {
            $color = 'background-color:#cfe2ff;';
        } elseif ($row['TipoAccion'] == 'Eliminar') {
            $color = 'background-color:#f8d7da;';
        } elseif ($row['TipoAccion'] == 'Editar') {
            $color = 'background-color:#fff3cd;';
        } else {
            $color = 'background-color:#ffffff;';
        }
        
        // aqui recorto la descripcion si es muy larga
        $descripcion = strlen($row['DescripcionAccion']) > 25 ? substr($row['DescripcionAccion'], 0, 25) . '...' : $row['DescripcionAccion'];
        
        $html .= '<tr style="' . $color . '">
            <td>' . $row['IdBitacora'] . '</td>
            <td>' . $fecha . '</td>
            <td>' . htmlspecialchars($row['NombreCompleto'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['Rol'] ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($row['TipoAccion']) . '</td>
            <td>' . htmlspecialchars($row['Modulo']) . '</td>
            <td>' . htmlspecialchars($descripcion ?? '') . '</td>
        </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" align="center">No hay registros en la bitácora</td></tr>';
}

$html .= '</tbody></table>';

// aqui escribo el HTML en el PDF
$pdf->writeHTML($html, true, false, true, false, '');

// aqui muestro nota de que solo se muestran los ultimos 100 registros
$pdf->Ln(5);
$pdf->SetFont('helvetica', 'I', 9);
$pdf->Cell(0, 5, 'Nota: Se muestran los últimos 100 registros de la bitácora', 0, 1, 'L');

// aqui calculo estadisticas por tipo de accion
$sqlStats = "SELECT TipoAccion, COUNT(*) as Total FROM Bitacora_Acceso";
if ($fecha_inicio && $fecha_fin) {
    $sqlStats .= " WHERE DATE(FechaHora) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}
$sqlStats .= " GROUP BY TipoAccion ORDER BY Total DESC";
$resultStats = $conexion->query($sqlStats);

$pdf->Ln(3);
$pdf->SetFont('helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Acciones por Tipo:', 0, 1, 'L');
$pdf->SetFont('helvetica', '', 10);

// aqui listo cada tipo de accion
while ($stat = $resultStats->fetch_assoc()) {
    $pdf->Cell(0, 5, '• ' . $stat['TipoAccion'] . ': ' . $stat['Total'] . ' acción(es)', 0, 1, 'L');
}

// aqui cierro la conexion
$conexion->close();

// aqui genero el PDF y lo mando al navegador
$pdf->Output('reporte_bitacora_' . date('Ymd_His') . '.pdf', 'I');
?>
