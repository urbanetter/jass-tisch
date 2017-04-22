<?php
namespace Jass\Player;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\Entity\Turn;
use Jass\GameStyle\GameStyle;
use Jass\Strategy\Strategy;

function playTurn(Trick $trick, Player $player, Card $card)
{
    if (!in_array($card, $player->hand)) {
        throw new \InvalidArgumentException('Card not in hand!');
    }

    $index = array_search($card, $player->hand);
    unset($player->hand[$index]);

    $turn = new Turn();
    $turn->player = $player;
    $turn->card = $card;

    if (!$trick->leadingSuit) {
        $trick->leadingSuit = $card->suit;
    }

    $trick->turns[] = $turn;
}

function chooseCard(Player $player, Trick $trick, GameStyle $style)
{
    $card = null;
    if (!$trick->leadingSuit) {
        foreach ($player->strategies as $strategy) {
            $card = $strategy->firstCardOfTrick($player, $style);
            if ($card) {
                return $card;
            }
        }
    } elseif (count($trick->turns) == 2) {
        foreach ($player->strategies as $strategy) {
            $card = $strategy->teammatePlayed($player, $trick, $style);
            if ($card) {
                return $card;
            }
        }
    } else {
        foreach ($player->strategies as $strategy) {
            $card = $strategy->otherTeamPlayed($player, $trick, $style);
            if ($card) {
                return $card;
            }
        }
    }
    throw new \InvalidArgumentException('Could not figure out a card');
}

function isInMyTeam(Player $myself, Player $other)
{
    return $myself->team == $other->team;
}

function knows(Player $player, $what)
{
    return isset($player->brain[$what]);
}

function remember(Player $player, $what, $value)
{
    $player->brain[$what] = $value;
}

function rememberMore(Player $player, $what, $value)
{
    $player->brain[$what][] = $value;
    $player->brain[$what] = array_unique($player->brain[$what]);
}

function recall(Player $player, $what)
{
    return $player->brain[$what];
}

function forget(Player $player, $what)
{
    if (knows($player, $what)) {
        unset($player->brain[$what]);
    }
}