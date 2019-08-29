<?php

namespace App\Db\Connection;

use PDO;

interface ConnectionInterface
{
    public function make(): PDO;
}
