<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;
use Jass\GameStyle\BottomUp;
use Jass\GameStyle\TopDown;
use Jass\GameStyle\Trump;

class Potential implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        $styles = [
            new TopDown(),
            new BottomUp()
        ];

        $suits = \Jass\CardSet\suits();
        foreach ($suits as $suit) {
            $styles[] = new Trump($suit);
        }

        $result = [];
        foreach ($styles as $style) {
            $potential = 0;
            foreach ($suits as $suit) {
                $potential += \Jass\Hand\potential([], $game->currentPlayer->hand, $suit, [$style, 'orderValue']);
            }
            $result[] = $style->name() . ": " . $potential;
        }

        $command->text = implode(", ", $result);
    }

    public function getName(): string
    {
        return 'potential';
    }
}