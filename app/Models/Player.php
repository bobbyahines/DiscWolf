<?php
declare(strict_types=1);

namespace DiscWolf\Models;


use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class Player
{
    public string $uuid;
    public int $order;
    public string $name;
    public float $nassau;

    public function __construct($playerData = [])
    {
        $this->uuid = $playerData['uuid'] ?: Uuid::uuid4()->toString();
        $this->order = $playerData['order'] ?: 0;
        $this->name = $playerData['name'] ?: 'Stupid No Name';
        $this->nassau = $playerData['nassau'] ?: 0;
    }
}