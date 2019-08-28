<?php

namespace App\Entity;

use DateTime;
use DateTimeZone;

class DbMessage implements MessageInterface
{
    private $row;

    public function __construct(array $row)
    {
        $this->row = $row;
    }

    public function serialize(): MessageDTO
    {
        $message = new MessageDTO();
        $message
            ->setFirstName($this->row['first_name'])
            ->setLastName($this->row['last_name'])
            ->setAge($this->calculateAge())
            ->setEmail($this->row['email'])
            ->setContent($this->row['content'])
            ->setCreatedAt($this->getCreatedAt());
        
        return $message;
    }

    private function calculateAge(): int
    {
        $dateOfBirth = DateTime::createFromFormat(
            'Y-m-d',
            $this->row['date_of_birth']
        );
        $timeZone = new DateTimeZone('Europe/Vilnius');
        $now = new DateTime('now', $timeZone);
        $years = $dateOfBirth->diff($now)->format('%y');

        return (int) $years;
    }

    private function getCreatedAt(): DateTime
    {
        return DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $this->row['created_at']
        );
    }
}
