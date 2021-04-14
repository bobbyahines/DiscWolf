<?php declare(strict_types=1);


namespace DiscWolf\Controllers;


use DiscWolf\Models\Game;
use DiscWolf\Models\Player;

class SetupController extends Controller
{
    public function register(): void
    {
        $_SESSION['game'] = new Game([
          'courseName' => $_POST['courseName'],
          'holesCount' => $_POST['holesCount'],
          'playersCount' => $_POST['playersCount'],
          'skinsAmount' => $_POST['skinsAmount'],
          'currentHole' => 0,
          'currentPot' => $_POST['skinsAmount'] * $_POST['playersCount'],
          'players' => [],
        ]);

        $template = $this->twig->load('/Register.twig');

        echo $template->render(['data' => $_SESSION['game']]);
    }

    public function terms()
    {
        $game = $_SESSION['game'];

        for ($i = 0; $i < $game->playersCount; ++$i) {
            $game->players[] = new Player([
              'id' => $i + 1,
              'name' => $_POST['player' . ($i + 1)],
              'nassau' => $_POST['player' . ($i + 1) . 'nassau'],
              'scores' => [],
            ]);
        }

        $_SESSION['game'] = $game;
        unset($game);

        $template = $this->twig->load('/Terms.twig');

        echo $template->render(['data' => $_SESSION['game']]);
    }
}