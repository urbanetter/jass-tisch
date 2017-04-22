<?php

namespace Jass\Table;


use Jass\Entity\Player;

function deal($cards, $players)
{
    foreach ($players as $player) {
        $player->hand = [];
    }

    shuffle($cards);

    $player = $players[0];
    while (count($cards)) {
        $card = array_pop($cards);
        $player->hand[] = $card;
        $player = nextPlayer($players, $player);
    }

    return $players;
}


function nextPlayer($players, Player $currentPlayer)
{
    $index = array_search($currentPlayer, $players);
    $index++;
    if (!isset($players[$index])) {
        $index = 0;
    }
    return $players[$index];
}
