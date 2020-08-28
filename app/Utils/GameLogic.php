<?php
declare(strict_types=1);

namespace DiscWolf\Utils;


use DiscWolf\Models\Game;
use DiscWolf\Models\Hole;
use DiscWolf\Models\Player;


final class GameLogic
{
    // TODO:
    // In HoleController save file in index? Bobby....I found where to put it.
    // Adjust load game to show hole by hole score and round breakdown?
    // function assigns skins to hole winner, or carries skins forward.
    // function that summs scores and calculates Nassau.


    /**
     * Retuns the the sum of a list of integers, as an integer.
     * Method is public for purpose of initial testing.
     *
     * @return int
     */
    public function sumListIntegers($int_list): int
    {
        return array_sum($int_list);
    }

    /**
     * Retuns the the sum of a list of floats, as a float.
     * Method is public for purpose of initial testing.
     *
     * @return float
     */
    public function sumListFloats($float_list): float
    {
        return array_sum($float_list);
    }

    /**
     * Retuns array of [playerOrder] => score of lowest scoring player(s).
     * Method is public for purpose of initial testing
     *
     * @return array
     */
    public function lowestHoleScore($holes): array
    {
        foreach ($holes[0]->scores as $value) {
            $ps[$value->playerOrder] = $value->score;
        }

        $minimum = min($ps);
        $lowestScore = array_filter($ps, function($elem) use($minimum){
            return $elem == $minimum;
        });

        return $lowestScore;
    }

    /**
     * Return player's position
     *
     *@return array
     */
    public function getPlayerPositions($holes): array
    {
        foreach ($holes[0]->scores as $value) {
            $pp[$value->playerOrder] = $value->position;
        }
        return $pp;
    }

    /**
     * Return string of win or push based on which team had lowest score.
     * Method is public for purpose of initial testing.
     *
     *@return string
     */
    public function isSkinWin($winning_scores): string
    {
        if (!array_diff(array(0=>"W", 1=>"Y"), $winning_scores)) {
            return "push";
        } elseif (!array_diff(array(0=>"P", 1=>"Y"), $winning_scores)) {
            return "push";
        } else {
            return "win";
        }
    }

    /**
     * Return skin multiplier. If Wolf doesn't pick a partner the skins
     * are doubled.
     * Method is public for purpose of initial testing.
     *
     *@return int
     */
    public function getSkinValue($player_positions): int
    {
        if (array_diff(array(0=>"P"), $player_positions)) {
            return 2;
        }
        return 1;
    }

    /**
     * Return array of team that wins skins.
     *
     *
     *@return array
     */
    public function getWinTeam($all_players, $winning_score): array
    {
        // Make both teams
        foreach ($all_players as $position => $team) {
            if ($team == "W") {
                $wolf_team[$position] = $team;
            } elseif ($team == "P") {
                $wolf_team[$position] = $team;
            } elseif ($team == "Y") {
                $other_team[$position] = $team;
            }
        }

        // return winning team.
        if (in_array("W", $winning_score)) {
            return $wolf_team;
        } elseif (in_array("P", $winning_score)) {
            return $wolf_team;
        } elseif (in_array("Y", $winning_score)) {
            return $other_team;
        }
    }

    /**
     * Return all keys in array with single value.
     *
     *
     *@return array
     */
    private function updateArrayValue($base_array, $new_value): array
    {
        $new_array = array();
        foreach ($base_array as $key => $value) {
            $new_array[$key] = $new_value;
        }
        return $new_array;
    }

    /**
     * Update hole_scores for each player and each wolf partner.
     *
     *
     *@return object
     */
    private function updateScores($game, $holeNumber): object
    {
        foreach ($game->players as $key => $value) {
            $p_num = $value->order;
            $hole = $game->holes[$holeNumber-1];
            $p_score = $hole->scores[$p_num-1]->score;
            array_push($value->hole_scores, $p_score);

            // likely better to move to it's own function? BOBBY
            $p_partner = $hole->scores[$p_num-1]->position;
            array_push($value->wolf_team, $p_partner);
        }
        return $game;
    }

    /**
     * Calculate total score for each player.
     *
     *@return object
     */
    private function calcTotal($game): Object
    {
        foreach ($game->players as $key => $value) {
            $p_score = $hole->scores[$p_num-1]->score;
            $tot = $this->sumListIntegers($value->hole_scores);
            $value->total_score = $tot;
        }
        return $game;
    }

    /**
     * Assign skins to winner and recalculate total.
     *
     *@return object
     */
    private function assignSkins($game, $skins_won): Object
    {
        foreach ($skins_won as $key => $value) {
            $game->players[$key-1]->total_skins += $value;
        }
        return $game;
    }

    /**
     * Return array of skins to add to each players tally.
     * 1. Find lowest score.
     * 2. Calculate winning players skins or pass empty array to push skins
     * to next hole..
     * Method is public for purpose of initial testing.
     *
     *@return array
     */
    public function calculateSkins($holes): array
    {
        // Find lowest score array
        $low_scores = $this->lowestHoleScore($holes);

        // Array of player positions
        $p_pos = $this->getPlayerPositions($holes);

        // Array of positions to compare lowest scores.
        $p_low_score = array_intersect_key($p_pos, $low_scores);

        // Check if the Wolf or Other team both had lowest score.
        $win_or_push = $this->isSkinWin($p_low_score);

        // If Wolf is all alone double the skins.
        $skin_value = $holes[0]->skins_to_win * $this->getSkinValue($p_pos);

        // Make zeroed array to fill in skins.
        $zero_skins = $this->updateArrayValue($p_pos, 0);

        if ($win_or_push == "win") {
            $win_team = $this->getWinTeam($p_pos, $p_low_score);
            $skin_win = $this->updateArrayValue($win_team, $skin_value);
            // print_r(array_replace($zero_skins, $skin_win));
            return array_replace($zero_skins, $skin_win);
        }
        return array();
    }

    /**
     * Return updated game object.
     * 1. Update hole scores.
     * 2. Update skins earned.
     * 3. Update overall score.
     *
     * Method is public for purpose of initial testing.
     *
     *@return object
     */
     public function updatePlayers($args, $game): Object
         {
        // if skins_won is empty only update skins_win
        $skins_won = $this->calculateSkins($game->holes);

        $holeNumber = (int) $args['holeNumber'];

        // add score to hole_scores
        $game = $this->updateScores($game, $holeNumber);

        // calculate player total score.
        $game = $this->calcTotal($game);

        // add skins if won.
        if(!empty($skins_won)) {
            $game = $this->assignSkins($game, $skins_won);
        }

        // hold on to skins if pushed.
        if(empty($skins_won)) {
            $game->holes[$holeNumber-1]->skins_to_win += 1;
        }

        return $game;
    }
}
