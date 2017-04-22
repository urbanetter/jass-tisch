<?php

namespace Jass\Strategy;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;
use Jass\Hand;

class Dumb extends Strategy
{
    public function firstCardOfTrick(Player $player, GameStyle $style)
    {
        return Hand\highest($player->hand, $style->orderFunction());
    }

    public function teammatePlayed(Player $player, Trick $trick, GameStyle $style)
    {
        return Hand\lowest($player->hand, $style->orderFunction());
    }

    public function otherTeamPlayed(Player $player, Trick $trick, GameStyle $style)
    {
        if (Hand\canFollowSuit($player->hand, $trick->leadingSuit)) {
            return Hand\highest(Hand\suit($player->hand, $trick->leadingSuit), $style->orderFunction());
        } else {
            return Hand\lowest($player->hand, $style->orderFunction());
        }
    }
}