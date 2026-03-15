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
$sheet->setTitle('Agenda');

// aqui pongo el titulo
$sheet->setCellValue('A1', 'Reporte de Agenda');
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// aqui muestro el periodo si hay fechas
$row = 2;
if ($fecha_inicio && $fecha_fin) {
    $sheet->setCellValue('A' . $row, 'Período: ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_fin)));
    $sheet->mergeCells('A' . $row . ':F' . $row);
    $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    $row++;
}

$sheet->setCellValue('A' . $row, 'Fecha de generación: ' . date('d/m/Y H:i:s'));
$sheet->mergeCells('A' . $row . ':F' . $row);

// aqui pongo los headers
$row += 2;
$headers = ['ID', 'Fecha/Hora', 'Paciente', 'Médico', 'Motivo', 'Estado'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $row, $header);
    $sheet->getStyle($col . $row)->getFont()->setBold(true);
    $sheet->getStyle($col . $row)->getFill()
        ->setFillType(Fill::FILL_SOLID)
        ->getStartColor()->setARGB('FFE0E0E0');
    $col++;
}

// aqui construyo el query base
$sql = "SELECT 
            a.*,
            p.NombreCompleto as NombrePaciente,
            m.NombreCompleto as NombreMedico
        FROM Control_Agenda a
        LEFT JOIN Control_Pacientes p ON a.IdPaciente = p.IdPaciente
        LEFT JOIN Control_Medicos m ON a.IdMedico = m.IdMedico";

// aqui agrego el filtro de fechas si viene
if ($fecha_inicio && $fecha_fin) {
    $sql .= " WHERE DATE(a.FechaCita) BETWEEN '$fecha_inicio' AND '$fecha_fin'";
}

$sql .= " ORDER BY a.FechaCita DESC";
$resultado = $conexion->query($sql);

// aqui agrego los datos
$row++;
while ($data = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['IdCita']);
    $sheet->setCellValue('B' . $row, date('d/m/Y H:i', strtotime($data['FechaCita'])));
    $sheet->setCellValue('C' . $row, $data['NombrePaciente']);
    $sheet->setCellValue('D' . $row, $data['NombreMedico']);
    $sheet->setCellValue('E' . $row, $data['MotivoConsulta']);
    $sheet->setCellValue('F' . $row, $data['EstadoCita']);
    
    // aqui pongo color segun el estado
    if ($data['EstadoCita'] == 'Completada') {
        $sheet->getStyle('F' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD4EDDA');
    } elseif ($data['EstadoCita'] == 'Programada') {
        $sheet->getStyle('F' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFCFE2FF');
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
header('Content-Disposition: attachment;filename="reporte_agenda_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
