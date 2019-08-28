<?php

namespace App\Page;

class PageUnderLimit implements PageInterface
{
    private $page;

    private $total;

    public function __construct(
        PageInterface $page,
        PageCountInterface $total
    ) {
        $this->page = $page;
        $this->total = $total;
    }

    public function toInt(): int
    {
        $value = $this->page->toInt();
        $limit = $this->total->toInt();
        if($value > $limit) {
            $value = $limit;
        }

        return $value;
    }
}
