<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;

class Why implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        if ($game->lastPlayer) {
            $command->text = implode("<br>", $game->lastPlayer->strategy->logs);
        } else {
            $command->text = "Last card was not chosen by computer, so how should I know?";
        }
    }

    public function getName(): string
    {
        return 'why';
    }
}