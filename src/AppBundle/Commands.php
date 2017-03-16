<?php

namespace AppBundle;


use AppBundle\Entity\Game;
use AppBundle\Entity\Command as CommandEntity;

class Commands
{
    private $commands;

    public function addCommand(Command $command)
    {
        $name = $command->getName();
        $this->commands[$name] = $command;
    }

    public function run(Game $game, string $input)
    {
        $words = explode(" ", trim($input));

        $command = new CommandEntity();
        $command->name = $input;
        $name = array_shift($words);
        $command->params = $words;

        if (isset($this->commands[$name])) {
            $this->commands[$name]->run($game, $command);
        } else {
            $command->text = "Hm, I do not know $name";
        }

        return $command;
    }
}