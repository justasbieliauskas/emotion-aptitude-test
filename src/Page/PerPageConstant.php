<?php

namespace App\Page;

class PerPageConstant implements PerPageInterface
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }
}
