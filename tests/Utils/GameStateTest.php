<?php
declare(strict_types=1);


namespace Tests\Utils;


use DiscWolf\Utils\GameState;
use PHPUnit\Framework\TestCase;


class GameStateTest extends TestCase
{
    public function testClassIsInstanceOfGameStateUtil(): void
    {
        $game = new GameState();

        $this->assertInstanceOf('Discwolf\Utils\GameState', $game);
    }
}