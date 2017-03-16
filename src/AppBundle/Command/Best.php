<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;

class Best implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        if (!$command->params) {
            $command->params = ["suit"];
        }
        $what = array_shift($command->params);
        if ($what == "suit") {
            $command->text = "Best suit is " . \Jass\Hand\bestSuit($game->playedTricks, $game->currentPlayer->hand, [$game->style, 'orderValue']);
        }
    }

    public function getName(): string
    {
        return 'best';
    }
}