<?php declare(strict_types=1);


namespace DiscWolf\Controllers;


use DiscWolf\Traits\ScoringUtilitiesTrait;

class FinalScoreController extends Controller
{
    use ScoringUtilitiesTrait;

    public function index()
    {
        //Make a fresh Game object from the current Session game object.
        $game = $_SESSION['game'];

        //First, score the previous hole so it is recorded and rendered on the screen
        $playersWithScores = ScoringUtilitiesTrait::processPreviousHoleScores($_POST, $game->players);
        //Replace existing players array, with new players array.
        $game->players = $playersWithScores;

        /**
         * NEW SCORING LOGIC GOES HERE
         */

            //Code...

        /**
         * END LOGIC
         */

        //Replace the session's game object with the updated one.
        $_SESSION['game'] = $game;

        //Load the Hole template
        $template = $this->twig->load('/Scores.twig');

        //Render the template, passing it the session's game object as an array.
        echo $template->render([
          'data' => $_SESSION['game'],
        ]);
    }
}