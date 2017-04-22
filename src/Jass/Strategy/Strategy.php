<?php

namespace Jass\Strategy;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;

abstract class Strategy
{
    public function firstCardOfTrick(Player $player, GameStyle $style)
    {
        return null;
    }

    public function teammatePlayed(Player $player, Trick $trick, GameStyle $style)
    {
        return null;
    }

    public function otherTeamPlayed(Player $player, Trick $trick, GameStyle $style)
    {
        return null;
    }

    public function trickFinished(Player $player, Trick $trick, GameStyle $style)
    {
        return null;
    }

}