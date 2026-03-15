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
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui creo el objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Médicos');

// aqui pongo el titulo
$sheet->setCellValue('A1', 'Reporte de Médicos');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

$sheet->setCellValue('A2', 'Fecha de generación: ' . date('d/m/Y H:i:s'));
$sheet->mergeCells('A2:F2');

// aqui pongo los headers
$row = 4;
$headers = ['ID', 'Nombre Completo', 'Cédula', 'Especialidad', 'Teléfono', 'Estado'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $sheet->getStyle($col . $row)->getFont()->setBold(true);
    $sheet->getStyle($col . $row)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE0E0E0');
    $col++;
}

// aqui obtengo los datos
$sql = "SELECT m.*, e.NombreEspecialidad 
        FROM Control_Medicos m
        LEFT JOIN Especialidades e ON m.EspecialidadId = e.IdEspecialidad
        ORDER BY m.FechaIngreso DESC";
$resultado = $conexion->query($sql);

// aqui agrego los datos
$row = 5;
while ($data = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['IdMedico']);
    $sheet->setCellValue('B' . $row, $data['NombreCompleto']);
    $sheet->setCellValue('C' . $row, $data['CedulaProfesional']);
    $sheet->setCellValue('D' . $row, $data['NombreEspecialidad']);
    $sheet->setCellValue('E' . $row, $data['Telefono']);
    $sheet->setCellValue('F' . $row, $data['Estatus'] == 1 ? 'Activo' : 'Inactivo');
    
    // aqui pongo color segun el estatus
    if ($data['Estatus'] == 1) {
        $sheet->getStyle('F' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD4EDDA');
    }
    
    $row++;
}

// aqui ajusto columnas
foreach (range('A', 'F') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$conexion->close();

// aqui mando el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_medicos_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
