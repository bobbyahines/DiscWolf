<?php
declare(strict_types=1);


namespace DiscWolf\Models;


use Ramsey\Uuid\Uuid;


final class Score
{
    public string $uuid;
    public int $playerOrder;
    public string $position;
    public int $score;

    public function __construct($playerData = [])
    {
        $this->uuid = $playerData['uuid'] ?: Uuid::uuid4()->toString();
        $this->playerOrder = $playerData['playerOrder'] ?: 0;
        $this->position = $playerData['playerPosition'] ?: 'Y';
        $this->score = $playerData['score'] ?: 0;
    }
}