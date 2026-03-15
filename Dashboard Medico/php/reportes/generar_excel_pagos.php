<?php
// aqui inicio sesion y verifico que el usuario este logueado
session_start();
require_once '../login/verificar_sesion.php';

// aqui incluyo la libreria PhpSpreadsheet
require_once '../../vendor/phpspreadsheet/src/Bootstrap.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// aqui me conecto a la base de datos
$conexion = new mysqli("localhost", "root", "edereder", "clinica_db");
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// aqui obtengo las fechas del filtro si vienen
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

// aqui creo el objeto Spreadsheet
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// aqui pongo el titulo de la hoja
$sheet->setTitle('Pagos');

// aqui pongo los encabezados
$sheet->setCellValue('A1', 'Reporte de Pagos');
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
$sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

// aqui dejo una fila en blanco
$row += 2;

// aqui pongo los headers de la tabla
$headers = ['ID', 'Paciente', 'Médico', 'Monto', 'Método Pago', 'Fecha Pago', 'Estado'];
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
            p.IdPago,
            p.Monto,
            p.MetodoPago,
            p.FechaPago,
            p.Referencia,
            p.EstatusPago,
            pac.NombreCompleto as NombrePaciente,
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

// aqui agrego cada fila de datos
$row = 5;
$totalPagado = 0;
while ($data = $resultado->fetch_assoc()) {
    $sheet->setCellValue('A' . $row, $data['IdPago']);
    $sheet->setCellValue('B' . $row, $data['NombrePaciente']);
    $sheet->setCellValue('C' . $row, $data['NombreMedico']);
    $sheet->setCellValue('D' . $row, '$' . number_format($data['Monto'], 2));
    $sheet->setCellValue('E' . $row, $data['MetodoPago']);
    $sheet->setCellValue('F' . $row, date('d/m/Y', strtotime($data['FechaPago'])));
    $sheet->setCellValue('G' . $row, $data['EstatusPago']);
    
    // aqui pongo color segun el estado
    if ($data['EstatusPago'] == 'Pagado') {
        $sheet->getStyle('G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFD4EDDA');
        $totalPagado += $data['Monto'];
    } elseif ($data['EstatusPago'] == 'Pendiente') {
        $sheet->getStyle('G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFF3CD');
    } else {
        $sheet->getStyle('G' . $row)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFF8D7DA');
    }
    
    $row++;
}

// aqui pongo el total
$row++;
$sheet->setCellValue('C' . $row, 'Total Pagado:');
$sheet->setCellValue('D' . $row, '$' . number_format($totalPagado, 2));
$sheet->getStyle('C' . $row . ':D' . $row)->getFont()->setBold(true);

// aqui ajusto el ancho de las columnas automaticamente
foreach (range('A', 'G') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// aqui cierro la conexion
$conexion->close();

// aqui configuro los headers para descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_pagos_' . date('Ymd_His') . '.xlsx"');
header('Cache-Control: max-age=0');

// aqui genero el archivo y lo mando
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
