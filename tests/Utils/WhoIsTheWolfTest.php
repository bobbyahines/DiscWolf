<?php
declare(strict_types=1);


namespace Tests\Utils;


use DiscWolf\Utils\WhoIsTheWolf;
use PHPUnit\Framework\TestCase;


class WhoIsTheWolfTest extends TestCase
{

    public function testClassIsInstanceOfWhoIsTheWolfUtil(): void
    {
        $witw = new WhoIsTheWolf();

        $this->assertInstanceOf('DiscWolf\Utils\WhoIsTheWolf', $witw);
    }
}