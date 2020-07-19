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
        $skinsValue = (float) $args['skins_value'] * 1.0;
        $golfCourse = $args['course_name'];

        $currentHole = $params['current_hole'] ?: null;
        $matchHoles = $params['match_holes'] ?: null;
        $availableSkins = $params['available_skins'] ?: null; //counter for available skins, holds carry over.

        // added variables
        $playerName = $args['player_name'];
        $playerWolfOrder = $args['player_wolf_order'];
        $playerNassau = $args['player_nassau'];
        $playerHoleScore = $args['player_hole_score'];
        $playerSkins = $args['player_skins'];
        $playerTotalScore = $args['player_total_score'];
        $wolfPartner = $args['wolf_partner'];


        $this->setSessionArgs('playerCount', $playerCount);
        $this->setSessionArgs('golfCourse', $golfCourse);
        $this->setSessionArgs('matchSkins', $matchSkins);

        $this->setSessionArgs('playerName', $playerName);
        $this->setSessionArgs('playerWolfOrder', $playerWolfOrder);
        $this->setSessionArgs('playerNassau', $playerNassau);
        $this->setSessionArgs('playerHoleScore', $playerHoleScore);
        $this->setSessionArgs('playerSkins', $playerSkins);
        $this->setSessionArgs('playerTotalScore', $playerTotalScore);
        $this->setSessionArgs('wolfPartner', $wolfPartner);

        $params = [
          'data' => [
                'playerCount' => $playerCount,
                'golfCourse' => $golfCourse,
                'skinsValue' => $skinsValue,
                'playerTerms' => $args['player_terms'] ?: "off",
                'currentHole' => $currentHole,
                // added array
                'matchHoles' => $matchHoles,
                'player_data' => [
                    'player_number' => [
                        'playerName' => $playerName,
                        'playerWolfOrder' => $playerWolfOrder,
                        'playerNassau' => $playerNassau,
                        'playerHoleScore' => $playerHoleScore,
                        'playerSkins' => $playerSkins,
                        'playerTotalScore' => $playerTotalScore,
                        'wolfPartner' => $wolfPartner,
                        'matchSkins' => $matchSkins, // match skins added here to assist with debugging later.
                        'matchHoles' => $matchHoles,
                      ],
                  ],
            ],
        ];

        $template = $this->twig->load('/RegisterPlayers.twig');

        echo $template->render($params);
    }
}
