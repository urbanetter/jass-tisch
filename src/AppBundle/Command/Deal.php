<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Game;
use AppBundle\Entity\Command as CommandEntity;
use Jass\CardSet;
use Jass\Entity\Trick;
use Jass\Strategy\Verrueren;
use Jass\Table;

class Deal implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        $set = CardSet\jassSet();
        shuffle($set);
        Table\deal($set, $game->players);
        $game->playedTricks = [];
        $game->currentTrick = new Trick();
        $game->currentPlayer = $game->players[0];

        foreach ($game->players as $player) {
            $player->brain = [];
            $player->strategy = new Verrueren();
        }

        $command->text = "Shuffled and dealt cards.";
        $command->hand = \Jass\Hand\ordered($game->players[0]->hand, [$game->style, 'orderValue']);
        $command->trick = $game->currentTrick;
        $command->player = $game->currentPlayer;
    }

    public function getName(): string
    {
        return 'deal';
    }
}