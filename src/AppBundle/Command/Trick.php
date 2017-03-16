<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;

class Trick implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        $trick = $game->currentTrick;
        $player = $game->currentPlayer;
        while (!\Jass\Trick\isFinished($trick, $game->players)) {
            $card = $player->strategy->nextCard($game->style, $trick, $player);

            \Jass\Player\playTurn($trick, $player, $card);

            $player = $player->nextPlayer;
        }

        $game->lastPlayer = $game->currentPlayer;
        $game->currentPlayer = \Jass\Trick\winner($trick, $game->style);
        foreach ($game->players as $player) {
            $player->strategy->lookAtTrick($trick);
        }

        $game->playedTricks[] = $trick;
        $game->currentTrick = new \Jass\Entity\Trick();

        $command->trick = $trick;
        $winningTurn = \Jass\Trick\winningTurn($trick, $game->style);
        $command->text = "Winner: " . $winningTurn->card . " von " . $winningTurn->player;
    }

    public function getName(): string
    {
        return 'trick';
    }
}