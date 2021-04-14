<?php


namespace DiscWolf\Models;


final class Game
{

    public ?string $courseName;
    public ?int $holesCount;
    public ?int $playersCount;
    public ?float $skinsAmount;
    public ?int $currentHole;
    public ?float $currentPot;
    public ?array $players;

    public function __construct(array $gameData = [])
    {
        $this->courseName = $gameData['courseName'] ?: null;
        $this->holesCount = $gameData['holesCount'] ?: null;
        $this->playersCount = $gameData['playersCount'] ?: null;
        $this->skinsAmount = $gameData['skinsAmount'] ?: null;
        $this->currentHole = $gameData['currentHole'] ?: null;
        $this->currentPot = $gameData['currentPot'] ?: null;
        $this->players = $gameData['players'] ?: [];
    }
}