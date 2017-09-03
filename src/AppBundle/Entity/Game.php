<?php

namespace AppBundle\Entity;


use Jass\Entity\Player;
use Jass\Entity\Team;
use Jass\Entity\Trick;
use Jass\GameStyle\GameStyle;
use Jass\Strategy\Strategy;

class Game
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var GameStyle
     */
    public $style;

    /**
     * @var Trick[]
     */
    public $playedTricks = [];

    /**
     * @var Player
     */
    public $currentPlayer;

    /**
     * @var Trick
     */
    public $currentTrick;

    /**
     * @var Player[]
     */
    public $players;

}