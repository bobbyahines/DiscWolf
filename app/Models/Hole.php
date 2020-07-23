<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class Hole
{
    public string $uuid;
    public int $number;
    public string $position; //Wolf, Lone, Pack, preY
    public int $score;

    public function __construct($holeData = [])
    {
        $this->uuid = $holeData['uuid'] ?: Uuid::uuid4()->toString();
        $this->number = $holeData['number'] ?: 0;
        $this->position = $holeData['position'] ?: '';
        $this->score = $holeData['score'] ?: 0;
    }
}