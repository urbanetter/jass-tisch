<?php

namespace AppBundle\Entity;


use Jass\Entity\Card;
use Jass\Entity\Player;
use Jass\Entity\Trick;

class Command
{
    public $name;

    public $params;

    public $text;

    /**
     * @var Trick
     */
    public $trick;

    /**
     * @var Card[]
     */
    public $hand;

    /**
     * @var Player
     */
    public $player;
}