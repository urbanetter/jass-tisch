<?php

namespace Jass\Strategy;


use Jass\Entity\Player as PlayerEntity;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Player;

class Azeige extends Bock
{
    public function firstCardOfTrick(PlayerEntity $player, GameStyle $style)
    {
        $card = parent::firstCardOfTrick($player, $style);

        if (!$card) {
            if (isset($player->brain['azeigt']) && Hand\canFollowSuit($player->hand, $player->brain['azeigt'])) {
                $card = Hand\highest(Hand\suit($player->hand, $player->brain['azeigt']), $style->orderFunction());
                unset($player->brain['azeigt']);
            } else {
                $bestSuit = Hand\bestSuit($player->brain['playedCards'], $player->hand, $style->orderFunction());
                $player->brain['azoge'] = $bestSuit;
                $card = Hand\lowest(Hand\suit($player->hand, $bestSuit), $style->orderFunction());
            }
        }

        return $card;
    }

    public function card(PlayerEntity $player, TrickEntity $trick, GameStyle $style)
    {
        if (Player\isInMyTeam($player, $trick->turns[0]->player)) {
            $leadingCard = $trick->turns[0]->card;
            $playedCards = isset($player->brain['playedCards']) ? $player->brain['playedCards'] : [];
            if ($leadingCard !== Hand\bock($playedCards, $leadingCard->suit, $style->orderFunction())) {
                if (isset($player->brain['azoge'])) {
                    // leading player gave this card, so do not treat it as "azeigt"
                    unset($player->brain['azoge']);
                } else {
                    // no winner card, treat it like "azeigt"
                    $player->brain['azeigt'] = $leadingCard->suit;
                }
            }
        }

        return parent::card($player, $trick, $style);
    }
}