<?php

namespace App\Page;

use App\Entity\CountInterface;

class PageCountFromTotal implements PageCountInterface
{
    private $total;

    private $perPage;

    public function __construct(CountInterface $total, PerPageInterface $perPage) {
        $this->total = $total;
        $this->perPage = $perPage;
    }

    public function toInt(): int
    {
        $total = $this->total->toInt();
        $perPage = $this->perPage->toInt();

        return ceil($total / $perPage);
    }
}
