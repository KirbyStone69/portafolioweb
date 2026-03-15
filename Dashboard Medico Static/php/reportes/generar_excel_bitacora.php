<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';

// aqui incluyo la libreria PhpSpreadsheet
require_once '../../vendor/phpspreadsheet/src/Bootstrap.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// aqui me conecto a la base de datos
require_once __DIR__ . "/../mock_db.php";
$conexion = new MockMysqli();
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo las fechas del filtro si vienen
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// aqui creo el objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Bitácora');

// aqui pongo el titulo
$sheet->setCellValue('A1', 'Reporte de Bitácora de Acceso');
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// aqui muestro el periodo si hay fechas
$row = 2;
if ($fecha_inicio && $fecha_fin) {
    $sheet->setCellValue('A' . $row, 'Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)));
    $sheet->mergeCells('A' . $row . ':G' . $row);
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $row++;
}

$sheet->setCellValue('A' . $row, 'Fecha de generación: ' . date('d/m/Y H:i:s'));
$sheet->mergeCells('A' . $row . ':G' . $row);

// aqui pongo los headers
$row += 2;
$headers = ['ID', 'Fecha/Hora', 'Usuario', 'Rol', 'Tipo Acción', 'Módulo', 'Descripción'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $sheet->getStyle($col . $row)->getFont()->setBold(true);
    $sheet->getStyle($col . $row)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE0E0E0');
    $col++;
}

// aqui construyo el query base con los nombres correctos de columnas
$sql = "SELECT 
            b.IdBitacora,
            b.FechaHora,
            b.TipoAccion,
            b.Modulo,
            b.DescripcionAccion,
            u.NombreCompleto,
            u.Rol
        FROM Bitacora_Acceso b
        LEFT JOIN Usuarios_Sistema u ON b.IdUsuario = u.IdUsuario";

// aqui agrego el filtro de fechas si viene
if ($fecha_inicio && $fecha_fin) {
    $sql .= " WHERE DATE(b.FechaHora) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY b.FechaHora DESC LIMIT 200";
$resultado = $conexion->query($sql);

// aqui agrego los datos
$row++;
while ($data = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['IdBitacora']);
    $sheet->setCellValue('B' . $row, date('d/m/Y H:i:s', strtotime($data['FechaHora'])));
    $sheet->setCellValue('C' . $row, $data['NombreCompleto'] ?? 'N/A');
    $sheet->setCellValue('D' . $row, $data['Rol'] ?? 'N/A');
    $sheet->setCellValue('E' . $row, $data['TipoAccion']);
    $sheet->setCellValue('F' . $row, $data['Modulo']);
    $sheet->setCellValue('G' . $row, $data['DescripcionAccion'] ?? '');
    
    // aqui pongo color segun el tipo de accion
    if ($data['TipoAccion'] == 'Login') {
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD4EDDA');
    } elseif ($data['TipoAccion'] == 'Logout') {
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCFE2FF');
    } elseif ($data['TipoAccion'] == 'Eliminar') {
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF8D7DA');
    } elseif ($data['TipoAccion'] == 'Editar') {
        $sheet->getStyle('E' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFF3CD');
    }
    
    $row++;
}

// aqui ajusto columnas
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$conexion->close();

// aqui mando el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_bitacora_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
