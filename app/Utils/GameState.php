<?php


namespace DiscWolf\Utils;


use DiscWolf\Models\Game;
use DiscWolf\Models\Player;

final class GameState
{

    private function gameFilesDirectory(): string
    {
        return dirname(__DIR__, 2) . '/public/gameFiles/';
    }

    private function gameFileName(string $gameUuid)
    {
        return $this->gameFilesDirectory() . $gameUuid . '.json';
    }

    private function createGameObjects(array $contents): Game
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

        return $game;
    }

    public function loadGame(string $gameUuid): Game
    {

        try {
            $fileContents = file_get_contents($this->gameFileName($gameUuid));
            if (!$fileContents) {
                throw new \Exception('GameState::loadGame() -- Unable to load game file ' . $gameUuid . '.json.');
            }
            $decodedFileContents = json_decode($fileContents, true, 512, JSON_THROW_ON_ERROR);
            $game = $this->createGameObjects(collect($decodedFileContents)->toArray());
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            exit;
        }

        return $game;
    }

    public function saveGame(Game $game)
    {
        $this->destroyGame($game->uuid);

        try {
            $fileName = $this->gameFileName($game->uuid);
            $save = file_put_contents($fileName, collect($game));
            if (!$save) {
                throw new \Exception('GameState::saveGame() -- Failed to save game file.');
            }
            return $save;
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
    }

    public function destroyGame(string $gameUuid): ?bool
    {
        $gameFileName = $this->gameFileName($gameUuid);
        if (file_exists($gameFileName)) {

            try {
                $destroyFile = unlink($gameFileName);
                if (!$destroyFile) {
                    throw new \Exception('GameState::destoryGame() -- Failed to delete game file.');
                }
                return $destroyFile;
            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }

        return null;
    }
}