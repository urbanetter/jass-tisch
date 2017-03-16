<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;

class Suits implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        $command->text = implode(", ", \Jass\CardSet\suits());
    }

    public function getName(): string
    {
        return 'suits';
    }
}