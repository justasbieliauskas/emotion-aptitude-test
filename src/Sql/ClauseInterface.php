<?php

namespace App\Sql;

interface ClauseInterface
{
    public function asString(): string;
}
