<?php

use Jass\Entity\Card;

class CardSetTest extends \PHPUnit\Framework\TestCase
{
    public function testDrawCards()
    {
        $set = \Jass\CardSet\jassSet();

        $threeCards = \Jass\CardSet\drawCards($set, 3);

        $this->assertEquals(3, count($threeCards));
        $this->assertTrue($threeCards[0] instanceof \Jass\Entity\Card);

        $testSet = [
            Card::from(Card\Suit::BELL, Card\Value::ACE),
            Card::from(Card\Suit::BELL, Card\Value::KING),
            Card::from(Card\Suit::BELL, Card\Value::QUEEN),
        ];

        $used1 = [
            Card::from(Card\Suit::BELL, Card\Value::QUEEN),
        ];
        $used2 = [
            Card::from(Card\Suit::BELL, Card\Value::KING),
        ];

        $oneCard = \Jass\CardSet\drawCards($testSet, 1, $used1, $used2);

        $this->assertEquals(1, count($oneCard));
        $this->assertTrue($oneCard[0] instanceof \Jass\Entity\Card);
        $this->assertEquals(Card\Suit::BELL, $oneCard[0]->suit);
        $this->assertEquals(Card\Value::ACE, $oneCard[0]->value);

    }

}