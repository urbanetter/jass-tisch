<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Jass\Entity\Trick;
use Jass\GameStyle\TopDown;
use Jass\Strategy\Verrueren;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Jass\CardSet;
use Jass\Table;
use Jass\Hand;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $games = $this->get('app.games')->games();
        return $this->render('default/index.html.twig', ['games' => $games]);
    }

    /**
     * @Route("/game/{game}", name="game")
     */
    public function gameAction($game)
    {
        $game = $this->get('app.games')->get($game);
        dump($game);
        return $this->render('default/game.html.twig', ['game' => $game]);
    }

    /**
     * @Route("/create", name="create")
     */
    public function createAction()
    {
        $game = new Game();
        $game->name = md5(uniqid());

        list($teams, $players) = $this->teamSetup();
        $game->teams = $teams;
        $game->players = $players;

        $game->currentPlayer = $players[0];
        $game->currentTrick = new Trick();
        $game->style = new TopDown();

        foreach ($game->players as $player) {
            $player->strategy = new Verrueren();
        }

        $this->get('app.games')->add($game);

        return $this->redirectToRoute('game', ['game' => $game->name]);

    }

    /**
     * @Route("/cmd/{game}", name="command")
     */
    public function commandAction($game, Request $request)
    {
        $input = $request->getContent();
        $game = $this->get('app.games')->get($game);

        $command = $this->get('app.commands')->run($game, $input);

        $this->get('app.games')->save();

        if ($command->trick && count($command->trick->turns)) {
            foreach ($command->trick->turns as $turn) {
                $turn->player->nextPlayer = null;
            }
        }
        if ($command->player) {
            $command->player->nextPlayer = null;
        }

        return new JsonResponse($command);
    }

    private function teamSetup()
    {
        $ueli = new \Jass\Entity\Player("Ueli");
        $sandy = new \Jass\Entity\Player("Sandy");
        $heinz = new \Jass\Entity\Player("Heinz");
        $peter = new \Jass\Entity\Player("Peter");

        $teamUeliAndHeinz = new \Jass\Entity\Team('Ueli and Heinz');
        $teamSandyAndPeter = new \Jass\Entity\Team('Sandy and Peter');

        $ueli->team = $teamUeliAndHeinz;
        $ueli->nextPlayer = $sandy;

        $sandy->team = $teamSandyAndPeter;
        $sandy->nextPlayer = $heinz;

        $heinz->team = $teamUeliAndHeinz;
        $heinz->nextPlayer = $peter;

        $peter->team = $teamSandyAndPeter;
        $peter->nextPlayer = $ueli;

        $players = [$ueli, $sandy, $heinz, $peter];
        $teams = [$teamUeliAndHeinz, $teamSandyAndPeter];

        return [$teams, $players];
    }
}
