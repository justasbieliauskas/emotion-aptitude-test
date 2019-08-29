<?php

namespace App\Db\Connection;

use PDO;

class CheckedConnection implements ConnectionInterface
{
    private $connection;

    public function __construct(ConnectionInterface $connection)
    {
        $this->connection = $connection;
    }

    public function make(): PDO
    {
        $pdo = $this->connection->make();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
}
