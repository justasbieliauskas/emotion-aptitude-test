<?php

namespace App\Db\Sql\Clause;

class SelectClause implements ClauseInterface
{
    private $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    public function asString(): string
    {
        return "SELECT * FROM $this->table";
    }
}
