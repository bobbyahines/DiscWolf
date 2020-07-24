<?php
declare(strict_types=1);


namespace Tests\Models;


use DiscWolf\Models\Game;
use PHPUnit\Framework\TestCase;


class GameTest extends TestCase
{
    public function testClassIsInstanceOfGameModel(): void
    {
        $game = new Game();

        $this->assertInstanceOf('Discwolf\Models\Game', $game);
    }

    public function testDefaultConstructionOfGameModel(): void
    {
        $game = new Game();

        $this->assertSame(4, $game->playerCount);
        $this->assertSame(0.25, $game->playerSkins);
        $this->assertSame('', $game->courseName);
        $this->assertSame(9, $game->holeCount);
        $this->assertSame(0, $game->endTimeStamp);
    }

    public function testInjectedConstructionOfGameModel(): void
    {
        $game = new Game([
            'playerCount' => 6,
            'playerSkins' => 1,
            'courseName' => 'Red Ribbon Moose DGC',
            'holeCount' => 9,
        ]);

        $this->assertSame(6, $game->playerCount);
        $this->assertSame(1.0, $game->playerSkins);
        $this->assertSame('Red Ribbon Moose DGC', $game->courseName);
        $this->assertSame(9, $game->holeCount);
    }

    public function testEndTimeStampMethod()
    {
        $game = new Game();

        $this->assertSame(0, $game->endTimeStamp);

        $game->endTimeStamp();

        $this->assertNotSame(0, $game->endTimeStamp);
    }
}