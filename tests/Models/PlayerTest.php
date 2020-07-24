<?php
declare(strict_types=1);


namespace Tests\Models;


use DiscWolf\Models\Player;
use PHPUnit\Framework\TestCase;


class PlayerTest extends TestCase
{

    public function testClassIsInstanceOfPlayerModel(): void
    {
        $player = new Player();

        $this->assertInstanceOf('Discwolf\Models\Player', $player);
    }
}