<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Uuid;

final class Player
{
    public string $uuid;
    public int $order;
    public string $name;
    public float $nassau;
    public array $hole_scores;
    public int $total_score;
    public array $wolf_team;
    public int $total_skins;

    /**
     * Player constructor.
     * @param array $playerData
     */
    public function __construct($playerData = [])
    {
        $this->uuid = $playerData['uuid'] ?: Uuid::uuid4()->toString();
        $this->order = $playerData['order'] ?: 0;
        $this->name = $playerData['name'] ?: 'Stupid No Name';
        $this->nassau = $playerData['nassau'] ?: 0;
        $this->hole_scores = $playerData['hole_score'] ?: [];
        $this->total_score = $playerData['total_score'] ?: -99;
        $this->wolf_team = $playerData['wolf_team'] ?: [];
        $this->total_skins = $playerData['total_skins'] ?: 0;
    }
}
