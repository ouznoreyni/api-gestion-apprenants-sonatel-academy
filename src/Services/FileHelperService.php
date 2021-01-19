<?php
namespace App\Services;

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
}
