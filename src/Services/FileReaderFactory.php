<?php

namespace App\Services;

class FileReaderFactory
{
    public function createFileReader($fileType)
    {
        switch ($fileType) {
            case 'xml':
                return new XMLFileReader();
            // Add cases for other file types (e.g., JSONFileReader, CSVFileReader)
            default:
                throw new \Exception('Unsupported file type');
        }
    }
}
