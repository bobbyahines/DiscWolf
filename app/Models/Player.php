<?php declare(strict_types=1);


namespace DiscWolf\Models;


final class Player
{

    public ?int $id;
    public ?string $name;
    public ?float $nassau;
    public ?array $scores;

    public function __construct(array $data = [])
    {
        $this->id = (int) $data['id'] ?: null;
        $this->name = $data['name'] ?: null;
        $this->nassau = $data['nassau'] === '0.00' ? 0 : (float) $data['nassau'];
        $this->scores = (array) $data['scores'] ?: [];
    }
}