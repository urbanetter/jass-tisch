<?php


class GameTest extends \PHPUnit\Framework\TestCase
{
    public function testSimpleGameRun()
    {
        $strategies = [new \Jass\Strategy\Dumb()];


        $players = [
            new \Jass\Entity\Player('Rüedu', 'Rüedu und Fränzu', $strategies),
            new \Jass\Entity\Player('Fridu', 'Fridu und Küsu', $strategies),
            new \Jass\Entity\Player('Fränzu', 'Rüedu und Fränzu', $strategies),
            new \Jass\Entity\Player('Küsu', 'Fridu und Küsu', $strategies),
        ];

        $cards = \Jass\CardSet\jassSet();

        \Jass\Table\deal($cards, $players);

        $style = new \Jass\GameStyle\TopDown();

        $playedTricks = \Jass\Game\run($players, $style);

        $this->assertTrue(\Jass\Game\isFinished($playedTricks));
        $this->assertCount(9, $playedTricks);
        $this->assertCount(0, $players[0]->hand);
    }

}