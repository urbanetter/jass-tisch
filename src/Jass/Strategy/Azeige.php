<?php

namespace Jass\Strategy;


use Jass\Entity\Player as PlayerEntity;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Trick;
use Jass\CardSet;
use Jass\Player;

class Azeige extends Bock
{

    public function nextCard(GameStyle $gameStyle, TrickEntity $trick, PlayerEntity $player)
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

            // will we win?
            if ($card != Hand\bock($this->playedCards, $card->suit, [$gameStyle, 'orderValue'])) {
                if (Player\knows($player, "azeigt") && Hand\canFollowSuit($player->hand, Player\recall($player, 'azeigt'))) {
                    $card = Hand\highest(Hand\suit($player->hand, Player\recall($player, 'azeigt')), [$gameStyle, 'orderValue']);
                    Player\forget($player, "azeigt");
                } else {
                    $bestSuit = Hand\bestSuit($this->playedCards, $player->hand, [$gameStyle, 'orderValue']);
                    Player\remember($player, 'azoge', $bestSuit);
                    $card = Hand\lowest(Hand\suit($player->hand, $bestSuit), [$gameStyle, 'orderValue']);
                }
            }
        } else {
            if (Player\isInMyTeam($player, $trick->turns[0]->player)) {
                $leadingCard = $trick->turns[0]->card;
                if ($leadingCard != Hand\bock($this->playedCards, $leadingCard->suit, [$gameStyle, 'orderValue'])) {
                    if (Player\knows($player, 'azoge')) {
                        // leading player gave this card, so do not treat it as "azeigt"
                        Player\forget($player, 'azoge');
                    } else {
                        // no winner card, treat it like "azeigt"
                        Player\remember($player, "azeigt", $leadingCard->suit);
                    }
                }
            }
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