<?php

namespace App\Db\Sql\Pdo\Query;

use PDO;
use App\Db\Sql\Pdo\Statement\StatementInterface;

interface QueryInterface
{
    public function state(PDO $pdo): StatementInterface;
}
