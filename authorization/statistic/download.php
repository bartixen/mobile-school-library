<?php
    session_start();

    if (!isset($_SESSION['authorization'])) {
        header('Location: ../login/index.php');
        exit();
    }
    require_once "../../connect.php";
    require_once '../../vendor/autoload.php';
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    mysqli_report(MYSQLI_REPORT_STRICT);

    try {
        $connection = new mysqli($host, $db_user, $db_password, $db_name);
        $connection->query("SET NAMES 'utf8'");

        $result1 = $connection->query("SELECT COUNT(*) AS occurrences FROM `authentication`")->fetch_assoc()["occurrences"];
        $result2 = $connection->query("SELECT COUNT(*) AS occurrences FROM `reader`")->fetch_assoc()["occurrences"];
        $result3 = $connection->query("SELECT COUNT(*) AS occurrences FROM `rentals`")->fetch_assoc()["occurrences"];
        $result4 = $connection->query("SELECT COUNT(*) AS occurrences FROM book WHERE id NOT IN (SELECT id_book FROM rentals)")->fetch_assoc()["occurrences"];
        $result5 = $connection->query("SELECT COUNT(*) AS occurrences FROM `book`")->fetch_assoc()["occurrences"];

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

        $sheet->setCellValue('A1', 'Typ');
        $sheet->setCellValue('B1', 'Dane');

        $rowIndex = 2;

        $sheet->setCellValue('A' . $rowIndex, "Ilość użytkowników z uprawnieniami do autoryzacji");
        $sheet->setCellValue('B' . $rowIndex, $result1);
        $rowIndex++;

        $sheet->setCellValue('A' . $rowIndex, "Ilość czytelników");
        $sheet->setCellValue('B' . $rowIndex, $result2);
        $rowIndex++;

        $sheet->setCellValue('A' . $rowIndex, "Ilość wypożyczonych książek");
        $sheet->setCellValue('B' . $rowIndex, $result3);
        $rowIndex++;

        $sheet->setCellValue('A' . $rowIndex, "Ilość dostępnych książek");
        $sheet->setCellValue('B' . $rowIndex, $result4);
        $rowIndex++;

        $sheet->setCellValue('A' . $rowIndex, "Ilość zarejestrowanych książek");
        $sheet->setCellValue('B' . $rowIndex, $result5);
        $rowIndex++;

        $sheet->getStyle('A1:B1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:B' . ($rowIndex - 1))->applyFromArray($cellStyle);

        $sheet->setCellValue('A' . $rowIndex, '© 2023 WIADERNA.EDU.PL | Powered by BARTIXEN.PL - Bartosz Krasoń');
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($headerStyle);
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($size);
        $sheet->mergeCells('A' . $rowIndex . ':B' . $rowIndex);
        foreach (range('A', 'B') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'statistic.xlsx';
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
