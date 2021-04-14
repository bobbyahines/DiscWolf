<?php


namespace DiscWolf\Traits;


use DiscWolf\Classes\ScoreHole;

trait ScoringUtilitiesTrait
{
    public static function processRawHoleScores(array $postParams): array
    {

        $rawHoleScores = [];
        foreach ($_SESSION['game']->players as $player) {
            $rawHoleScores[] = [
              'playerId' => $player->id,
              'hole' => (int) $postParams['hole'],
              'position' => $postParams['position' . $player->id],
              'score' => $postParams['strokes' . $player->id] === '0' ? 0 : (int) $postParams['strokes' . $player->id],
              'pot' => (float) 0,
            ];
        }

        return $rawHoleScores;
    }

    public static function processPreviousHoleScores(array $postParams, array $gamePlayers): array
    {
        $playerScores = self::processRawHoleScores($postParams);
        $scoreHole = new ScoreHole($playerScores);
        $scores = $scoreHole->applyScore();
        unset($scoreHole);

        //Create a new players array
        $playersWithScores = [];
        foreach ($gamePlayers as $player) {
            $score = collect($scores)->where('playerId', $player->id)->first();
            $player->scores[] = $score;
            $playersWithScores[] = $player;
        }

        return $playersWithScores;
    }

    public static function playerTotals(array $gamePlayers): array
    {
        $playerTotals = [];
        foreach ($gamePlayers as $player) {
            $points = collect($player->scores)->pluck('score')->sum();
            $skins = collect($player->scores)->pluck('pot')->sum();
            $playerTotals[$player->id] = [
                'points' => $points,
                'skins' => $skins,
            ];
        }

        return $playerTotals;
    }
}