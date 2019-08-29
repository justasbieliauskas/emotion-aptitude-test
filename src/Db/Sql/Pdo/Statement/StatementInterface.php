<?php

namespace App\Db\Sql\Pdo\Statement;

interface StatementInterface
{
    public function execute(): array;
}
