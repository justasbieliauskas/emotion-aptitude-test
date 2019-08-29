<?php

namespace App\Sql;

class OrderClause implements ClauseInterface
{
    private $clause;

    private $by;

    private $order;

    public function __construct(
        ClauseInterface $clause,
        string $by,
        string $order = 'ASC'
    ) {
        $this->clause = $clause;
        $this->by = $by;
        $this->order = $order;
    }

    public function asString(): string
    {
        $clause = $this->clause->asString();
        
        return "$clause ORDER BY $this->by $this->order";
    }
}
