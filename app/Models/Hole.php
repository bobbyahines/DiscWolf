<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Uuid;

final class Hole
{
    public string $uuid;
    public int $number;
    public int $skins_to_win;
    public array $scores;

    /**
     * Hole constructor.
     * @param array $holeData
     */
    public function __construct($holeData = [])
    {
        $this->uuid = $holeData['uuid'] ?: Uuid::uuid4()->toString();
        $this->number = $holeData['number'] ?: 0;
        $this->skins_to_win = $holeData['skins_to_win'] ?: 1;
        $this->scores = $holeData['scores'] ?: [];
    }
}
