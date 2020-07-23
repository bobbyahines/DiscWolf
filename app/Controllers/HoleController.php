<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use DiscWolf\Models\Hole;
use DiscWolf\Models\Player;
use DiscWolf\Utils\GameState;
use DiscWolf\Utils\WhoIsTheWolf;

class HoleController extends Controller
{
    private function getHoleNumber(string $requestUri): string
    {
        $uriExploded = explode('/', $requestUri);

        return end($uriExploded);
    }

    private function setCurrentHoleInSession(int $holeNumber): void
    {
        $_SESSION['currentHole'] = $holeNumber;
    }

    public function index(): void
    {
        //load the game file
        $gameFileId = collect($_SESSION['gameFileId'])->first();
        $gameState = new GameState();
        $loadedGame = $gameState->loadGame($gameFileId);

        //get the holeNumber
        $holeNumber = (int) $this->getHoleNumber($_SERVER['REQUEST_URI']);
        //add it as a Session variable.
        $this->setCurrentHoleInSession($holeNumber);

        //get the Wolf for this hole
        $witw = new WhoIsTheWolf();
        $wolfPlayerNumber = $witw->playerNumber($loadedGame->holeCount, $loadedGame->playerCount, $holeNumber);
        unset($witw);

        $params = ['data' => [
          'gameFile' => $loadedGame,
          'currentHole' => $holeNumber,
          'wolfPlayer' => $wolfPlayerNumber
        ]];

        $template = $this->twig->load('/Hole.twig');
        echo $template->render($params);
    }
}