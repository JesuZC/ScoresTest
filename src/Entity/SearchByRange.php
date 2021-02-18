<?php
// src/Entity/Task.php
namespace App\Entity;

class SearchByRange
{
    protected $startRange;
    protected $endRange;

    public function getStartRange(): int
    {
        return $this->startRange;
    }

    public function setStartRange(string $start): void
    {
        $this->startRange = $start;
    }

    public function getEndRange(): int
    {
        return $this->endRange;
    }

    public function setEndRange(string $end): void
    {
        $this->endRange = $end;
    }
}