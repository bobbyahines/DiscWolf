<?php
declare(strict_types=1);

namespace DiscWolf\Models;


final class GameStartDTO
{
    // Single value match variables.
    private ?int $playerCount;
    private ?float $skinsValue;
    private ?string $golfCourse;
    private ?bool $agreedToTerms;

    // Multi-value and adjustable match variables.
    private ?int $currentHole:
    private ?int $matchHoles;
    private ?int $availableSkins;

    // Player values
    private ?string $playerName;
    private ?int $playerWolfOrder;
    private ?bool $playerNassau;
    private ?int $playerHoleScore;
    private ?int $matchScore;
    private ?string $holePartner;
    private ?int $playerSkins;
    private ?int $playerTotalScore;

    public function __construct(array $params = [])
    {
        // Single value match variables.
        $this->playerCount = $params['playerCount'] ?: null;
        $this->golfCourse = $params['golfCourse'] ?: null;
        $this->skinsValue = $params['skinsValue'] ?: null;
        $this->agreedToTerms = $params['playerTerms'] ?: null;

        // Multi-value and adjustable match variables.
        $this->$currentHole = $params['currentHole'] ?: null;
        $this->$matchHoles = $params['matchHoles'] ?: null;
        $this->availableSkins = $params['availableSkins'] ?: null; //counter for available skins, holds carry over.

        // Player values
        $this->playerName = $params['playerName'] ?: null;
        $this->playerWolfOrder = $params['playerWolfOrder'] ?: null;
        $this->playerNassau = $params['playerNaussau'] ?: null;
        $this->playerHoleScore = $params['playerHoleScore'] ?: null;
        $this->playerSkins = $params['playerSkins'] ?: null;
        $this->playerTotalScore = $params['playerTotalScore'] ?: null;
        $this->wolfPartner = $params['wolfPartner'] ?: null; //only wolf team will have partners, all others will be 0.
    }
}
