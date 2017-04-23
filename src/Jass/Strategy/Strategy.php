<?php

namespace Jass\Strategy;


use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;

abstract class Strategy
{
    public function firstCardOfTrick(Player $player, GameStyle $style)
    {
        return null;
    }

    public function card(Player $player, Trick $trick, GameStyle $style)
    {
        return null;
    }

    public function trickFinished(Player $player, Trick $trick, GameStyle $style)
    {
        return null;
    }

}