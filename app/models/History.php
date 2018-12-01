<?php

/**
 *
 */
class History extends \Phalcon\Mvc\Model
{
    /**
     *
     * @var Integer
     */
    public $id;

    /**
     *
     * @var Integer
     */
    public $id_chat;

    /**
     *
     * @var String
     */
    public $question;

    /**
     *
     * @var String
     */
    public $answer;

    /**
     *
     * @var String
     */
    public $date;


    public function initialize()
    {
        $this->setSchema("public");
    }
}
