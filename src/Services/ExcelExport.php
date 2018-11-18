<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelExport
{
    /** @var EntityManagerInterface  */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function export(array $codes)
    {
        $spreadsheet = new Spreadsheet();

        $counter = 0;
        foreach ($codes as $code) {
            $counter++;
            $spreadsheet->getActiveSheet()->setCellValue('A'.$counter, $code);
        }

        $writer = new Xlsx($spreadsheet);
        $date = date('Y-m-d_H-i-s', time());
        $writer->save(sprintf('%s.xls', $date));
    }
}