<?php


namespace DiscWolf\Classes;


final class ScoreHole
{
    protected array $rawHoleScores;
    protected bool $soloWolfPack;
    protected string $winner;
    protected float $pot;

    public function __construct(array $rawHoleScores)
    {
        $this->rawHoleScores = $rawHoleScores;
        $this->soloWolfPack = $this->soloWolfPack($rawHoleScores);
        $this->winner = $this->whoWon($rawHoleScores);
        $this->pot = (float) $_SESSION['game']->currentPot;
    }

    protected function soloWolfPack(array $rawHolesScores): bool
    {
        //collect the submitted raw scores and search for a score for any position of Pack/Partner 'p'
        $pack = (int) collect($rawHolesScores)->where('position', 'p')->pluck('score')->first();

        //If there is no Pack/Partner score,
        if (!$pack) {
            //then this is a solo wolf hole.
            return true;
        }

        //There is a pack/partner score, so this is a pack hole.
        return false;
    }

    protected function lowestPackScore(array $rawHolesScores): int
    {
        $wolf = (int) collect($rawHolesScores)->where('position', 'w')->pluck('score')->first();
        $pack = (int) collect($rawHolesScores)->where('position', 'p')->pluck('score')->first();

        if ($wolf < $pack) {

            $lowestPackScore = $wolf;
        } elseif ($pack < $wolf) {

            $lowestPackScore = $pack;
        } else {

            $lowestPackScore = $wolf;
        }

        return $lowestPackScore;
    }

    protected function whoWon(array $rawHolesScores): string
    {
        $wolf = (int) collect($rawHolesScores)->where('position', 'w')->pluck('score')->first();

        $soloWolfPack = $this->soloWolfPack($rawHolesScores);
        if (!$soloWolfPack) {
            $pack = 'pack';
            $lowestPackScore = $this->lowestPackScore($rawHolesScores);
        } else {
            $pack = 'wolf';
            $lowestPackScore = $wolf;
        }

        $sheepsScores = collect($rawHolesScores)->where('position', 's')->pluck('score')->toArray();
        $lowestSheepScore = min($sheepsScores);

        if ($lowestPackScore < $lowestSheepScore) {
            $_SESSION['game']->currentPot = $_SESSION['game']->skinsAmount * $_SESSION['game']->playersCount;
            return $pack;
        }

        if ($lowestSheepScore < $lowestPackScore)  {
            $_SESSION['game']->currentPot = $_SESSION['game']->skinsAmount * $_SESSION['game']->playersCount;
            return 'sheep';
        }

        // Pushing, so add this current pot to the next hole's pot.
        $_SESSION['game']->currentPot = $_SESSION['game']->currentPot + ($_SESSION['game']->skinsAmount * $_SESSION['game']->playersCount);

        return 'push';
    }

    public function applyScore(): array
    {
        $rawScores = $this->rawHoleScores;

        if ($this->winner === 'wolf') {
            //Wolf's pot = Skins * 2

            //First, collect the raw score elements, and select the one that is in the wolf position.
            $wolf = collect($rawScores)->where('position', 'w')->first();
            //Set it's pot element to equal twice the skins.
            $wolf['pot'] = $this->pot + ($_SESSION['game']->skinsAmount * 2);

            //Collect all the raw score elements except for the wolf, cutting out the original.
            $newRawScores = collect($rawScores)->where('position', '!=', 'w')->all();
            //Add the newly awarded wolf array to the new raw scores array
            $newRawScores[] = $wolf;

            //Redefine $rawScores with new raw scores array
            return $newRawScores;
        }

        if ($this->winner === 'pack') {
            //Skins pot gets split between Wolf and Pack Partner
            $pot = round($this->pot / 2, 2);

            //Collect the raw score elements, and select the one that is in the wolf and partner positions.
            $wolf = collect($rawScores)->where('position', 'w')->first();
            $wolf['pot'] = $pot;
            $pack = collect($rawScores)->where('position', 'p')->first();
            $pack['pot'] = $pot;

            //Collect all the raw score elements except for the wolf and partner, cutting out the original.
            $newRawScores = collect($rawScores)->where('position', '!=', 'w')
              ->where('position', '!=', 'p')
              ->all();
            $newRawScores[] = $wolf;
            $newRawScores[] = $pack;

            //Redefine $rawScores with new raw scores array
            return $newRawScores;
        }

        if ($this->winner === 'sheep') {
            $sheep = collect($rawScores)->where('position', 's')->all();
            $sheepCount = count($sheep);
            $pot = round(($this->pot + ($_SESSION['game']->skinsAmount * $sheepCount)), 2) / $sheepCount;

            $newRawScores = collect($rawScores)->where('position', '!=', 's')->all();
            foreach ($sheep as $lamb) {
                $lamb['pot'] = $pot;
                $newRawScores[] = $lamb;
            }

            return $newRawScores;
        }

        return $this->rawHoleScores;
    }
}