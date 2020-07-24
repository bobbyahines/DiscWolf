<?php
declare(strict_types=1);


namespace Tests\Controllers;


use DiscWolf\Controllers\Controller;
use PHPUnit\Framework\TestCase;


class ControllerTest extends TestCase
{

    public function testClassIsInstanceOfController(): void
    {
        $controller = new Controller();

        $this->assertInstanceOf('DiscWolf\Controllers\Controller', $controller);
    }
}