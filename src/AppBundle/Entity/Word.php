<?php
/**
 * Created by PhpStorm.
 * User: Jasper
 * Date: 07-Aug-16
 * Time: 08:05 PM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping;

/**
 * @Mapping\Entity
 * @Mapping\Table(name="word")
 */
class Word
{
    /**
     * @Mapping\Id
     * @Mapping\GeneratedValue(strategy="AUTO")
     * @Mapping\Column(type="integer")
     */
    private $id;

    /**
     * @Mapping\Column(type="string")
     */
    private $word;

    /**
     * @return mixed
     */
    public function getWord()
    {
        return strip_tags($this->word);
    }

    /**
     * @param mixed $word
     */
    public function setWord($word)
    {
        $this->word = $word;
    }


}