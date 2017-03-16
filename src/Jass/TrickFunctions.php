<?php

namespace Jass\Trick;

use Jass\Entity\Card;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;

function isFinished(Trick $trick, $players)
{
    return count($trick->turns) == count($players);
}

function winner(Trick $trick, GameStyle $gameStyle)
{

    return winningTurn($trick, $gameStyle)->player;
}

function winningTurn(Trick $trick, GameStyle $gameStyle)
{
    $winningTurn = array_reduce($trick->turns, function ($winning, $turn) use ($gameStyle, $trick) {
        if (!$winning) {
            return $turn;
        }
        if ($gameStyle->orderValue($turn->card, $trick->leadingSuit) > $gameStyle->orderValue($winning->card, $trick->leadingSuit)) {
            return $turn;
        } else {
            return $winning;
        }
    });

    return $winningTurn;

}

function playedCards(Trick $trick)
{
    return ($trick->turns) ? array_map(function($turn) {
        return $turn->card;
    }, $trick->turns) : [];
}

function points(Trick $trick, GameStyle $gameStyle)
{
    return array_reduce(\Jass\Trick\playedCards($trick), function($value, Card $card) use ($gameStyle) {
        return $value + $gameStyle->points($card);
    }, 0);
}
