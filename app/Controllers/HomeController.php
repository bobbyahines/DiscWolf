<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use DiscWolf\Models\GameStartDTO;

class HomeController extends Controller
{
    public function index(): void
    {
        $template = $this->twig->load('/Home.twig');

        echo $template->render();
    }

    private function setSessionArgs(string $label, $arg): void
    {
        $_SESSION[$label] = $arg;
    }

    public function register(): void
    {
        $args = func_get_arg(1);
        $playerCount = $args['player_count'] * 1;
        $playerSkins = (float) $args['player_skins'] * 1.0;

        $this->setSessionArgs('playerCount', $playerCount);
        $this->setSessionArgs('playerSkins', $playerSkins);

        $params = [
          'data' => [
                'playerCount' => $playerCount,
                'playerSkins' => $playerSkins,
                'playerTerms' => $args['player_terms'] ?: "off",
            ],
        ];

        $template = $this->twig->load('/RegisterPlayers.twig');

        echo $template->render($params);
    }
}