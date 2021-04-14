<?php


namespace DiscWolf\Models;


final class Score
{

    public ?int $hole;
    public ?string $position;
    public ?int $score;
    public ?float $pot;

    public function __construct(array $data)
    {
        $this->hole = $data['hole'] ?: null;
        $this->position = $data['position'] ?: null;
        $this->score = $data['score'] === '0' ? 0 : (int) $data['score'];
        $this->pot = $data['pot'] ?: null;
    }
}