<?php

namespace App\Entity;

use PDO;
use IteratorAggregate;
use App\Db\Sql\Clause\ClauseInterface;

class DbMessages implements IteratorAggregate
{
    private $sql;

    private $pdo;

    public function __construct(ClauseInterface $sql, PDO $pdo)
    {
        $this->sql = $sql;
        $this->pdo = $pdo;
    }

    public function getIterator()
    {
        $sql = $this->sql->asString();
        $statement = $this->pdo->query($sql);

        return new DbMessageIterator($statement);
    }
}
