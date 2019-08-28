<?php

namespace App\Page;

class PositivePage implements PageInterface
{
    private $page;

    public function __construct(PageInterface $page)
    {
        $this->page = $page;
    }

    public function toInt(): int
    {
        $value = $this->page->toInt();
        if($value < 1) {
            $value = 1;
        }
        
        return $value;
    }
}
