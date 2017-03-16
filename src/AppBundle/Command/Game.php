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

        $command->text = "Played tricks: " . count($game->playedTricks);
        $playedPoints = array_sum(array_map(function($trick) use ($game) {
            return \Jass\Trick\points($trick, $game->style);
        }, $game->playedTricks));
        $command->text .= ", played points: " . $playedPoints;
        $command->text .= ", next player is " . $game->currentPlayer->name;

        $scoreboard = [$game->teams[0]->name => ['points' => 0, 'tricks' => 0], $game->teams[1]->name => ['points' => 0, 'tricks' => 0]];
        foreach ($game->playedTricks as $trick) {
            $winningTurn = \Jass\Trick\winningTurn($trick, $game->style);
            $teamName = $winningTurn->player->team->name;
            $scoreboard[$teamName]['tricks'] += 1;
            $scoreboard[$teamName]['points'] += \Jass\Trick\points($trick, $game->style);
        }

        foreach ($scoreboard as $name => $score) {
            $command->text .= ", team " . $name . " won " . $score["tricks"] . " tricks (" . $score['points'] . " points)";
        }
    }

    public function getName(): string
    {
        return 'game';
    }
}