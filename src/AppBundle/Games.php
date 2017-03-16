<?php

namespace AppBundle;


use AppBundle\Entity\Game;

class Games
{
    private $filename;

    private $games = [];

    /**
     * Games constructor.
     * @param $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
        if (file_exists($this->filename)) {
            $this->games = unserialize(file_get_contents($this->filename));
        }
    }

    public function add(Game $game)
    {
        $name = $game->name ? $game->name : "Game #" . (count($this->games) + 1);
        $this->games[$name] = $game;
        $this->save();
    }

    public function save()
    {
        file_put_contents($this->filename, serialize($this->games));
    }

    public function get($name) : Game
    {
        return isset($this->games[$name]) ? $this->games[$name] : null;
    }

    public function remove($name)
    {
        unset($this->games[$name]);
        $this->save();
    }

    public function games()
    {
        return $this->games;
    }
}