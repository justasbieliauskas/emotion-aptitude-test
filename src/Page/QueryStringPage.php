<?php

namespace App\Page;

/**
 * Represents page value from "?page=12"
 */
class QueryStringPage implements PageInterface
{
    private $get;

    private $key;

    private $default;

    public function __construct(array $get, string $key, int $default)
    {
        $this->get = $get;
        $this->key = $key;
        $this->default = $default;
    }

    public function toInt(): int
    {
        $page = $this->default;
        if(isset($this->get[$this->key])) {
            $value = $this->get[$this->key];
            if(ctype_digit($value)) {
                $page = $value;
            }
        }

        return $value;
    }
}
