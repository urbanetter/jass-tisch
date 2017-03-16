<?php

namespace Jass\Strategy;


use Jass\Entity\Player;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Trick;
use Jass\CardSet;

class Bock extends Strategy
{

    public function nextCard(GameStyle $gameStyle, TrickEntity $trick, Player $player)
    {
        if (!$trick->leadingSuit) {
            $card = Hand\highest($player->hand, [$gameStyle, 'orderValue']);
            foreach (CardSet\suits() as $suit) {
                $bockCard = Hand\bock($this->playedCards, $suit, [$gameStyle, 'orderValue']);
                if (in_array($bockCard, $player->hand)) {
                    $card = $bockCard;
                    break;
                }
            }
        } else {
            if (Hand\canFollowSuit($player->hand, $trick->leadingSuit)) {
                $card = Hand\highest(Hand\suit($player->hand, $trick->leadingSuit), [$gameStyle, 'orderValue']);
                $bestTrickCard = Hand\highest(Hand\suit(Trick\playedCards($trick), $trick->leadingSuit), [$gameStyle, 'orderValue']);
                if ($gameStyle->orderValue($bestTrickCard) > $gameStyle->orderValue($card)) {
                    $card =  Hand\lowest(Hand\suit($player->hand, $trick->leadingSuit), [$gameStyle, 'orderValue']);
                }
            } else {
                $card = Hand\lowest($player->hand, [$gameStyle, 'orderValue']);
            }
        }

        return $card;
    }

}