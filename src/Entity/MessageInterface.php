<?php

namespace App\Entity;

interface MessageInterface
{
    public function serialize(): MessageDTO;
}
