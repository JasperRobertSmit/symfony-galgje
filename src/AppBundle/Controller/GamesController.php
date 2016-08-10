<?php
/**
 * Created by PhpStorm.
 * User: Jasper
 * Date: 07-Aug-16
 * Time: 04:14 PM
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Game;
use AppBundle\Entity\Word;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class GamesController extends Controller
{
    /**
     * @Route("/games", name="start_game_action")
     */
    public function startGameAction()
    {
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Word')->findAll();

        //Pick a random word from the result array
        $key = array_rand($result);
        $word = $result[$key];

        /**
         * Get the length of the word
         */
        $word = $word->getWord();
        $wordLength = strlen($word);

        $response = '';
        $charAmount = 0;
        for ($i = 0; $i < $wordLength; $i++) {
            $response .= '_ ';
            $charAmount = $i;
        }

        //Instellingen voor het spel
        $game = new Game();
        $game->setWord($word);
        $game->setStatus('busy');
        $game->setTriesLeft(3);

        //Game get added to the Database here
        $em->persist($game);
        $em->flush();

        //Game id doesn't exist before it's been added to the database
        $gameId = $game->getId();

        return $this->render('games/start.html.twig', [
            'word' => $word,
            'response' => $response,
            'charAmount' => $charAmount,
            'gameId' => $gameId
        ]);
    }

    /**
     * @Route("/games/{gameId}/{letter}")
     * @Method("POST")
     */
    public function inputLetter($gameId, $letter)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('AppBundle:Game')->find($gameId);

        $word = trim($game->getWord());
        $game->setCharGuessed($letter);
        //Store the guessed letter in the database
        $em->persist($game);
        $em->flush();

        //Retrieve the guessed letters from the database
        $guessedLetters = $game->getCharGuessed();

        //Fill the response with blank lines equal to the length of the word
        $wordresponse = array();

        for ($i = 0; $i < strlen($word); $i++) {
            $wordresponse[$i] = '_';
        }

        //Used to tell the player his letter was not found
        $letterFound = true;

        //Save the status
        $em->persist($game);
        $em->flush();

        //For every letter that has been guessed
        foreach ($guessedLetters as $letter) {

            //For every letter in the $word
            for ($i = 0; $i < strlen($word); $i++) {


                //Get the position of the $letter in the $word if it's in
                $pos = strpos($word, $letter, $i);

                //Check if the letter is in the word
                if ($pos !== false) {

                    //Check if the position of the $letter found is the same as the offset
                    //If it is it means that the current position is occupied by the current $letter
                    //Which means we should add it to the assoc. array at that location.
                    if ($pos == $i) {
                        $wordresponse[$i] = $letter;
                    }
                }
            }

                //The player has already lost an attempt due to this letter, I'm not going to punish him/her twice
                if (!in_array($letter, $game->getIncorrectGuesses())) {

                    //If the letter is not in the word the player loses an attempt
                    if (strpos($word, $letter) === false) {

                        //If the player has no more tries set status to failed
                        if ($game->getTriesLeft() === 1) {
                            $game->setStatus('failed');
                            $game->setTriesLeft(0);
                        } else {
                            //Actually remove an attempt left here
                            $triesLeft = $game->getTriesLeft();
                            $triesLeft -= 1;
                            //Update the players tries left variable
                            $game->setTriesLeft($triesLeft);
                        }

                        //Add the current letter to the list of incorrectGuesses to make sure the player doesn't get
                        //punished twice for inputting the same letter
                        $game->setIncorrectGuesses($letter);

                        //Save the new status / tries_left
                        $em->persist($game);
                        $em->flush();


                        $letterFound = false;
                    }
                }
        }

        //Get the status of the current game of the player
        //It should be either BUSY | FAILED | SUCCESS
        $status = $game->getStatus();

        //Get the tries left for the player
        $triesLeft = $game->getTriesLeft();

        //Turn the word array back to a string
        $implodedWordResponse = implode(" ", $wordresponse);

        $noSpaceImplodedWordResponse = implode("", $wordresponse);
        if($noSpaceImplodedWordResponse == $game->getWord()){
            $victory = true;
        }else{
            $victory = false;
        }



        $guessedChar = $game->getCharGuessed();

        $result = array(
            'wordresponse'      => $implodedWordResponse,
            'status'            => $status,
            'triesleft'         => $triesLeft,
            'letternotfound'    => $letterFound,
            'victory'           => $victory,
            'guessedchar'       => $guessedChar
        );

        return new JsonResponse($result);

    }

    /**
     * @Route("/")
     */
    public function homeAction()
    {
        $string = "Waddup";

        return $this->render('games/start.html.twig', [
            'word' => $string
        ]);
    }

    /**
     * @Route("/populatedatabase")
     */
    public function populateDatabase()
    {

        /**
         * Used once to populate the database
         * Safeguard
         */

        if (true) {
            return new Response('<html><body>Accident prevention</body></html>');
        }



        $dir = $this->get('kernel')->getRootDir() .'\Resources\files\words.english';


        $file = fopen($dir, 'r');
        $words = array();

        //While the end of the file isn't reached keep adding the current line to the $words array
        while (!feof($file)) {
            array_push($words, fgets($file));
        }


        foreach ($words as $word) {
            $wordObj = new Word();


            $wordObj->setWord(trim($word));

            $em = $this->getDoctrine()->getManager();
            $em->persist($wordObj);
            $em->flush();
        }


        return $this->render('Database populated');
    }
}