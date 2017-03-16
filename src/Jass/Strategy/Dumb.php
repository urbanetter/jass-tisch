<?php

namespace Jass\Strategy;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;
use Jass\Hand;

class Dumb extends Strategy
{

    /**
     * @param GameStyle $gameStyle
     * @param Trick $trick
     * @param Player $player
     * @return Card next card the player plays
     */
    public function nextCard(GameStyle $gameStyle, Trick $trick, Player $player)
    {
        if (!$trick->leadingSuit) {
            $card = Hand\highest($player->hand, [$gameStyle, 'orderValue']);
        } else {
            if (Hand\canFollowSuit($player->hand, $trick->leadingSuit)) {
                $card = Hand\highest(Hand\suit($player->hand, $trick->leadingSuit), [$gameStyle, 'orderValue']);
            } else {
                $card = Hand\lowest($player->hand, [$gameStyle, 'orderValue']);
            }
        }

        return $card;
    }
}