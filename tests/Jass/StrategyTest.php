<?php

use Jass\Entity\Card;

class StrategyTest extends \PHPUnit\Framework\TestCase
{
    public function testAzeige()
    {
        $hand = [
            Card::from(Card\Suit::BELL, Card\Value::KING),
            Card::from(Card\Suit::BELL, Card\Value::QUEEN),
        ];

        $mateHand = [
            Card::from(Card\Suit::BELL, Card\Value::ACE),
            Card::from(Card\Suit::BELL, Card\Value::TEN),
        ];

        $enemyHand1 = [
            Card::from(Card\Suit::OAK, Card\Value::ACE),
            Card::from(Card\Suit::OAK, Card\Value::TEN),
        ];

        $enemyHand2 = [
            Card::from(Card\Suit::OAK, Card\Value::EIGHT),
            Card::from(Card\Suit::OAK, Card\Value::NINE),
        ];

        $simple = new \Jass\Strategy\Simple();
        $azeige = new \Jass\Strategy\Azeige();

        $players = [
            new \Jass\Entity\Player('Rüedu', 'Rüedu und Fränzu', [$azeige]),
            new \Jass\Entity\Player('Fridu', 'Fridu und Küsu', [$simple]),
            new \Jass\Entity\Player('Fränzu', 'Rüedu und Fränzu', [$azeige]),
            new \Jass\Entity\Player('Küsu', 'Fridu und Küsu', [$simple]),
        ];

        $players[0]->hand = $hand;
        $players[1]->hand = $enemyHand1;
        $players[2]->hand = $mateHand;
        $players[3]->hand = $enemyHand2;

        $style = new \Jass\GameStyle\TopDown();
        $first = \Jass\Game\runTrick($players, $style, $players[0]);

        $winner = \Jass\Trick\winner($first, $style->orderFunction());
        $this->assertSame($players[2], $winner);

        $this->assertSame(Card\Suit::BELL, $players[2]->brain['azeigt']);

        $second = \Jass\Game\runTrick($players, $style, $winner);

        $winner = \Jass\Trick\winner($second, $style->orderFunction());
        $this->assertSame($players[0], $winner);

        $this->assertSame(Card\Suit::BELL, $second->leadingSuit);

    }
}