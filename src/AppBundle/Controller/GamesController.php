<?php
/**
 * Created by PhpStorm.
 * User: Jasper
 * Date: 07-Aug-16
 * Time: 04:14 PM
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class GamesController extends Controller
{
    /**
     * @Route("/games", name="start_game_action")
     */
    public function startGameAction()
    {
        $em = $this->getDoctrine()->getManager();

    }


    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $string = "Waddup";

        return  $this->render('games/start.html.twig', [
            'message' => $string
        ]);
    }
}