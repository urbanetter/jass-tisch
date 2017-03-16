<?php

namespace Jass\Entity;


use Jass\GameStyle;

class Game
{

    /**
     * @var Player[]
     */
    public $players = [];

    /**
     * @var GameStyle
     */
    public $gameStyle;

    /**
     * @var Trick
     */
    public $currentTrick;

    /**
     * @var Trick[]
     */
    public $playedTricks;
}