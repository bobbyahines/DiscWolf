<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class Game
{
    public string $uuid;
    public int $playerCount;
    public float $playerSkins;
    public string $courseName;
    public int $holeCount;
    public array $players;
    public array $holes;
    public int $startTimeStamp;
    public ?int $endTimeStamp;

    public function __construct($gameData = [])
    {
        $this->uuid = $gameData['uuid'] ?: Uuid::uuid4()->toString();
        $this->playerCount = $gameData['playerCount'] ?: 4;
        $this->playerSkins = $gameData['playerSkins'] ?: 0.25;
        $this->courseName = $gameData['courseName'] ?: '';
        $this->holeCount = $gameData['holeCount'] ?: 9;
        $this->players = $gameData['players'] ?: [];
        $this->holes = $gameData['holes'] ?: [];
        $this->startTimeStamp = $gameData['startTimeStamp'] ?: $this->getTimeStamp();
        $this->endTimeStamp = $gameData['endTimeStamp'] ?: 0;
    }

    private function getTimeStamp(): int
    {
        $date = new \DateTime();
        $timeStamp = $date->getTimestamp();
        unset($date);

        return $timeStamp;
    }

    public function endTimeStamp(): void
    {
        $this->endTimeStamp = $this->getTimeStamp();
    }
}