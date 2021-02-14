<?php
namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;

class FileHelperService
{
    public function uploadFile($file)
    {
        if ($file) {
            return fopen($file->getRealPath(), "rb");
        } else {
            return null;
        }
    }

    public static function uploadExcel($file)
    {
        if ($file) {
            $doc = IOFactory::identify($file);
            $docReader = IOFactory::createReader($doc);
            $spreadSheet = $docReader->load($file);
            return $spreadSheet->getActiveSheet()->toArray();

        } else {
            return null;
        }
    }
}
