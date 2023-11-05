<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../../login/index.php');
        exit();
    }
    require_once "../../../connect.php";
    require_once '../../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");

        $sql = "SELECT * FROM rentals_history ORDER BY `rentals_history`.`id` DESC";
        $result = $connection->query($sql);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headerStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'bfd01d'],
                'size' => 13,
                'name' => 'Century Gothic',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2f2675'], 
            ],
        ];

        $cellStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 11,
                'name' => 'Century Gothic',
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '393188'], 
            ],
        ];

        $size = [
            'font' => [
                'size' => 10,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Czytelnik');
        $sheet->setCellValue('C1', 'Książka');
        $sheet->setCellValue('D1', 'Data wypożyczenia');
        $sheet->setCellValue('E1', 'Data oddania');

        $rowIndex = 2;

        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row["id"]);
            $sheet->setCellValue('B' . $rowIndex, $row["reader"]);
            $sheet->setCellValue('C' . $rowIndex, $row["book"]);
            $sheet->setCellValue('D' . $rowIndex, $row["rental_date"]);
            $sheet->setCellValue('E' . $rowIndex, $row["delivery_date"]);

            $rowIndex++;
        }

        $sheet->getStyle('A1:E1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:E' . ($rowIndex - 1))->applyFromArray($cellStyle);

        $sheet->setCellValue('A' . $rowIndex, '© 2023 WIADERNA.EDU.PL | Powered by BARTIXEN.PL - Bartosz Krasoń');
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($headerStyle);
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($size);
        $sheet->mergeCells('A' . $rowIndex . ':E' . $rowIndex);
        foreach (range('A', 'E') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'rentals_history.xlsx';
        $writer->save($filename);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        readfile($filename);

        $connection->close();
        unlink($filename);

    } catch (Exception $e) {
        echo 'Internal error: ' . $e->getMessage();
    }
?>
