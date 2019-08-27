<?php

namespace App\Page;

class BoundedPage implements PageInterface
{
    private $page;

    private $upper;

    private $lower;

    public function __construct(
        PageInterface $page,
        ?int $upper,
        ?int $lower
    ) {
        $this->page = $page;
        $this->upper = $upper;
        $this->lower = $lower;
    }

    public function toInt(): int
    {
        $value = $this->page->toInt();
        if($this->upper !== null && $value > $this->upper) {
            $value = $this->upper;
        }
        if($this->lower !== null && $value < $this->lower) {
            $value = $this->lower;
        }

        return $value;
    }
}
