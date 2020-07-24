<?php
declare(strict_types=1);


namespace Tests\Models;


use DiscWolf\Models\Hole;
use PHPUnit\Framework\TestCase;


class HoleTest extends TestCase
{

    public function testClassIsInstanceOfHoleModel(): void
    {
        $hole = new Hole();

        $this->assertInstanceOf('Discwolf\Models\Hole', $hole);
    }
}