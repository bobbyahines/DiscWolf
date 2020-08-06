<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use DiscWolf\Models\Game;
use DiscWolf\Models\Hole;
use DiscWolf\Models\Score;
use DiscWolf\Utils\GameState;
use DiscWolf\Utils\WhoIsTheWolf;

final class HoleController extends Controller
{

    /**
     * Loads the current session game file.
     *
     * @return Game
     * @throws \JsonException
     */
    private function loadGameFile(): Game
    {
        $gameFileId = collect($_SESSION['gameFileId'])->first();

        $gameState = new GameState();
        $loadGame = $gameState->loadGame($gameFileId);
        unset($gameState);

        return $loadGame;
    }

    /**
     * Saves/Overwrites the session's Game File with any provided Game object.
     *
     * @param Game $game
     * @return bool
     */
    private function saveGameFile(Game $game): ?int
    {
        $gameState = new GameState();
        $saved = $gameState->saveGame($game);
        unset($gameState);

        return $saved;
    }

    /**
     * Send it the form params & player count, and it returns a Hole object
     *
     * @param array $args
     * @param int $playerCount
     * @return Hole
     */
    private function newHoleMaker(array $args, int $playerCount = 4): Hole
    {
        $holeNumber = (int) $args['holeNumber'];

        $newHole = new Hole([
            'number' => $holeNumber,
        ]);

        $scores = [];
        for ($i = 1; $i <= $playerCount; ++$i) {
            $playerOrder = $args['holeNumber'] ? (int)$args['player' . $i . 'order'] : $i;
            $position = $args['holeNumber'] ? $args['position' . $i] : '';
            $score = $args['holeNumber'] ? (int)$args['score' . $i] : 0;

            $score = new Score([
                'playerOrder' => $playerOrder,
                'position' => $position,
                'score' => $score,
            ]);

            $scores[] = $score;
            unset($score);
        }

        $newHole->scores = $scores;

        return $newHole;
    }

    /**
     * @param Hole $newHole
     * @param array $holes
     * @return array
     */
    private function integrateNewHole(Hole $newHole, array $holes): array
    {
        $holes[] = $newHole;

        return $holes;

    }

    /**
     * Returns the hole number by parsing the last digit from the URI.
     *
     * @return int
     */
    private function getHoleNumberFromUrl(): int
    {
        $uriExploded = explode('/', $_SERVER['REQUEST_URI']);

        return (int)end($uriExploded);
    }

    /**
     * Controls all hole url pages with a single method rendering a single template.     *
     *
     * @throws \JsonException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): void
    {
        //Load the Game File
        $game = $this->loadGameFile();

        //Create a new Hole out of the existing args.
        $args = func_get_arg(1);
        if (count($args) > 1) {
            $newHole = $this->newHoleMaker($args, $game->playerCount);
        }

        if ($newHole) {
            $newHoles = $this->integrateNewHole($newHole, $game->holes);
        }

        if ($newHoles) {
            $game->holes = $newHoles;
            $this->saveGameFile($game);
        }

        //get the Wolf for this hole (only really needed for unsaved holes, I suppose)
        $holeNumber = $this->getHoleNumberFromUrl();
        $witw = new WhoIsTheWolf();
        $wolfPlayerNumber = $witw->playerNumber($game->holeCount, $game->playerCount, $holeNumber);
        unset($witw);

        $params = ['data' => [
            'gameFile' => $game,
            'currentHole' => $holeNumber,
            'wolfPlayer' => $wolfPlayerNumber,
        ]];

        $template = $this->twig->load('/Hole.twig');
        echo $template->render($params);
    }
}