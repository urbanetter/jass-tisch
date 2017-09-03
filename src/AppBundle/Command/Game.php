<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game as GameEntity;

class Game implements Command
{

    public function run(GameEntity $game, CommandEntity $command)
    {
        $command->trick = $game->currentTrick;
        $command->player = $game->currentPlayer;

        $command->text = "Game style: " . $game->style->name();

        $command->text .= ", played tricks: " . count($game->playedTricks);
        $playedPoints = array_sum(array_map(function($trick) use ($game) {
            return \Jass\Trick\points($trick, $game->style);
        }, $game->playedTricks));
        $command->text .= ", played points: " . $playedPoints;

    }

    public function getName(): string
    {
        return 'game';
    }
}