<?php

namespace App\Db\Sql\Pdo\Statement;

use PDO;
use App\Sql\ClauseInterface;

class QueryStatement implements StatementInterface
{
    private $sql;

    private $pdo;

    public function __construct(ClauseInterface $sql, PDO $pdo)
    {
        $this->sql = $sql;
        $this->pdo = $pdo;
    }

    public function execute(): array
    {
        return $pdo->query($this->sql->asString());
    }
}
