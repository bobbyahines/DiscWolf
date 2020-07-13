<?php
declare(strict_types=1);

namespace DiscWolf\Models;


final class GameStartDTO
{
    private ?int $playerCount;
    private ?float $skinsPerHole;
    private ?bool $agreedToTerms;

    public function __construct(array $params = [])
    {
        $this->playerCount = $params['playerCount'] ?: null;
        $this->skinsPerHole = $params['playerSkins'] ?: null;
        $this->agreedToTerms = $params['playerTerms'] ?: null;
    }
}