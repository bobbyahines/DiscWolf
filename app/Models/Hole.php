<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Uuid;

final class Hole
{
    public string $uuid;
    public int $number;
    public int $wolfPlayerNumber;
    public ?int $wolfPackNumber; //null = Lone Wolf
    public array $score;

    public function __construct($holeData = [])
    {
        $this->uuid = $holeData['uuid'] ?: Uuid::uuid4()->toString();
        $this->number = $holeData['number'] ?: 0;
        $this->wolfPlayerNumber = $holeData['wolfPlayerNumber'] ?: 0;
        $this->wolfPackNumber = $holeData['wolfPackNumber'] ?: null;
        $this->score = $holeData['score'] ?: [];
    }
}