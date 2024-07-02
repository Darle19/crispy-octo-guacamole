<?php

namespace App\Services;

use App\Repositories\FeedRepository;
use App\Services\FileReaderFactory;
use Monolog\Logger;

class FeedProcessor
{
    private $repository;
    private $logger;
    private $fileReaderFactory;

    public function __construct(FeedRepository $repository, Logger $logger, FileReaderFactory $fileReaderFactory)
    {
        $this->repository = $repository;
        $this->logger = $logger;
        $this->fileReaderFactory = $fileReaderFactory;
    }

    public function processFile($filePath, $fileType)
    {
        try {
            $fileReader = $this->fileReaderFactory->createFileReader($fileType);
            $items = $fileReader->readFile($filePath);

            foreach ($items as $item) {
                $this->repository->insertItem($item);
            }

        } catch (\Exception $e) {
            $this->logger->error('Error processing file: ' . $e->getMessage());
            echo 'Error processing file: ' . $e->getMessage() . PHP_EOL;
        }
    }
}
