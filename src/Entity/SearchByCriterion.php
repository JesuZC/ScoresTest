<?php
// src/Entity/Task.php
namespace App\Entity;

class SearchByCriterion
{
    protected $region;
    protected $gender;
    protected $hasLegalAge;
    protected $hasPositiveScore;

    public function getRegion(): string
    {
        return $this->region;
    }

    public function setRegion(string $region): void
    {
        $this->Region = $region;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): void
    {
        $this->gender = $gender;
    }

    public function getHasLegalAge(): bool
    {
        return $this->hasLegalAge;
    }

    public function setHasLegalAge(string $hasLegalAge): void
    {
        $this->hasLegalAge = $hasLegalAge;
    }
    public function getHasPositiveScore(): string
    {
        return $this->hasPositiveScore;
    }

    public function setHasPositiveScorer(string $hasPositiveScore): void
    {
        $this->hasPositiveScore = $hasPositiveScore;
    }
}