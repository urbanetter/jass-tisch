<?php

namespace Jass\Strategy;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;

abstract class Strategy
{
    /**
     * @var Card[]
     */
    protected $playedCards = [];

    public $logs = [];

    /**
     * @param GameStyle $gameStyle
     * @param Trick $trick
     * @param Player $player
     * @return Card next card the player plays
     */
    abstract public function nextCard(GameStyle $gameStyle, Trick $trick, Player $player);

    public function lookAtTrick(Trick $trick)
    {
        $this->playedCards = array_merge($this->playedCards,  \Jass\Trick\playedCards($trick));
    }

    protected function log($msg)
    {
        $this->logs[] = $msg;
    }

}