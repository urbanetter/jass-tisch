<?php

use Jass\Entity\Card;

class HandTest extends \PHPUnit\Framework\TestCase
{
    public function testBock()
    {
        $style = new \Jass\GameStyle\TopDown();
        $orderFunction = [$style, 'orderValue'];

        $bock = \Jass\Hand\bock([], \Jass\Entity\Card\Suit::ROSE, $orderFunction);

        $this->assertNotNull($bock);
        $this->assertEquals(\Jass\Entity\Card::from(\Jass\Entity\Card\Suit::ROSE, \Jass\Entity\Card\Value::ACE), $bock);
    }

    public function testBestSuit()
    {
        $style = new \Jass\GameStyle\TopDown();
        $orderFunction = [$style, 'orderValue'];

        $hand = [
            Card::from(Card\Suit::ROSE, Card\Value::TEN),
            Card::from(Card\Suit::ROSE, Card\Value::JACK),
            Card::from(Card\Suit::ROSE, Card\Value::QUEEN),
            Card::from(Card\Suit::ROSE, Card\Value::KING),
            Card::from(Card\Suit::BELL, Card\Value::ACE),
        ];

        $expected = Card\Suit::BELL;
        $actual = \Jass\Hand\bestSuit([], $hand, $orderFunction);

        $this->assertSame($expected, $actual);
    }
}