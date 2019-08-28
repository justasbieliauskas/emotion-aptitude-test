<?php

namespace App\Entity;

use PDO;

class PDOMessageCount implements CountInterface
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function toInt(): int
    {
        return (int) $this->db
            ->query('SELECT COUNT(*) FROM messages')
            ->fetchColumn();
    }
}
