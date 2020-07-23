<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use DiscWolf\Models\Game;
use DiscWolf\Models\Player;
use DiscWolf\Utils\GameState;

class HomeController extends Controller
{
    public function index(): void
    {
        $template = $this->twig->load('/Home.twig');

        echo $template->render();
    }

    private function makePlayer(int $id, string $name, float $nassau): Player
    {
        return new Player([
          'order' => $id,
          'name' => $name,
          'nassau' => $nassau,
        ]);
    }

    public function registerPlayers(): void
    {
        $args = func_get_arg(1);

        $params = [
          'data' => [
            'playerCount' => $args['playerCount'] * 1,
            'playerSkins' => $args['playerSkins'] * 1,
            'courseName' => $args['courseName'],
            'holeCount' => $args['holeCount'],
            'playerTerms' => $args['playerTerms'],
          ],
        ];

        $template = $this->twig->load('/RegisterPlayers.twig');

        echo $template->render($params);
    }

    public function startGame(): void
    {
        $args = func_get_arg(1);

        $game = new Game([
          'playerCount' => $args['playerCount'] * 1,
          'playerSkins' => $args['playerSkins'] * 1,
          'courseName' => $args['courseName'],
          'holeCount' => $args['holeCount'] * 1,
          'playerTerms' => $args['playerTerms'],
        ]);

        $players = [];
        for ($i=1; $i <= ($args['playerCount']); ++$i) {
            $players[] = $this->makePlayer($i, $args['player' . $i], ($args['player' . $i . 'nassau'] * 1));
        }

        $game->players = $players;

        $gameState = new GameState();
        $saveGameState = $gameState->saveGame($game);
        unset($gameState);

        $_SESSION['gameFileId'] = $game->uuid;

        $params = [
          'data' => [
            'gameFile' => $game,
          ],
        ];

        $template = $this->twig->load('/StartGame.twig');

        echo $template->render($params);
    }
}