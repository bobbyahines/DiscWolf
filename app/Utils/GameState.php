<?php
declare(strict_types=1);

namespace DiscWolf\Utils;


use DiscWolf\Models\Game;
use DiscWolf\Models\Hole;
use DiscWolf\Models\Player;
use RuntimeException;


final class GameState
{

    /**
     * Retuns the absolute path to the cache directory, as a string.
     *
     * @return string
     */
    private function gameFilesDirectory(): string
    {
        return dirname(__DIR__, 2) . '/public/gameFiles/';
    }

    /**
     * Returns the absolute path to json file in the cache directory.
     *
     * @param string $gameUuid
     * @return string
     */
    private function gameFileName(string $gameUuid): string
    {
        return $this->gameFilesDirectory() . $gameUuid . '.json';
    }

    /**
     * Takes in the array'ed json file and returns its contents as a complete Game model object.
     * Public for GameLogicTest.
     *
     * @param array $contents
     * @return Game
     */
    public function createGameObjects(array $contents): Game
    {
        $game = new Game([
            'uuid' => $contents['uuid'],
            'playerCount' => $contents['playerCount'],
            'playerSkins' => $contents['playerSkins'],
            'courseName' => $contents['courseName'],
            'holeCount' => $contents['holeCount'],
            'players' => [],
            'holes' => [],
            'startTimeStamp' => $contents['startTimeStamp'],
            'endTimeStamp' => $contents['endTimeStamp'],
        ]);

        $contentPlayers = collect($contents['players'])->toArray();
        $players = [];
        foreach ($contentPlayers as $player) {
            $players[] = new Player([
                'uuid' => $player['uuid'],
                'order' => $player['order'],
                'name' => $player['name'],
                'nassau' => $player['nassau'],
            ]);
        }
        $game->players = $players;

        $contentHoles = collect($contents['holes'])->toArray();
        $holes = [];
        foreach ($contentHoles as $hole) {
            $holes[] = new Hole([
                'uuid' => $hole['uuid'],
                'number' => $hole['number'],
                'wolfPlayerNumber' => $hole['wolfPlayerNumber'],
                'wolfPackNumber' => $hole['wolfPackNumber'],
                'score' => $hole['score'],
            ]);
        }
        $game->holes = $holes;

        return $game;
    }

    /**
     * Takes in a json file ID and returns a complete Game Object.
     *
     * @param string $gameUuid
     * @return Game
     */
    public function loadGame(string $gameUuid): Game
    {

        try {
            $fileContents = file_get_contents($this->gameFileName($gameUuid));
            if (!$fileContents) {
                throw new RuntimeException('GameState::loadGame() -- Unable to load game file ' . $gameUuid . '.json.');
            }
            $decodedFileContents = json_decode($fileContents, true, 512, JSON_THROW_ON_ERROR);
            $game = $this->createGameObjects(collect($decodedFileContents)->toArray());
        } catch (RuntimeException $exception) {
            echo $exception->getMessage();
            exit;
        }

        return $game;
    }

    /**
     * Takes in a Game model object and saves it to a json file in the cache directory, destroying any previous copy.
     *
     * @param Game $game
     * @return false|int
     */
    public function saveGame(Game $game)
    {
        $this->destroyGame($game->uuid);

        try {
            $fileName = $this->gameFileName($game->uuid);
            $save = file_put_contents($fileName, collect($game));
            if (!$save) {
                throw new RuntimeException('GameState::saveGame() -- Failed to save game file.');
            }
            return $save;
        } catch (RuntimeException $exception) {
            echo $exception->getMessage();
        }
    }

    /**
     * Takes in a game file id and deletes its cached json file.
     *
     * @param string $gameUuid
     * @return bool|null
     */
    public function destroyGame(string $gameUuid): ?bool
    {
        $gameFileName = $this->gameFileName($gameUuid);
        if (file_exists($gameFileName)) {

            try {
                $destroyFile = unlink($gameFileName);
                if (!$destroyFile) {
                    throw new RuntimeException('GameState::destoryGame() -- Failed to delete game file.');
                }
                return $destroyFile;
            } catch (RuntimeException $exception) {
                echo $exception->getMessage();
            }
        }

        return null;
    }
}
