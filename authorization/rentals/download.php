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

        $sql = "SELECT rentals.id, CONCAT(reader.name, ' ', reader.last_name, ' [', reader.class, ']') AS reader_name, CONCAT(book.title, ' (', book.author, ') [', book.record_number, ']') AS book_details, rentals.date FROM rentals JOIN reader ON rentals.id_reader = reader.id JOIN book ON rentals.id_book = book.id ORDER BY rentals.id";
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
        $sheet->setCellValue('B1', 'Imię i Nazwisko');
        $sheet->setCellValue('C1', 'Książka');
        $sheet->setCellValue('D1', 'Data');

        $rowIndex = 2;

        while ($row = $result->fetch_assoc()) {
            $sheet->setCellValue('A' . $rowIndex, $row["id"]);
            $sheet->setCellValue('B' . $rowIndex, $row["reader_name"]);
            $sheet->setCellValue('C' . $rowIndex, $row["book_details"]);
            $sheet->setCellValue('D' . $rowIndex, $row["date"]);

            $rowIndex++;
        }

        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:D' . ($rowIndex - 1))->applyFromArray($cellStyle);

        $sheet->setCellValue('A' . $rowIndex, '© 2023 WIADERNA.EDU.PL | Powered by BARTIXEN.PL - Bartosz Krasoń');
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($headerStyle);
        $sheet->getStyle('A' . $rowIndex)->applyFromArray($size);
        $sheet->mergeCells('A' . $rowIndex . ':D' . $rowIndex);
        foreach (range('A', 'D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'rentals.xlsx';
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
