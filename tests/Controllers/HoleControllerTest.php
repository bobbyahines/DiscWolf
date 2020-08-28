<?php
declare(strict_types=1);


namespace Tests\Controllers;


use DiscWolf\Controllers\HoleController;
use PHPUnit\Framework\TestCase;


class HoleControllerTest extends TestCase
{

    public function testClassIsInstanceOfHoleController(): void
    {
        $holeController = new HoleController();

        $this->assertInstanceOf('DiscWolf\Controllers\HoleController', $holeController);
    }
}
