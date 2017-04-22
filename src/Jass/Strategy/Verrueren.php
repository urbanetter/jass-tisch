<?php

namespace Jass\Strategy;


use Jass\Entity\Player as PlayerEntity;
use Jass\Entity\Trick as TrickEntity;
use Jass\GameStyle\GameStyle;
use Jass\Hand;
use Jass\Trick;
use Jass\Player;

class Verrueren extends Bock
{

    public function nextCard(GameStyle $gameStyle, TrickEntity $trick, PlayerEntity $player)
    {
        $orderFunction = [$gameStyle, 'orderValue'];
        $this->logs = [];
        $this->log("$player has hand: " . implode(", ", Hand\ordered($player->hand, $orderFunction)));
        if (!$trick->leadingSuit) {
            $suit = Hand\bestSuit($this->playedCards, $player->hand, $orderFunction);
            $card = Hand\highest(Hand\suit($player->hand, $suit), $orderFunction);
            $this->log("$player best card is $card");

            // will we win?
            if ($card != Hand\bock($this->playedCards, $card->suit, $orderFunction)) {
                $this->log("$player probably does not win with this card, bock is " . Hand\bock($this->playedCards, $card->suit, $orderFunction));
                if (Player\knows($player, "azeigt") && Hand\canFollowSuit($player->hand, Player\recall($player, 'azeigt'))) {
                    $this->log("player recalls azeigt: " . Player\recall($player, "azeigt"));
                    $card = Hand\lowest(Hand\suit($player->hand, Player\recall($player, 'azeigt')), $orderFunction);
                    Player\forget($player, "azeigt");
                } else {
                    // can we find the suit to play from verührt strategy?
                    $suit = null;
                    if (Player\knows($player, 'verrüert')) {
                        $this->log("$player knows the following suits are verrüert: " . implode(Player\recall($player, 'verrüert')));

                        $suits = Hand\suits($player->hand);
                        $suits = array_diff($suits, Player\recall($player, 'verrüert'));

                        $this->log("$player: potential suits: " . implode(", ", $suits));

                        if (count($suits) == 1) {
                            $suit = array_pop($suits);
                            $this->log("$player chooses $suit");
                        }

                    }

                    if (!$suit) {
                        $suit = Hand\bestSuit($this->playedCards, $player->hand, [$gameStyle, 'orderValue']);
                        Player\remember($player, 'azoge', $suit);
                        $this->log("$player thinks $suit is the best suit and does 'azie'");
                    }
                    $card = Hand\lowest(Hand\suit($player->hand, $suit), [$gameStyle, 'orderValue']);
                }
            }
        } else {
            if (Player\isInMyTeam($player, $trick->turns[0]->player)) {
                $leadingCard = $trick->turns[0]->card;
                $this->log("$player: leading card ($leadingCard) is from my team");
                if ($leadingCard != Hand\bock($this->playedCards, $leadingCard->suit, [$gameStyle, 'orderValue'])) {
                    if (Player\knows($player, 'azoge')) {
                        // leading player gave this card, so do not treat it as "azeigt"
                        $this->log("$player: Yay! Azoge worked!! Suit: " .$leadingCard->suit);
                        Player\forget($player, 'azoge');
                    } else {
                        // no winner card, treat it like "azeigt"
                        $this->log("$player thinks the $leadingCard is 'azeigt'");
                        Player\remember($player, "azeigt", $leadingCard->suit);
                    }
                }
            }
            if (Hand\canFollowSuit($player->hand, $trick->leadingSuit)) {
                $this->log("$player can follow suit");
                $card = Hand\highest(Hand\suit($player->hand, $trick->leadingSuit), $orderFunction);
                $winningCard = Hand\highest(Hand\suit(Trick\playedCards($trick), $trick->leadingSuit), $orderFunction);
                if ($gameStyle->orderValue($winningCard) > $gameStyle->orderValue($card)) {
                    $this->log("$player can not beat leading card, so give lowest");
                    $card =  Hand\lowest(Hand\suit($player->hand, $trick->leadingSuit), $orderFunction);
                }
            } else {
                $this->log("$player cannot follow suit");
                $worstSuit = Hand\worstSuit($this->playedCards, $player->hand, [$gameStyle, 'orderValue']);
                $this->log("$player: worst suit is $worstSuit");
                $card = Hand\lowest(Hand\suit($player->hand, $worstSuit), [$gameStyle, 'orderValue']);
                $this->log("$player verrüert $card");
            }
        }

        return $card;
    }

    public function lookAtTrick(TrickEntity $trick)
    {
        parent::lookAtTrick($trick);

        if ($trick->turns[0]->card->suit != $trick->turns[2]->card->suit) {
            Player\rememberMore($trick->turns[0]->player, 'verrüert', $trick->turns[2]->card->suit);
        }
    }


}