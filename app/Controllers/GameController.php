<?php declare(strict_types=1);


namespace DiscWolf\Controllers;


use DiscWolf\Traits\ScoringUtilitiesTrait;

class GameController extends Controller
{
    use ScoringUtilitiesTrait;

    private function holeNumberFromUrl(): int
    {
        $uriExploded = explode('/', $_SERVER['REQUEST_URI']);

        return (int)end($uriExploded);
    }

    public function index()
    {
        //Fetch the hole number from the holeNumberFromUrl() private method.
        $currentHoleNumber = $this->holeNumberFromUrl();

        //Make a fresh Game object from the current Session game object.
        $game = $_SESSION['game'];
        //set session's currentHole to $holeNumber variable
        $game->currentHole = $currentHoleNumber;

        // If the hole number is greater than one...
        if ($game->currentHole > 1) {

            //Score the previous hole so it is rendered on the screen
            $playersWithScores = ScoringUtilitiesTrait::processPreviousHoleScores($_POST, $game->players);
            //Replace existing players array, with new players array.
            $game->players = $playersWithScores;
        }

        //Replace the current session game object with the updated one.
        $_SESSION['game'] = $game;
        unset($game);

        $playerTotals = ScoringUtilitiesTrait::playerTotals($_SESSION['game']->players);

        //Load the Hole template
        $template = $this->twig->load('/Hole.twig');

        //Render the template, passing it the session's game object as an array.
        echo $template->render([
          'data' => $_SESSION['game'],
          'playerTotals' => $playerTotals
        ]);
    }
}