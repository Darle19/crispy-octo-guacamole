<?php

require 'vendor/autoload.php';

use App\Repositories\FeedRepository;
use App\Services\FeedProcessor;
use App\Services\FileReaderFactory;
use Doctrine\DBAL\DriverManager;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

// Configuration for the SQLite database
$dbParams = require 'src/Config/db.php';

// Path to the XML file
$xmlFile = __DIR__ . '/feed.xml';
$fileType = 'xml'; // Assuming XML, can be extended to support other types

// Path to the log file
$logFile = __DIR__ . '/logs/logfile.log';

// Set up Logger
$logger = new Logger('feed_processor');
$logger->pushHandler(new StreamHandler($logFile, Logger::ERROR));

// Set up Database Connection
$connection = DriverManager::getConnection($dbParams);

// Set up Repository
$repository = new FeedRepository($connection);

// Set up FileReaderFactory
$fileReaderFactory = new FileReaderFactory();

// Set up FeedProcessor
$processor = new FeedProcessor($repository, $logger, $fileReaderFactory);
$processor->processFile($xmlFile, $fileType);

echo "Processing completed." . PHP_EOL;
