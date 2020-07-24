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
    }
}