<?php

namespace App\Entity;

use PDO;
use Iterator;
use PDOStatement;
use App\Entity\DbMessage;

class DbMessageIterator implements Iterator
{
    private $statement;

    private $row;

    private $index;

    public function __construct(PDOStatement $statement)
    {
        $this->statement = $statement;
        $this->index = -1;
    }

    public function rewind(): void
    {
        $this->next();
    }

    public function valid(): bool
    {
        return $this->row !== false;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function current(): DbMessage
    {
        return new DbMessage($this->row);
    }

    public function next(): void
    {
        $this->row = $this->statement->fetch(PDO::FETCH_ASSOC);
        $this->index++;
    }
}
