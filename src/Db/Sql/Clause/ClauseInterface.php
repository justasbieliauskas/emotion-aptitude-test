<?php

namespace App\Db\Sql\Clause;

interface ClauseInterface
{
    public function asString(): string;
}
