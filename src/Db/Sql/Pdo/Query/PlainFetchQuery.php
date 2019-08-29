<?php

namespace App\Db\Sql\Pdo\Query;

use PDO;
use App\Sql\ClauseInterface;
use App\Db\Sql\Pdo\Statement\QueryStatement;
use App\Db\Sql\Pdo\Statement\StatementInterface;

class PlainFetchQuery implements QueryInterface
{
    private $sql;

    public function __construct(ClauseInterface $sql)
    {
        $this->sql = $sql;
    }

    public function state(PDO $pdo): StatementInterface
    {
        return new QueryStatement($this->sql, $pdo);
    }
}
