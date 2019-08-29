<?php

namespace App\Db\Statement;

use App\Sql\ClauseInterface;
use App\Db\Connection\ConnectionInterface;

class PreparedStatement implements StatementInterface
{
    private $connection;

    private $sql;

    private $placeholders;

    public function __construct(
        ConnectionInterface $connection,
        ClauseInterface $sql,
        iterable $placeholders
    ) {
        $this->connection = $connection;
        $this->sql = $sql;
        $this->placeholders = $placeholders;
    }

    public function execute(): array
    {
        $pdo = $this->connection->make();
        $sql = $this->sql->asString();

        $statement = $pdo->prepare($sql);
        foreach($this->placeholders as $placeholder) {
            $placeholder->bind($statement);
        }

        return $statement->execute();
    }
}
