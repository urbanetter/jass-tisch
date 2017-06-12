<?php

namespace Jass\Strategy;


use Jass\Entity\Player as PlayerEntity;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Trick;

class Verrueren extends Bock
{
    public function firstCardOfTrick(PlayerEntity $player, GameStyle $style)
    {
        $verruert = isset($player->brain['verrüert']) ? $player->brain['verrüert'] : [];
        $suits = Hand\suits($player->hand);

        $playable = array_diff($suits, $verruert);
        if (count($playable) == 1) {
            $suit = $playable[array_rand($playable)];
            return Hand\lowest(Hand\suit($player->hand, $suit), $style->orderFunction());
        }

        return null;
    }

    public function trickFinished(PlayerEntity $player, TrickEntity $trick, GameStyle $style)
    {
        if (
            $trick->turns[0]->player === $player &&
            Trick\winner($trick, $style->orderFunction()) === $player &&
            $trick->turns[2]->card->suit != $trick->turns[0]->card->suit
        ) {
            if (!isset($player->brain['verrüert'])) {
                $player->brain['verrüert'] = [];
            }
            $player->brain['verrüert'][] = $trick->turns[2]->card->suit;
        }
    }


}