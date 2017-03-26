<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;
use Jass\GameStyle\BottomUp;
use Jass\GameStyle\TopDown;
use Jass\GameStyle\Trump;

class Style implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        if ($command->params) {

            $validStyles = ['top', 'bottom', 'trump'];
            if (in_array($command->params[0], $validStyles)) {
                if ($command->params[0] == 'top') {
                    $game->style = new TopDown();
                }
                if ($command->params[0] == 'bottom') {
                    $game->style = new BottomUp();
                }
                if ($command->params[0] == 'trump') {
                    $game->style = new Trump($command->params[1]);
                }
            }
        }

        $command->text = "Game style is " . $game->style->name();
    }

    public function getName(): string
    {
        return 'style';
    }
}