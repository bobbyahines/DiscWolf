<?php
declare(strict_types=1);


namespace Tests\Controllers;


use DiscWolf\Controllers\HomeController;
use PHPUnit\Framework\TestCase;


class HomeControllerTest extends TestCase
{

    public function testClassIsInstanceOfHoleController(): void
    {
        $homeController = new HomeController();

        $this->assertInstanceOf('DiscWolf\Controllers\HomeController', $homeController);
    }
}