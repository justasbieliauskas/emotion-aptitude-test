<?php

namespace App\Page;

use PDO;

class TotalPages
{
    private $db;

    private $perPage;

    public function __construct(PDO $db, int $perPage)
    {
        $this->db = $db;
        $this->perPage = $perPage;
    }

    public function toInt(): int
    {
        $count = $this->db
            ->query('SELECT COUNT(*) FROM messages')
            ->fetchColumn();

        return ceil($count / $this->perPage);
    }
}
