<?php

namespace AppBundle;

use AppBundle\Entity\Game;
use AppBundle\Entity\Command as CommandEntity;

interface Command
{
    public function run(Game $game, CommandEntity $command);

    public function getName() : string;
}