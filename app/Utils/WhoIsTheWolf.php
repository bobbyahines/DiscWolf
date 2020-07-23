<?php


namespace DiscWolf\Utils;


final class WhoIsTheWolf
{

    public function playerHoleMatrix(int $numberOfHoles = 9, int $numberOfPlayers = 4): array
    {
        $holes = [];
        for ($i=0; $i < $numberOfHoles; ++$i) {
            $holes[] = $i + 1;
        }

        return array_chunk($holes, $numberOfPlayers);
    }

    public function playerNumber(int $numberOfHoles, int $numberOfPlayers, int $currentHole): int
    {
        $playerHoleMatrix = $this->playerHoleMatrix($numberOfHoles, $numberOfPlayers);

        $wolf = 0;
        foreach ($playerHoleMatrix as $row) {
            $tokenInArray = array_search($currentHole, $row, true);
            if ($tokenInArray !== false) {
                $wolf = $tokenInArray + 1;
            }
        }

        return $wolf;
    }


}