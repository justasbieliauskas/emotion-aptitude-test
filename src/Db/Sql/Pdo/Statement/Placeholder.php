<?php

namespace App\Db\Statement;

use PDO;

class Placeholder
{
    private $index;

    private $value;

    public function __construct(int $index, ?string $value)
    {
        $this->index = $index;
        $this->value = $value;
    }

    public function bind(PDOStatement $statement): void
    {
        $type = PDO::PARAM_STR;
        if($this->value === null) {
            $type = PDO::PARAM_INT;
        }

        $statement->bindValue($this->index, $this->value, $type);
    }
}
