<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use DiscWolf\Utils\GameState;
use DiscWolf\Utils\WhoIsTheWolf;

class HoleController extends Controller
{
    /**
     * Retrieves the game file id variable from the Global Session array.
     *
     * @return string
     */
    private function getGameFileId(): string
    {
        return collect($_SESSION['gameFileId'])->first();
    }

    /**
     * Returns the hole number by parsing the last digit from the URI.
     *
     * @return int
     */
    private function getHoleNumber(): int
    {
        $uriExploded = explode('/', $_SERVER['REQUEST_URI']);

        return (int) end($uriExploded);
    }

    /**
     * Sets the current hole number (passed in) into a session variable.
     *
     * @param int $holeNumber
     */
    private function setCurrentHoleInSession(int $holeNumber): void
    {
        $_SESSION['currentHole'] = $holeNumber;
    }

    /**
     * Controls all hole url pages with a single method rendering a single template.
     *
     * @todo Save Hole Data on load.
     *
     * @throws \JsonException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): void
    {
        //get the game file id
        $gameFileId = $this->getGameFileId();

        //load the game file
        $gameState = new GameState();
        $loadedGame = $gameState->loadGame($gameFileId);

        //get the holeNumber
        $holeNumber = $this->getHoleNumber();
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