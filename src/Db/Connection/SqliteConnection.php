<?php

namespace App\Db\Connection;

use PDO;

class SqliteConnection implements ConnectionInterface
{
    private $path;

    public function __construct(string $path)
    {
        $this->path = $path;
    }

    public function make(): PDO
    {
        return new PDO("sqlite:$this->path");
    }
}
