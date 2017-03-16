<?php

class HandTest extends \PHPUnit_Framework_TestCase
{
    public function testBock()
    {
        $style = new \Jass\GameStyle\TopDown();
        $orderFunction = [$style, 'orderValue'];

        $bock = \Jass\Hand\bock([], \Jass\Entity\Card\Suit::ROSE, $orderFunction);

        $this->assertNotNull($bock);
        $this->assertEquals(\Jass\Entity\Card::from(\Jass\Entity\Card\Suit::ROSE, \Jass\Entity\Card\Value::ACE), $bock);
    }
}