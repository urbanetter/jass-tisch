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
     * @var string
     */
    public $team;

    /**
     * @var array
     */
    public $brain;

    /**
     * @var Strategy[]
     */
    public $strategies;

    /**
     * @var bool
     */
    public $isManual;

    /**
     * @param string $name
     * @param string $team
     * @param null|Strategy[] $strategies
     */
    public function __construct($name = 'Ueli', $team = 'Team Ueli', $strategies = null)
    {
        $this->name = $name;
        $this->team = $team;
        $this->isManual = is_null($strategies);
        $this->strategies = $strategies;
    }

    public function __toString()
    {
        return $this->name . (($this->isManual) ? " (manual)" : "");
    }
}