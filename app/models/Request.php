<?php

/**
 *
 */
class Request extends \Phalcon\Mvc\Model
{

    /**
     *
     * @var Integer
     */
    public $id_chat;

    /**
     *
     * @var Integer
     */
    public $id_user;

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
