<?php

namespace Jass\GameStyle;


use Jass\Entity\Card;
use Jass\Entity\Card\Value;
use Jass\Entity\Team;

class TopDown extends GameStyle
{

    /**
     * @param Card $card
     * @param string $leadingSuit
     * @return int
     */
    public function orderValue(Card $card, $leadingSuit = null)
    {
        $order = $this->order();
        $result = array_search($card->value, $order);

        // increase order if its the same suit like leading turn
        if ($leadingSuit && $leadingSuit == $card->suit) {
            $result += 100;
        }
        return $result;
    }

    protected function order()
    {
        return [Value::SIX, Value::SEVEN, Value::EIGHT, Value::NINE, Value::TEN, Value::JACK, Value::QUEEN, Value::KING, Value::ACE];
    }

    public function beginningPlayer($players)
    {
        return $players[array_rand($players)];
    }

    public function points(Card $card)
    {
        $values = [Value::EIGHT => 8, Value::TEN => 10, Value::JACK => 2, Value::QUEEN => 3, Value::KING => 4, Value::ACE => 11];

        return (isset($values[$card->value])) ? $values[$card->value] : 0;
    }

    public function teamPoints($tricks, Team $team)
    {
        $points = \Jass\Table\teamPoints($tricks, $team, $this);

        $lastOne = array_slice($tricks, -1);
        $winnerOfLastOne = \Jass\Trick\winner($lastOne[0], $this);
        $points += ($winnerOfLastOne->team == $team) ? 5 : 0;

        $points += ($points == 157) ? 100 : 0;

        return $points;
    }

    public function name()
    {
        return "Obäabä";
    }

}