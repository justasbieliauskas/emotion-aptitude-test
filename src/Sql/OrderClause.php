<?php

namespace App\Sql;

class OrderClause implements ClauseInterface
{
    private $clause;

    private $by;

    public function __construct(ClauseInterface $clause, string $by)
    {
        $this->clause = $clause;
        $this->by = $by;
    }

    public function asString(): string
    {
        $clause = $this->clause->asString();
        
        return "$clause ORDER BY $this->by";
    }
}
