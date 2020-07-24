<?php


namespace DiscWolf\Utils;


final class WhoIsTheWolf
{

    /**
     * Takes in number of holes and number of players, and returns a 2-D matrix of holes chunked by players.
     *
     * @param int $numberOfHoles
     * @param int $numberOfPlayers
     * @return array
     */
    public function playerHoleMatrix(int $numberOfHoles = 9, int $numberOfPlayers = 4): array
    {
        $holes = [];
        for ($i=0; $i < $numberOfHoles; ++$i) {
            $holes[] = $i + 1;
        }

        return array_chunk($holes, $numberOfPlayers);
    }

    /**
     * Returns the player number of the wolf for the requested hole, based on a 2-D holes by player matrix.
     *
     * @param int $numberOfHoles
     * @param int $numberOfPlayers
     * @param int $currentHole
     * @return int
     */
    public function playerNumber(int $numberOfHoles, int $numberOfPlayers, int $currentHole): int
    {
        $playerHoleMatrix = $this->playerHoleMatrix($numberOfHoles, $numberOfPlayers);

        $wolf = 0;
        foreach ($playerHoleMatrix as $row) {
            //array_search here is looking at the single row in the matrix being presented during a loop, and looking
            // for the needle. If it finds it, it returns the index of the match, else it returns false.
            $tokenInArray = array_search($currentHole, $row, true);
            if ($tokenInArray !== false) {
                $wolf = $tokenInArray + 1;
            }
        }

        return $wolf;
    }
}