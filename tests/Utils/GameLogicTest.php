<?php
declare(strict_types=1);


namespace Tests\Utils;


use DiscWolf\Models\Game;
use DiscWolf\Utils\GameState;
use DiscWolf\Utils\GameLogic;
use PHPUnit\Framework\TestCase;


class GameLogicTest extends TestCase
{

    /**
     * Takes in a json file ID and returns a complete Game Object.
     *
     * @param string $gameUuid
     * @return Game
     */
    private function setupGame($fileName): Game
    {
        $gameState = new GameState();
        $loadGame = $gameState->loadGame($fileName);
        unset($gameState);

        return $loadGame;
    }

    public function testClassIsInstanceOfGameLogicUtil(): void
    {
        $game = new GameLogic();

        $this->assertInstanceOf('Discwolf\Utils\GameLogic', $game);
    }

    public function testIntSum(): void
    {
        $int_list_test = array(1, 2, 3, 4, 5);
        $summation = new GameLogic();
        $sum_test = $summation->sumListIntegers($int_list_test);

        $this->assertSame(15, $sum_test);
    }

    public function testFloatSum(): void
    {
        $float_list_test = array(0.25, 0.25, 0.25);
        $summation = new GameLogic();
        $sum_test = $summation->sumListFLoats($float_list_test);

        $this->assertSame(0.75, $sum_test);
    }

    /**
     * Takes in a json file ID and returns a complete Game Object.
     *
     * @param string $gameUuid
     * @return object
     */
    public function setupGameTest(string $fileName): object
    {
        try {
            // $fileContents = file_get_contents($this->gameFileName($gameUuid));
            $test_directory = dirname(__DIR__, 2) . '/tests/Utils/';
            $fileName = $test_directory . $fileName . '.json';
            $fileContents = file_get_contents($fileName);
            if (!$fileContents) {
                throw new RuntimeException('GameState::loadGame() -- Unable to load game file ' . $fileName . '.json.');
            }
            $game = json_decode($fileContents, false, 512, JSON_THROW_ON_ERROR);
        } catch (RuntimeException $exception) {
            echo $exception->getMessage();
            exit;
        }
        return $game;
    }

    public function testOneLowestScore(): void
    {
        $game_test = $this->setupGameTest('one_lowest_score');
        $gameLogic = new GameLogic();

        $ls = $gameLogic->lowestHoleScore($game_test->holes);
        $single_array = array(
            1 => -3,
        );
        $this->assertSame($ls, $single_array);
    }

    public function testMultipleLowestScore(): void
    {
        $game_test = $this->setupGameTest('multiple_lowest_score');
        $gameLogic = new GameLogic();

        $ls = $gameLogic->lowestHoleScore($game_test->holes);
        $multi_array = array(
            1 => -3,
            3 => -3
        );
        $this->assertSame($ls, $multi_array);
    }

    public function testGetPlayerPositions(): void
    {
        $game_test = $this->setupGameTest('multiple_lowest_score');
        $gameLogic = new GameLogic();

        $positions = array(1=>"W", 2=>"Y", 3=>"P", 4=>"Y");

        $test_positions = $gameLogic->getPlayerPositions($game_test->holes);
        $this->assertTrue($test_positions==$positions);
    }

    public function testisSkinWin(): void {
        $skin_win_test = array(
            [array(1=>"W", 2=>"P"), 'win'],
            [array(1=>"Y", 2=>"P"), 'push'],
            [array(1=>"Y", 2=>"W", 3=>"P"), 'push'],
            [array(1=>"Y", 2=>"W", 3=>"P", 4=>"Y"), 'push'],
            [array(1=>"Y", 2=>"Y"), 'win'],
            [array(1=>"P", 2=>"W"), 'win'],
            [array(1=>"Y"), 'win'],
            [array(1=>"P"), 'win'],
            [array(1=>"W"), 'win']
        );
        foreach ($skin_win_test as $skin_win) {
            $gameLogic = new GameLogic();
            $skin_test = $gameLogic->isSkinWin($skin_win[0]);
            $this->assertSame($skin_test, $skin_win[1]);
        }
    }

    public function testGetSkinValue(): void
    {
        $skin_value_test = array(
            [array(1=>"Y", 2=>"W", 3=>"P", 4=>"Y"), 1],
            [array(1=>"Y", 2=>"Y", 3=>"W", 4=>"Y"), 2]
        );
        foreach ($skin_value_test as $skin_value) {
            $gameLogic = new GameLogic();
            $skin_test = $gameLogic->getSkinValue($skin_value[0]);
            $this->assertSame($skin_test, $skin_value[1]);
        }
    }

    public function testGetWinTeam(): void
    {
        $game_test = $this->setupGameTest('multiple_lowest_score');
        $gameLogic = new GameLogic();

        $win_team_test = array(1=>"W", 3=>"P");

        $low_scores = $gameLogic->lowestHoleScore($game_test->holes);
        $p_pos = $gameLogic->getPlayerPositions($game_test->holes);
        $p_low_score = array_intersect_key($p_pos, $low_scores);
        $win_team = $gameLogic->getWinTeam($p_pos, $p_low_score);

        $this->assertSame($win_team, $win_team_test);
    }

    public function testCalculateTeamSkins(): void
    {
        $game_test = $this->setupGameTest('multiple_lowest_score');
        $gameLogic = new GameLogic();

        $skin_test_check = array(1=>1, 2=>0, 3=>1, 4=>0);

        $gameLogic = new GameLogic();
        $skins_array = $gameLogic->calculateSkins($game_test->holes);

        $this->assertTrue($skins_array == $skin_test_check);
    }

    public function testCalculateWolfSkins(): void
    {
        $game_test = $this->setupGameTest('wolf_single_loss');

        $skin_test_check = array(1=>0, 2=>2, 3=>2, 4=>2);

        $gameLogic = new GameLogic();
        $skins_array = $gameLogic->calculateSkins($game_test->holes);
        $this->assertTrue($skins_array == $skin_test_check);
    }

    public function testUpdatePlayers(): void
    {
        $game_test = $this->setupGameTest('multiple_lowest_score');

        $args = array('holeNumber'=>1);

        $gameLogic = new GameLogic();
        $game_test = $gameLogic->updatePlayers($args, $game_test);
        // print_r($game_test);
        $skins_test = $game_test->players[0]->total_skins;
        $this->assertTrue(1 == $skins_test);
    }
}

// $data = '{
//     "name": "Aragorn",
//     "race": "Human"
// }';
// $character = json_decode($data);
// echo $character->name;
// echo $holes_test;
// $jdecode = json_decode($holes_test);
// echo $jdecode->uuid;
