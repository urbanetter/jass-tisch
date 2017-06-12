<?php

namespace Jass\Strategy;


use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\CardSet;
use function Jass\Trick\playedCards;

class Bock extends Simple
{

    public function firstCardOfTrick(Player $player, GameStyle $style)
    {
        $playedCards = isset($player->brain['playedCards']) ? $player->brain['playedCards'] : [];
        foreach (CardSet\suits() as $suit) {
            $bockCard = Hand\bock($playedCards, $suit, $style->orderFunction());
            if (in_array($bockCard, $player->hand)) {
                return $bockCard;
            }
        }

        return null;
    }

    public function trickFinished(Player $player, Trick $trick, GameStyle $style)
    {
        if (!isset($player->brain['playedCards'])) {
            $player->brain['playedCards'] = array();
        }
        $player->brain['playedCards'] = array_merge($player->brain['playedCards'], playedCards($trick));
    }
}