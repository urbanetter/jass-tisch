<?php

namespace Jass\Entity;

use Jass\Strategy\Strategy;

class Player
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var Card[]
     */
    public $hand;

    /**
     * @var Team
     */
    public $team;

    /**
     * @var Player
     */
    public $nextPlayer;

    /**
     * @var array
     */
    public $brain;

    /**
     * @var Strategy
     */
    public $strategy;

    /**
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function __toString()
    {
        return $this->name;
    }
}