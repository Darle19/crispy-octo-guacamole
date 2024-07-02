<?php

namespace App\Repositories;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

class FeedRepository
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function insertItem($item)
    {
        try {
            $this->connection->insert('feed_items', $item);
        } catch (Exception $e) {
            throw new \Exception('Error inserting item: ' . $e->getMessage());
        }
    }
}
