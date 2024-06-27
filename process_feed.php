<?php
require 'vendor/autoload.php';

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class FeedProcessor
{
    private $connection;
    private $logger;

    public function __construct($dbParams, $logFile)
    {
        $this->connection = DriverManager::getConnection($dbParams);
        $this->logger = new Logger('feed_processor');
        $this->logger->pushHandler(new StreamHandler($logFile, Logger::ERROR));
    }

    public function processXML($xmlFile)
    {
        if (!file_exists($xmlFile)) {
            $this->logger->error('XML file not found: ' . $xmlFile);
            echo 'XML file not found: ' . $xmlFile . PHP_EOL;
            return;
        }

        try {
            $xmlContent = file_get_contents($xmlFile);
            if ($xmlContent === false) {
                throw new Exception('Failed to read XML file');
            }

            $xml = simplexml_load_string($xmlContent);
            if ($xml === false) {
                throw new Exception('Failed to parse XML file');
            }

            foreach ($xml->item as $item) {
                $this->insertItem($item);
            }
        } catch (Exception $e) {
            $this->logger->error('Error processing XML file: ' . $e->getMessage());
            echo 'Error processing XML file: ' . $e->getMessage() . PHP_EOL;
        }
    }

    private function insertItem($item)
    {
        try {
            $this->connection->insert('feed_items', [
                'entity_id' => (int)$item->entity_id,
                'category_name' => (string)$item->CategoryName,
                'sku' => (string)$item->sku,
                'name' => (string)$item->name,
                'shortdesc' => (string)$item->shortdesc,
                'price' => (float)$item->price,
                'link' => (string)$item->link,
                'image' => (string)$item->image,
                'brand' => (string)$item->Brand,
                'rating' => (int)$item->Rating,
                'caffeine_type' => (string)$item->CaffeineType,
                'count' => (int)$item->Count,
                'flavored' => (string)$item->Flavored,
                'seasonal' => (string)$item->Seasonal,
                'instock' => (string)$item->Instock,
                'facebook' => (int)$item->Facebook,
                'is_kcup' => (int)$item->IsKCup,
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error inserting item: ' . $e->getMessage());
            echo 'Error inserting item: ' . $e->getMessage() . PHP_EOL;
        }
    }
}

// Configuration for the SQLite database
$dbParams = [
    'url' => 'sqlite:///' . __DIR__ . '/database.sqlite',
];

// Path to the XML file
$xmlFile = __DIR__ . '/feed.xml';

// Path to the log file
$logFile = __DIR__ . '/logfile.log';

$processor = new FeedProcessor($dbParams, $logFile);
$processor->processXML($xmlFile);

echo "Processing completed." . PHP_EOL;
