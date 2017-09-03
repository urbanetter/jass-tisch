<?php

namespace AppBundle\Command;


use AppBundle\Command;
use AppBundle\Entity\Command as CommandEntity;
use AppBundle\Entity\Game;
use Jass\Entity\Card;
use Jass\Entity\Trick;
use function Jass\Game\isFinished;
use function Jass\Player\chooseCard;
use function Jass\Player\showTrickToPlayers;
use function Jass\Table\nextPlayer;

class Play implements Command
{

    public function run(Game $game, CommandEntity $command)
    {
        if (!$game->currentPlayer->hand) {
            $command->text = "Game is finished. Start a new one with 'deal'";
            return;
        }

        if ($command->params && $command->params[0] == "round") {
            array_shift($command->params);
            $targetPlayer = $game->players[0];
        } else {
            $targetPlayer = nextPlayer($game->players, $game->currentPlayer);
        }

        $card = false;
        if ($command->params) {
            $card = $this->getCardFromCommand($command);
        }

        if (!$game->currentTrick) {
            $game->currentTrick = new Trick();
        }

        do {
            if (!$card) {
                $card = chooseCard($game->currentPlayer, $game->currentTrick, $game->style);
            }

            $command->text .= $game->currentPlayer . " plays " . $card . ". ";

            \Jass\Player\playTurn($game->currentTrick, $game->currentPlayer, $card);
            $card = null;
            $command->trick = $game->currentTrick;

            if (\Jass\Trick\isFinished($game->currentTrick, $game->players)) {
                $winnerTurn = \Jass\Trick\winningTurn($game->currentTrick, $game->style->orderFunction());
                $command->text .= $winnerTurn->player . " wins trick with " . $winnerTurn->card . " (" . \Jass\Trick\points($game->currentTrick, $game->style->pointFunction()) . " points). ";

                showTrickToPlayers($game->players, $game->currentTrick, $game->style);

                $game->playedTricks[] = $game->currentTrick;
                $game->currentPlayer = $winnerTurn->player;
                $command->hand = \Jass\Trick\playedCards($game->currentTrick);
                if (!isFinished($game->playedTricks)) {
                    $game->currentTrick = new Trick();
                } else {
                    $command->text .= "Game is finished.";
                }

            } else {
                $game->currentPlayer = nextPlayer($game->players, $game->currentPlayer);
            }
        } while ($game->currentPlayer != $targetPlayer || isFinished($game->playedTricks));

        $command->text .= "Next player is " . $game->currentPlayer;
        $command->player = $game->currentPlayer;
    }

    public function getName(): string
    {
        return 'play';
    }

    private function getCardFromCommand(CommandEntity $command)
    {
        $possibleSuits = \Jass\CardSet\suits();
        $possibleValues = \Jass\CardSet\values();

        $card = new Card();

        $first = strtolower(array_shift($command->params));
        if (in_array($first, $possibleSuits)) {
            $card->suit = $first;
        }
        if (in_array($first, $possibleValues)) {
            $card->value = $first;
        }
        $second = strtolower(array_shift($command->params));
        if (in_array($second, $possibleSuits)) {
            $card->suit = $second;
        }
        if (in_array($second, $possibleValues)) {
            $card->value = $second;
        }

        return \Jass\CardSet\isValidCard($card) ? $card : null;
    }
}