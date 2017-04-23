<?php

namespace Jass\Strategy;


use Jass\Entity\Player;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Trick;

class Simple extends Strategy
{
    public function card(Player $player, TrickEntity $trick, GameStyle $style)
    {
        if (Hand\canFollowSuit($player->hand, $trick->leadingSuit)) {
            $card = Hand\highest(Hand\suit($player->hand, $trick->leadingSuit), $style->orderFunction());
            $bestTrickCard = Hand\highest(Hand\suit(Trick\playedCards($trick), $trick->leadingSuit), $style->orderFunction());
            if ($style->orderValue($bestTrickCard) > $style->orderValue($card)) {
                $card =  Hand\lowest(Hand\suit($player->hand, $trick->leadingSuit), $style->orderFunction());
            }
        } else {
            $card = Hand\lowest($player->hand, $style->orderFunction());
        }

        return $card;
    }

    public function firstCardOfTrick(Player $player, GameStyle $style)
    {
        return Hand\highest($player->hand, $style->orderFunction());
    }


}