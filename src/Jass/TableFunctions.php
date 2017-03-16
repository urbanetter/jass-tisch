<?php

namespace Jass\Table;


use Jass\Entity\Team;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Trick;

function deal($cards, $players)
{
    foreach ($players as $player) {
        $player->hand = [];
    }

    $player = $players[0];
    while (count($cards)) {
        $card = array_pop($cards);
        $player->hand[] = $card;
        $player = $player->nextPlayer;
    }

    return $players;
}

function teamPoints($tricks, Team $team, GameStyle $gameStyle)
{
    $tricks = array_filter($tricks, function(TrickEntity $trick) use ($team, $gameStyle){
        return ($team == Trick\winner($trick, $gameStyle)->team);
    });

    return array_sum(array_map(function(TrickEntity $trick) use ($gameStyle) {
        return Trick\points($trick, $gameStyle);
    }, $tricks));
}
