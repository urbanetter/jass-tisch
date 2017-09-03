<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use Jass\Entity\Trick;
use Jass\GameStyle\TopDown;
use Jass\Strategy\Simple;
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

        // default strategies for all players except first player
        $strategies = [
            new Verrueren(),
            new Simple(),
        ];
        $game->players = [
            new \Jass\Entity\Player('Rüedu', 'Rüedu und Fränzu'),
            new \Jass\Entity\Player('Fridu', 'Fridu und Küsu', $strategies),
            new \Jass\Entity\Player('Fränzu', 'Rüedu und Fränzu', $strategies),
            new \Jass\Entity\Player('Küsu', 'Fridu und Küsu', $strategies),
        ];


        $game->currentPlayer = $game->players[0];
        $game->style = new TopDown();

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

}
