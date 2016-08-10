<?php
/**
 * Created by PhpStorm.
 * User: Jasper
 * Date: 07-Aug-16
 * Time: 05:07 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $word;

    /**
     * @ORM\Column(type="string", )
     */
    private $status = 'busy';

    /**
     * @ORM\Column(type="integer")
     */
    private $tries_left;



    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $char_guessed = array();


    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $incorrectGuesses = array();

    /**
     * @return mixed
     */
    public function getIncorrectGuesses()
    {
        return $this->incorrectGuesses;
    }

    /**
     * @param mixed $incorrectGuesses
     */
    public function setIncorrectGuesses($incorrectGuesses)
    {
        $this->incorrectGuesses[] = $incorrectGuesses;
    }



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCharGuessed()
    {
        return $this->char_guessed;
    }

    /**
     * @param mixed $char_guessed
     */
    public function setCharGuessed($char_guessed)
    {
        //Check if this is the first letter being added
        if($this->char_guessed != null){
            //Check if the letter is in the array already if it isn't add it else do nothing
            if(!in_array($char_guessed, $this->char_guessed)){
                $this->char_guessed[] = $char_guessed;
            }
        }else{
            //First letter added
            $this->char_guessed[] = $char_guessed;
        }


    }



    /**
     * @return mixed
     */
    public function getWord()
    {
        return $this->word;
    }

    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTriesLeft()
    {
        return $this->tries_left;
    }

    /**
     * @param mixed $tries_left
     */
    public function setTriesLeft($tries_left)
    {
        $this->tries_left = $tries_left;
    }

}