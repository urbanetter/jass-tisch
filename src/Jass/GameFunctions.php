<?php

namespace Jass\Game;

use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;
use function Jass\Hand\last;
use function Jass\Player\chooseCard;
use function Jass\Player\playTurn;
use function Jass\Player\showTrickToPlayers;
use function Jass\Trick\winner;

/**
 * @param Trick[] $playedTricks
 * @return bool
 */
function isFinished($playedTricks)
{
    return count($playedTricks) >= 9;
}

/**
 * @param Trick[] $playedTricks
 * @return bool
 */
function hasStarted($playedTricks)
{
    return is_array($playedTricks) && count($playedTricks) > 0;
}

/**
 * @param Trick[] $playedTricks
 * @param callable $valueFunction
 * @return bool
 */
function isMatched($playedTricks, $valueFunction)
{
    if (!isFinished($playedTricks)) {
        throw new \InvalidArgumentException('Game is not finished yet, no point in asking if its matched');
    }
    $firstTeam = $playedTricks[0]->turns[0]->player->team;
    $firstTeamWins = array_filter($playedTricks, function(Trick $trick) use ($firstTeam, $valueFunction) {
        $winner = winner($trick, $valueFunction);
        return $winner->team == $firstTeam;
    });

    return count($firstTeamWins) == 0 || count($firstTeamWins) == 9;
}

/**
 * @param Player[] $players
 * @param GameStyle $style
 * @param Trick[] $playedTricks
 * @param Player|null $starter
 * @return Trick[]
 */
function run($players, GameStyle $style, $playedTricks = [], Player $starter = null)
{
    if ($playedTricks) {
        $trick = last($playedTricks);
        $player = \Jass\Trick\nextPlayer($players, $trick, $style->orderFunction());
        if (\Jass\Trick\isFinished($trick, $players)) {
            $trick = new Trick();
        }
    } else {
        $trick = new Trick();
        if ($starter) {
            $player = $starter;
        } else {
            $player = $players[array_rand($players)];
        }
    }

    while (!isFinished($playedTricks) && !$player->isManual) {
        $card = chooseCard($player, $trick, $style);
        playTurn($trick, $player, $card);
        $player = \Jass\Trick\nextPlayer($players, $trick, $style->orderFunction());
        if (\Jass\Trick\isFinished($trick, $players)) {
            showTrickToPlayers($players, $trick, $style);
            $playedTricks[] = $trick;

            $trick = new Trick();
        }
    }

    return $playedTricks;


}

function runTrick($players, GameStyle $style, Player $player, Trick $trick = null)
{
    if (is_null($trick)) {
        $trick = new Trick();
    }
    while(!\Jass\Trick\isFinished($trick, $players)) {
        $card = chooseCard($player, $trick, $style);
        playTurn($trick, $player, $card);
        $player = \Jass\Trick\nextPlayer($players, $trick, $style->orderFunction());
    }
    showTrickToPlayers($players, $trick, $style);

    return $trick;
}