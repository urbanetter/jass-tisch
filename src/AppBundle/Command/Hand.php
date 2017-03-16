<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;

class Hand implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        $cards =  $game->currentPlayer->hand;
        if ($command->params) {
            $suit = array_shift($command->params);
            $cards = \Jass\Hand\suit($cards, $suit);
        }
        $command->hand = \Jass\Hand\ordered($cards, [$game->style, 'orderValue']);
    }

    public function getName(): string
    {
        return 'hand';
    }
}