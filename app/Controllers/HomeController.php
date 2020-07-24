<?php
declare(strict_types=1);


namespace DiscWolf\Controllers;


use DiscWolf\Models\Game;
use DiscWolf\Models\Player;
use DiscWolf\Utils\GameState;

class HomeController extends Controller
{
    /**
     * HOME LANDING PAGE
     *
     * This method renders the landing home page; the very first the users see.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): void
    {
        $template = $this->twig->load('/Home.twig');

        echo $template->render();
    }

    /**
     * Takes in the form data from the player registry and returns a Player model object.
     *
     * @param int $id
     * @param string $name
     * @param float $nassau
     * @return Player
     */
    private function makePlayer(int $id, string $name, float $nassau): Player
    {
        return new Player([
          'order' => $id,
          'name' => $name,
          'nassau' => $nassau,
        ]);
    }

    /**
     * Renders the form for registering the players.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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

    /**
     * Renders the game setup verification page.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
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